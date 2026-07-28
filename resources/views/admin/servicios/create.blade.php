{{-- resources/views/admin/servicios/create.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Nuevo Servicio')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('servicios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.servicios._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Servicio</button>
                <a href="{{ route('servicios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection