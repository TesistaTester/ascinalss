<?php
namespace App\Services\Prestamos;

use App\Models\ParametroFinanciero;
use App\Models\ProductoSimulador;
use Carbon\CarbonImmutable;

class LoanSimulationService
{
    public function __construct(private LoanAmortizationService $amortizacion, private LoanCapacityService $capacidad) {}

    public function simular(ProductoSimulador $producto, array $datos): array
    {
        if (!$producto->pro_activo || !$this->vigente($producto,'pro')) return $this->pendiente('La modalidad no está vigente.');
        $p=LoanPolicy::for($producto); $codigo=$producto->pro_codigo;
        $monto=(float)$datos['monto_solicitado']; $plazo=(int)$datos['plazo'];
        $ref=$datos['operacion']==='REFINANCIAMIENTO';
        $razones=[]; $particular=[]; $avisos=[$p['cargos_pendientes']];
        $servicio=(int)$datos['anios_servicio']*12+(int)$datos['meses_servicio'];
        $plazoMax=min((int)$producto->pro_plazo_maximo_meses,max(0,$p['limite_servicio_meses']-$servicio));
        $maximo=(float)$producto->pro_monto_maximo; $aportes=(int)($datos['aportes']??0);
        if (!$datos['socio_habilitado']) $razones[]='Debes ser socio habilitado con aportes activos.';
        if ($datos['mora']) $razones[]='La mora vigente como titular o garante impide una nueva operación.';
        if ($datos['situacion']==='PASIVO') $particular[]='La jubilación o servicio pasivo requiere evaluación particular de DAARO.';
        if ($p['escalas']) {
            if ($aportes<$p['aportes_minimos']) $razones[]='No alcanzas el mínimo de aportes de la modalidad.';
            $tramo=collect($p['escalas'])->first(fn($t)=>$aportes>=$t[0] && $aportes<=$t[1]);
            if ($tramo) $maximo=min($maximo,(float)$tramo[2]);
            elseif ($aportes>=$p['aportes_minimos']) $particular[]='Tu tramo de aportes requiere revisión particular.';
        }
        if ($codigo==='EMERG') {
            if (!($datos['aportes_continuos']??false)) $razones[]='Se requieren seis aportes continuos.';
            if ((int)($datos['emergencias_mes']??0)>=2) $razones[]='Ya alcanzaste dos operaciones de Emergencia en el mes.';
        }
        if ($codigo==='INIC' && (!($datos['recien_egresado']??false)||$aportes<$p['aportes_minimos'])) $razones[]='Iniciación exige ser sargento recién egresado, estar en el primer año como asociado y contar con el mínimo de aportes.';
        if ($codigo==='REG_GAR' && !($datos['dos_garantes']??false)) $razones[]='Se requieren dos socios activos que puedan actuar como garantes.';
        if ($codigo==='REG_SIN') $avisos[]='ASCINALSS verificará la cobertura de tus aportes y rendimientos (DAARO).';
        if ($codigo==='REG_GAR') $avisos[]='ASCINALSS verificará a ambos garantes, incluida capacidad y máximo de dos garantías vigentes.';
        if ($servicio>300) $particular[]='Con más de 25 años de servicio se debe verificar DAARO proyectado y fecha de jubilación.';
        if ($plazo>$plazoMax || $plazo<($producto->pro_plazo_minimo_meses??1)) $razones[]='El plazo no cumple los límites de la modalidad y los 35 años de servicio.';
        if ($monto<max((float)($producto->pro_monto_minimo??1),$codigo==='EMERG'?300:1)) $razones[]='El monto es inferior al mínimo de la modalidad.';
        if (!$ref && in_array($codigo,['EMERG','REG_SIN','INIC'])) $maximo=max(0,$maximo-(float)$datos['saldo_modalidad']);
        if ($monto>$maximo) $razones[]='El monto supera el límite disponible según modalidad, aportes y saldos declarados.';
        $parametro=$producto->parametro;
        if ($parametro && (!$this->vigente($parametro,'par')||$parametro->par_estado==='HISTORICO')) return $this->pendiente('Las condiciones financieras no están vigentes.');
        if (!$parametro || $parametro->par_estado==='PENDIENTE' || $parametro->par_metodo_amortizacion==='PENDIENTE') {
            if (!$p['permitir_aproximacion']) return $this->pendiente('El cálculo aproximado no está habilitado.');
            $corto=in_array($codigo,['EMERG','INIC']);
            $parametro=new ParametroFinanciero(['par_tasa'=>$corto?2:11.99,'par_tipo_tasa'=>$corto?'MENSUAL':'ANUAL_NOMINAL','par_metodo_amortizacion'=>'FRANCES','par_base_interes'=>'SALDO']);
            $avisos[]='Aproximación francesa con períodos mensuales uniformes y tasas del reglamento; no reproduce los ajustes diarios del sistema institucional.';
        }
        $tasa=$this->amortizacion->tasaPeriodica($parametro);
        if ($parametro->par_estado==='INFERIDO') $avisos[]='El método financiero configurado es una aproximación, no una fórmula institucional confirmada.';
        $cuota=$this->amortizacion->calcularCuota($monto,$plazo,$parametro);
        if ($cuota===null || !is_finite($cuota)) return $this->pendiente('El método configurado necesita revisión.');
        $fecha=!empty($datos['fecha_inicio'])?CarbonImmutable::parse($datos['fecha_inicio']):null;
        $liquido=(float)$datos['liquido_pagable']; $bonos=(float)($datos['bonos_temporales']??0);
        if ($codigo==='EMERG' && $bonos>0 && (!$fecha||$fecha->addMonthsNoOverflow($plazo)->year!==$fecha->year)) {
            $liquido=max(0,$liquido-$bonos); $avisos[]='Se excluyeron bonos temporales: el plazo excede la gestión o no se indicó fecha.';
        }
        $liberada=$ref?(float)($datos['cuota_liberada']??0):0;
        $regla=$producto->reglaCapacidad; $capacidad=null;
        if ($regla && in_array($regla->reg_estado,['CONFIRMADO','INFERIDO']) && $this->vigente($regla,'reg')) {
            $capacidad=$this->capacidad->calcular($liquido,$regla,$liberada);
            $avisos[]='Capacidad calculada con las condiciones configuradas por ASCINALSS.';
        } elseif ($p['usar_capacidad_referencial']) {
            $ajustado=$liquido+$liberada;
            $capacidad=max(0,min($ajustado*$p['porcentaje_referencial'],$ajustado-$p['residual_referencial']));
            $avisos[]='Supuesto referencial: menor entre '.($p['porcentaje_referencial']*100).'% del líquido ajustado y líquido ajustado menos Bs '.$p['residual_referencial'].'. Sujeto a calificación institucional.';
        }
        if ($capacidad===null) $particular[]='No hay regla de capacidad habilitada; solo puede estimarse la cuota.';
        $cargo=(float)$p['cargo_mensual'];
        $unitaria=$this->amortizacion->calcularCuota(1,$plazo,$parametro);
        $maxPersonal=$capacidad===null||!$unitaria?null:min($maximo,max(0,$capacidad-$cargo)/$unitaria);
        $neto=round($monto-$p['cargo_inicial']-($ref?(float)$datos['saldo_cancelar']:0),2);
        if ($neto<=0) $razones[]='El monto no deja un neto positivo después de gastos y liquidaciones declaradas.';
        $estado=$razones?'INCUMPLE_REQUISITO':($particular?'REQUIERE_EVALUACION':($cuota+$cargo<=$capacidad?'COMPATIBLE':'SUPERA_CAPACIDAD'));
        $plan=$this->plan($monto,$plazo,$tasa,$parametro->par_metodo_amortizacion,$cargo,$fecha);
        $sugerido=null;
        if ($estado==='SUPERA_CAPACIDAD') for($n=$plazo+1;$n<=$plazoMax;$n++) {
            if ($this->amortizacion->calcularCuota($monto,$n,$parametro)+$cargo<=$capacidad) { $sugerido=$n; break; }
        }
        return [
            'estado'=>$estado,'mensaje'=>match($estado) {
                'COMPATIBLE'=>'Podrías acceder al préstamo solicitado, sujeto a calificación de ASCINALSS.',
                'SUPERA_CAPACIDAD'=>'El monto supera tu capacidad estimada. Puedes reducirlo o ajustar el plazo permitido.',
                'INCUMPLE_REQUISITO'=>'Según los datos ingresados, no cumples uno o más requisitos de esta modalidad.',
                default=>'Tu caso requiere evaluación particular de ASCINALSS.',
            },
            'puede_enviar'=>$estado==='COMPATIBLE','razones'=>array_merge($razones,$particular),'avisos'=>array_values(array_filter($avisos)),
            'producto'=>$codigo,'nombre_producto'=>$producto->pro_nombre,'monto_solicitado'=>round($monto,2),'plazo'=>$plazo,
            'plazo_maximo'=>$plazoMax,'plazo_sugerido'=>$sugerido,'cuota_mensual'=>round($cuota+$cargo,2),
            'cuota_maxima_estimada'=>$capacidad===null?null:round($capacidad,2),
            'monto_maximo_estimado'=>$maxPersonal===null?null:floor($maxPersonal*100)/100,'maximo_modalidad'=>$maximo,
            'neto_estimado'=>$neto,'cargo_inicial'=>$p['cargo_inicial'],'tasa'=>$parametro->par_tasa,
            'tipo_tasa'=>$parametro->par_tipo_tasa,'metodo'=>$parametro->par_metodo_amortizacion,'plan'=>$plan,
            'totales'=>['capital'=>round(array_sum(array_column($plan,'capital')),2),'interes'=>round(array_sum(array_column($plan,'interes')),2),
                'cargos'=>round(array_sum(array_column($plan,'cargos')),2),'cuotas'=>round(array_sum(array_column($plan,'cuota')),2)],
            'fuente'=>$p['fuente'],'condiciones'=>LoanPolicy::CONDICIONES,'condiciones_version'=>LoanPolicy::CONDICIONES_VERSION,
            'generado_en'=>now()->toIso8601String(),'datos'=>$datos,
        ];
    }
    private function vigente($modelo,string $prefijo): bool
    {
        return (!$modelo->{$prefijo.'_vigente_desde'}||$modelo->{$prefijo.'_vigente_desde'}->lte(now()->startOfDay()))
            && (!$modelo->{$prefijo.'_vigente_hasta'}||$modelo->{$prefijo.'_vigente_hasta'}->gte(now()->startOfDay()));
    }
    private function pendiente(string $mensaje): array { return ['estado'=>'REQUIERE_EVALUACION','mensaje'=>$mensaje,'puede_enviar'=>false]; }

    private function plan(float $monto,int $meses,float $tasa,string $metodo,float $cargo,?CarbonImmutable $fecha): array
    {
        $parametro=new ParametroFinanciero(['par_tasa'=>$tasa*100,'par_tipo_tasa'=>'MENSUAL','par_metodo_amortizacion'=>$metodo]);
        $base=round($this->amortizacion->calcularCuota($monto,$meses,$parametro),2);
        $saldo=round($monto,2); $plan=[];
        for($n=1;$n<=$meses;$n++) {
            $interes=round(($metodo==='INTERES_PLANO'?$monto:$saldo)*$tasa,2);
            $capital=$metodo==='FRANCES'?round($base-$interes,2):round($monto/$meses,2);
            $capital=$n===$meses?$saldo:min($saldo,max(0,$capital));
            $saldo=round(max(0,$saldo-$capital),2);
            $plan[]=['numero'=>$n,'fecha'=>$fecha?->addMonthsNoOverflow($n)->toDateString(),'capital'=>$capital,
                'interes'=>$interes,'cargos'=>round($cargo,2),'cuota'=>round($capital+$interes+$cargo,2),'saldo'=>$saldo];
        }
        return $plan;
    }
}
