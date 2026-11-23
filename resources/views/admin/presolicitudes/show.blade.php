@extends('layouts.admin')
@section('titulo','Revisión de presolicitud')
@push('estilos')<link rel="stylesheet" href="{{ asset('css/prestamos.css') }}">@endpush
@section('contenido')
@php($s=$presolicitud->pre_simulacion)
<div class="prestamo-admin"><p>Referencia: <strong>{{ $presolicitud->pre_referencia }}</strong> · Versión {{ $presolicitud->pre_version }}</p>
<div class="d-flex gap-2 mb-3 flex-wrap"><a class="btn btn-outline-dark" href="{{ route('presolicitudes.index') }}">Volver a la bandeja</a><a class="btn btn-dark" href="{{ route('presolicitudes.imprimir',$presolicitud) }}" target="_blank" rel="noopener">Imprimir formulario y plan actuales</a><a class="btn btn-outline-dark" href="{{ route('presolicitudes.imprimir',[$presolicitud,'original'=>1]) }}" target="_blank" rel="noopener">Ver envío original</a></div>
<div class="alert alert-info">{{ $s['mensaje'] }} · Cuota estimada Bs {{ number_format($s['cuota_mensual'],2,',','.') }}. Este módulo no aprueba préstamos.</div>
@foreach(array_merge($s['razones'],$s['avisos']) as $aviso)<p>{{ $aviso }}</p>@endforeach
<details><summary>Plan estimado de la versión actual</summary>@include('prestamos._plan',['simulacion'=>$s])</details>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('presolicitudes.update',$presolicitud) }}">
@csrf @method('PUT')<input type="hidden" name="version" value="{{ $presolicitud->pre_version }}">
<h5>Formulario prellenado</h5>@include('prestamos._formulario',['formulario'=>$presolicitud->pre_formulario,'conGarantes'=>$s['producto']==='REG_GAR'])
<details><summary>Corregir condiciones y generar una nueva estimación</summary><p>La estimación original permanecerá disponible. Marca recalcular para aplicar las correcciones con los parámetros vigentes.</p>
<label><input type="checkbox" name="recalcular" value="1"> Recalcular al guardar</label>
<div id="condicionesEvaluador">
@include('prestamos._prestamo',['productos'=>\App\Models\ProductoSimulador::orderBy('pro_id')->get(),'datosSim'=>$s['datos']])
@include('prestamos._condiciones_form',['datosSim'=>$s['datos']])
</div></details>
<div class="row g-3 mt-3"><div class="col-md-4"><label class="form-label">Estado de revisión</label><select class="form-select" name="estado">@foreach(\App\Models\PresolicitudPrestamo::ESTADOS as $estado)<option @selected($presolicitud->pre_estado===$estado)>{{ $estado }}</option>@endforeach</select></div><div class="col-md-8"><label class="form-label">Observaciones y documentación pendiente</label><textarea name="observaciones" class="form-control" maxlength="5000">{{ old('observaciones',$presolicitud->pre_observaciones) }}</textarea></div></div>
@if($presolicitud->pre_documentacion_validada_en)<p>Documentación completa validada: {{ $presolicitud->pre_documentacion_validada_en }}</p>@else<label class="d-block my-3"><input type="checkbox" name="documentacion_completa" value="1"> He verificado que la documentación exigida está completa y sin observaciones, omisiones ni alteraciones. Registrar inicio del plazo del artículo 24.</label>@endif
<button class="btn btn-dark mt-3">Guardar revisión</button>
</form>
<details><summary>Condiciones aceptadas el {{ $presolicitud->pre_aceptado_en }}</summary><p>{{ $presolicitud->pre_condiciones }}</p></details>
<h5 class="mt-4">Historial de revisiones</h5><ul>@forelse($presolicitud->revisiones as $rev)<li>{{ $rev->created_at }} · {{ $rev->usuario?->usu_nombre_completo }} · Versión {{ $rev->rev_version }} <a target="_blank" rel="noopener" href="{{ route('presolicitudes.imprimir',[$presolicitud,'version'=>$rev->rev_version]) }}">Ver formulario y plan</a><p>{{ $rev->rev_datos['estado'] }} · {{ $rev->rev_datos['observaciones']??'' }}</p></li>@empty<li>Sin revisiones. Se conserva el envío original.</li>@endforelse</ul>
</div>
@endsection
@push('scripts')
<script>
(()=>{const root=document.getElementById('condicionesEvaluador');if(!root)return;
root.querySelectorAll('input,select').forEach(el=>{el.name='simulacion['+el.name+']';el.required=false;});
const check=document.querySelector('[name="recalcular"]');
function update(){const codigo=root.querySelector('[name="simulacion[producto]"]').value;const ref=root.querySelector('[name="simulacion[operacion]"]').value==='REFINANCIAMIENTO';
root.querySelectorAll('input,select').forEach(el=>el.disabled=!check.checked);
root.querySelectorAll('[data-productos],[data-refinanciamiento]').forEach(el=>{const show=el.hasAttribute('data-refinanciamiento')?ref:el.dataset.productos.split(' ').includes(codigo);el.hidden=!show;el.querySelectorAll('input,select').forEach(i=>i.disabled=!show||!check.checked);});}
check.addEventListener('change',update);root.addEventListener('change',update);update();})();
</script>
@endpush
