{{-- resources/views/admin/servicios/edit.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Editar Servicio')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('servicios.update', $servicio) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.servicios._form', ['servicio' => $servicio])
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Cambios</button>
                <a href="{{ route('servicios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection