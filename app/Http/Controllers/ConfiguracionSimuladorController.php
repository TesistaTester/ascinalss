<?php

namespace App\Http\Controllers;

use App\Models\HistorialCondicionPrestamo;
use App\Models\ParametroFinanciero;
use App\Models\ProductoSimulador;
use App\Models\ReglaCapacidadPrestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConfiguracionSimuladorController extends Controller
{
    public function index()
    {
        $productos = ProductoSimulador::with(['parametro', 'reglaCapacidad'])->orderBy('pro_id')->get();
        return view('admin.simulador-prestamos.index', compact('productos'));
    }

    public function update(Request $request, ProductoSimulador $producto)
    {
        $politica = $request->validate([
            'politica' => 'required|array', 'politica.fuente' => 'required|string|max:500',
            'politica.permitir_aproximacion' => 'required|boolean', 'politica.usar_capacidad_referencial' => 'required|boolean',
            'politica.porcentaje_referencial' => 'required|numeric|min:0|max:1', 'politica.residual_referencial' => 'required|numeric|min:0|max:1000000',
            'politica.limite_servicio_meses' => 'required|integer|min:1|max:420',
            'politica.aportes_minimos' => 'required|integer|min:1|max:720',
            'politica.cargo_inicial' => 'required|numeric|min:0|max:100000', 'politica.cargo_mensual' => 'required|numeric|min:0|max:100000',
            'politica.cargos_pendientes' => 'nullable|string|max:1000', 'escalas_json'=>'required|json',
        ])['politica'];
        $politica=array_intersect_key($politica, array_flip(['fuente','permitir_aproximacion','usar_capacidad_referencial','porcentaje_referencial','residual_referencial','limite_servicio_meses','aportes_minimos','cargo_inicial','cargo_mensual','cargos_pendientes']));
        $politica['cargos_pendientes']=$politica['cargos_pendientes']??'';
        $escalas = json_decode($request->input('escalas_json'), true);
        $anterior = -1;
        if (!is_array($escalas) || !array_is_list($escalas) || count($escalas)>30) throw ValidationException::withMessages(['escalas_json'=>'Ingresa una lista de tramos válida.']);
        foreach ($escalas as $tramo) {
            if (!is_array($tramo) || count($tramo)!==3 || !array_is_list($tramo) || !is_int($tramo[0]) || !is_int($tramo[1])
                || !is_numeric($tramo[2]) || $tramo[0]<0 || $tramo[0]<=$anterior || $tramo[1]<$tramo[0] || $tramo[1]>720 || $tramo[2]<=0 || $tramo[2]>10000000) {
                throw ValidationException::withMessages(['escalas_json'=>'Tramos [aporte inicial, aporte final, monto], ordenados y sin solapamientos.']);
            }
            $anterior=$tramo[1];
        }
        $politica['escalas']=$escalas;
        $datos = $request->validate([
            'pro_nombre' => 'required|string|max:255', 'pro_monto_minimo' => 'nullable|numeric|min:0',
            'pro_monto_maximo' => 'required|numeric|min:0.01', 'pro_moneda' => 'required|string|size:3',
            'pro_plazo_minimo_meses' => 'nullable|integer|min:1|max:300', 'pro_plazo_maximo_meses' => 'required|integer|min:1|max:300',
            'pro_cantidad_garantes' => 'nullable|integer|min:0|max:10', 'pro_vigente_desde' => 'nullable|date',
            'pro_vigente_hasta' => 'nullable|date|after_or_equal:pro_vigente_desde',
            'par_tasa' => 'nullable|numeric|min:0|max:100', 'par_tipo_tasa' => 'nullable|in:ANUAL,ANUAL_NOMINAL,ANUAL_EFECTIVA,MENSUAL,PERSONALIZADA',
            'par_base_interes' => 'nullable|in:CAPITAL,SALDO,PERSONALIZADA',
            'par_metodo_amortizacion' => 'required|in:FRANCES,AMORTIZACION_CONSTANTE,INTERES_PLANO,PERSONALIZADO,PENDIENTE',
            'par_frecuencia_pago' => 'required|in:MENSUAL', 'par_estado' => 'required|in:CONFIRMADO,INFERIDO,PENDIENTE,HISTORICO',
            'reg_porcentaje_maximo_liquido' => 'nullable|numeric|min:0|max:1', 'reg_liquido_minimo_residual' => 'nullable|numeric|min:0',
            'reg_factor_seguridad' => 'nullable|numeric|min:0|max:1', 'reg_estado' => 'required|in:CONFIRMADO,INFERIDO,PENDIENTE,HISTORICO',
        ]);

        if (($datos['pro_monto_minimo']??0)>$datos['pro_monto_maximo'] || ($datos['pro_plazo_minimo_meses']??1)>$datos['pro_plazo_maximo_meses']) throw ValidationException::withMessages(['pro_monto_minimo'=>'Los mínimos no pueden superar los máximos.']);
        if ($datos['pro_moneda']!=='BOB') throw ValidationException::withMessages(['pro_moneda'=>'La simulación reglamentaria utiliza BOB.']);
        if (in_array($datos['par_estado'],['CONFIRMADO','INFERIDO']) && (($datos['par_tasa']??null)===null || !in_array($datos['par_tipo_tasa']??null,['ANUAL','ANUAL_NOMINAL','ANUAL_EFECTIVA','MENSUAL']) || !in_array($datos['par_metodo_amortizacion'],['FRANCES','AMORTIZACION_CONSTANTE','INTERES_PLANO']))) throw ValidationException::withMessages(['par_estado'=>'Completa tasa, periodicidad y método soportado.']);
        if (in_array($datos['par_metodo_amortizacion'],['FRANCES','AMORTIZACION_CONSTANTE']) && ($datos['par_base_interes']??null)!=='SALDO') throw ValidationException::withMessages(['par_base_interes'=>'El método seleccionado requiere base SALDO.']);
        if ($datos['par_metodo_amortizacion']==='INTERES_PLANO' && ($datos['par_base_interes']??null)!=='CAPITAL') throw ValidationException::withMessages(['par_base_interes'=>'El interés plano requiere CAPITAL.']);
        foreach (['reg_usar_porcentaje_maximo'=>'reg_porcentaje_maximo_liquido','reg_usar_liquido_minimo'=>'reg_liquido_minimo_residual','reg_usar_factor_seguridad'=>'reg_factor_seguridad'] as $activar=>$valor) {
            if ($request->boolean($activar) && ($datos[$valor]??null)===null) throw ValidationException::withMessages([$valor=>'La regla activada requiere un valor.']);
        }
        DB::transaction(function () use ($request, $producto, $datos, $politica) {
            $camposProducto = array_filter($datos, fn ($key) => str_starts_with($key, 'pro_'), ARRAY_FILTER_USE_KEY);
            $camposProducto['pro_activo'] = $request->boolean('pro_activo');
            $camposProducto['pro_requiere_garantes'] = $request->boolean('pro_requiere_garantes');
            $camposProducto['pro_considera_antiguedad'] = $request->boolean('pro_considera_antiguedad');
            $camposProducto['pro_reglas_simulacion'] = $politica;
            $this->registrarCambios($producto, $camposProducto, $producto->pro_id);
            $producto->update($camposProducto);

            $parametros = array_filter($datos, fn ($key) => str_starts_with($key, 'par_'), ARRAY_FILTER_USE_KEY);
            $parametro = ParametroFinanciero::firstOrNew(['par_producto_id' => $producto->pro_id]);
            $this->registrarCambios($parametro, $parametros, $producto->pro_id);
            $parametro->fill($parametros)->save();
            $reglas = array_filter($datos, fn ($key) => str_starts_with($key, 'reg_'), ARRAY_FILTER_USE_KEY);
            foreach (['reg_usar_porcentaje_maximo', 'reg_usar_liquido_minimo', 'reg_usar_factor_seguridad', 'reg_considerar_refinanciamiento', 'reg_liberar_cuota_refinanciada'] as $campo) {
                $reglas[$campo] = $request->boolean($campo);
            }
            $regla = ReglaCapacidadPrestamo::firstOrNew(['reg_producto_id' => $producto->pro_id]);
            $this->registrarCambios($regla, $reglas, $producto->pro_id);
            $regla->fill($reglas)->save();
        });
        return back()->with('exito', 'Configuración del simulador actualizada correctamente.');
    }

    private function registrarCambios($modelo, array $nuevosValores, int $productoId): void
    {
        if (!$modelo->exists) return;
        foreach ($nuevosValores as $campo => $valor) {
            $serializar = fn ($v) => is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : (string) $v;
            if ($serializar($modelo->{$campo}) === $serializar($valor)) continue;
            HistorialCondicionPrestamo::create([
                'his_producto_id' => $productoId,
                'his_parametro' => $campo,
                'his_valor' => $serializar($modelo->{$campo}),
                'his_tipo' => 'VALOR',
                'his_fuente' => 'CMS, usuario '.auth()->id(),
                'his_estado' => 'HISTORICO',
                'his_vigente_hasta' => now()->toDateString(),
            ]);
        }
    }
}
