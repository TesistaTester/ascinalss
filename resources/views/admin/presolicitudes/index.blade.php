@extends('layouts.admin')
@section('titulo','Presolicitudes de préstamos')
@section('contenido')
<p>Recepción y preparación de atención presencial. Esta bandeja no aprueba préstamos.</p>
<form method="GET" class="row g-2 mb-3"><div class="col-md-5"><label class="form-label">Referencia</label><input class="form-control" name="referencia" value="{{ request('referencia') }}" maxlength="36"></div><div class="col-md-4"><label class="form-label">Estado</label><select name="estado" class="form-select"><option value="">Todos</option>@foreach(\App\Models\PresolicitudPrestamo::ESTADOS as $estado)<option @selected(request('estado')===$estado)>{{ $estado }}</option>@endforeach</select></div><div class="col-md-3 align-self-end"><button class="btn btn-dark">Buscar</button></div></form>
<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Referencia</th><th>Recibida</th><th>Solicitante</th><th>Modalidad</th><th>Estado</th><th></th></tr></thead><tbody>
@forelse($presolicitudes as $pre)<tr><td>{{ $pre->pre_referencia }}</td><td>{{ $pre->created_at }}</td><td>{{ $pre->pre_formulario['nombres']??'' }}</td><td>{{ $pre->pre_simulacion['nombre_producto'] }}</td><td>{{ $pre->pre_estado }}</td><td><a class="btn btn-sm btn-outline-dark" href="{{ route('presolicitudes.show',$pre) }}">Revisar</a></td></tr>@empty<tr><td colspan="6">No se encontraron presolicitudes.</td></tr>@endforelse
</tbody></table></div>
<div class="d-flex justify-content-between">@if($presolicitudes->previousPageUrl())<a href="{{ $presolicitudes->previousPageUrl() }}">Anterior</a>@endif<span>Página {{ $presolicitudes->currentPage() }} de {{ $presolicitudes->lastPage() }}</span>@if($presolicitudes->nextPageUrl())<a href="{{ $presolicitudes->nextPageUrl() }}">Siguiente</a>@endif</div>
@endsection
