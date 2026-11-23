<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Simulación de préstamo ASCINALSS</title>
<style>body{font:12px Arial,sans-serif;color:#172334;margin:24px auto;max-width:1000px;padding:0 16px}header{display:flex;align-items:center;gap:24px}header img{width:100px}h1{font-size:20px}h2{font-size:16px}table{width:100%;border-collapse:collapse;margin:15px 0}th,td{padding:6px;border:1px solid #aeb6c0;text-align:right}th:first-child,td:first-child{text-align:left}thead{display:table-header-group}tfoot{display:table-row-group}.datos{display:grid;grid-template-columns:1fr 1fr;gap:10px}.campo{border-bottom:1px solid #adb5bd;padding:8px;overflow-wrap:anywhere}.nota{font-size:11px;line-height:1.5}.firma{margin:55px 0 20px;display:flex;justify-content:space-around}.sim-tabla{overflow:auto}@page{size:A4;margin:14mm}@media print{body{margin:0;padding:0;max-width:none}.no-print{display:none}.formulario{break-before:page}.sim-tabla{overflow:visible}tr,.campo,.garante{break-inside:avoid}a{color:inherit}}</style></head><body>
<button class="no-print" onclick="window.print()">Imprimir / Guardar PDF</button>
<header><img src="{{ asset('img/logo.png') }}" alt="ASCINALSS"><div><h1>Simulación de plan de pagos</h1><p>{{ $simulacion['nombre_producto'] }}</p></div></header>
@if($referencia)<p>Presolicitud: {{ $referencia }}</p>@endif
@php($tipoTasa=['MENSUAL'=>'mensual','ANUAL'=>'anual nominal','ANUAL_NOMINAL'=>'anual nominal','ANUAL_EFECTIVA'=>'anual efectiva'][$simulacion['tipo_tasa']]??'configurada')
@php($metodo=['FRANCES'=>'Cuota base uniforme sobre saldos','AMORTIZACION_CONSTANTE'=>'Amortización constante','INTERES_PLANO'=>'Interés sobre capital original'][$simulacion['metodo']]??'Configurado')
<div class="datos"><p>Monto: Bs {{ number_format($simulacion['monto_solicitado'],2,',','.') }}</p><p>Plazo: {{ $simulacion['plazo'] }} meses</p><p>Tasa: {{ number_format($simulacion['tasa'],4,',','.') }}% {{ $tipoTasa }}</p><p>Método: {{ $metodo }} · Moneda: Bolivianos</p><p>Generado: {{ \Carbon\Carbon::parse($simulacion['generado_en'])->format('d/m/Y H:i') }}</p><p>Gasto inicial: Bs {{ number_format($simulacion['cargo_inicial'],2,',','.') }} · Neto estimado: Bs {{ number_format($simulacion['neto_estimado'],2,',','.') }}</p></div>
<p>{{ $simulacion['mensaje'] }}</p>
@foreach(array_merge($simulacion['razones'],$simulacion['avisos']) as $aviso)<p class="nota">{{ $aviso }}</p>@endforeach
@include('prestamos._plan')
<p class="nota">El total de cuotas incluye únicamente los cargos mensuales mostrados; el gasto inicial se presenta por separado. Fechas y condiciones definitivas sujetas a calificación y desembolso.</p>
<p class="nota">{{ $simulacion['condiciones'] }}</p>
@if($formulario)
<section class="formulario"><h1>Formulario de presolicitud de préstamo</h1><p>Referencia: {{ $referencia }} · Modalidad: {{ $simulacion['nombre_producto'] }} · Operación: {{ $simulacion['datos']['operacion'] }}</p><p>Monto: Bs {{ number_format($simulacion['monto_solicitado'],2,',','.') }} · Plazo: {{ $simulacion['plazo'] }} meses</p>
<h2>Datos del solicitante</h2><div class="datos">@foreach(\App\Services\Prestamos\LoanPolicy::fields() as $key=>$label)<div class="campo"><strong>{{ $label }}:</strong> {{ $formulario[$key]??'____________________' }}</div>@endforeach</div>
@if($simulacion['producto']==='REG_GAR')
@for($i=0;$i<2;$i++)<div class="garante"><h2>Datos del garante {{ $i+1 }}</h2><div class="datos">@foreach(\App\Services\Prestamos\LoanPolicy::garanteFields() as $key=>$label)<div class="campo"><strong>{{ $label }}:</strong> {{ $formulario['garantes'][$i][$key]??'____________________' }}</div>@endforeach</div></div>@endfor
@endif
<p class="nota">Documento prellenado para revisión en oficina. La recepción digital no sustituye la firma del formulario oficial, contratos ni autorizaciones institucionales. Los datos pendientes deberán completarse y verificarse antes de formalizar la solicitud.</p>
<h2>Declaraciones del formulario institucional para revisión y firma en oficina</h2>
<p class="nota">Declaramos la absoluta veracidad de la información proporcionada y autorizamos a ASCINALSS a investigar nuestros antecedentes en caso de falsedad, si se comprobara asumimos plena responsabilidad civil, penal y disciplinaria.</p>
<p class="nota">Autorizamos el descuento mensual de nuestras obligaciones a través de las planillas del Ministerio de Defensa, asumiendo los gastos operativos correspondientes.</p>
@if($simulacion['producto']==='REG_GAR')<p class="nota">Los garantes asumimos la presente obligación de forma solidaria, mancomunada e indivisible.</p>@endif
<p class="nota">A simple firma, las partes nos sometemos a la normativa de ASCINALSS y AUTORIZAMOS EXPRESAMENTE la ejecución del "Procedimiento de Liquidación de Oficio" sobre nuestra Devolución de Aportes (D.A.A.R.O.) en caso de mora de 90 días, desvinculación o insolvencia, renunciando irrevocablemente al beneficio de excusión y a todo requerimiento judicial previo.</p>
<p class="nota">De generarse un "Saldo al Descubierto", autorizamos la persecución coactiva externa sobre otros beneficios castrenses.</p>
<div class="firma"><span>____________________<br>Solicitante</span>@if($simulacion['producto']==='REG_GAR')<span>____________________<br>Primer garante</span><span>____________________<br>Segundo garante</span>@endif</div>
<p class="nota">{{ $simulacion['condiciones'] }}</p></section>
@endif
</body></html>
