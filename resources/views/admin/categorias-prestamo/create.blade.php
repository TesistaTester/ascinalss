{{-- resources/views/admin/categorias-prestamo/create.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Nueva Categoría de Préstamo')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('categorias-prestamo.store') }}" method="POST">
            @csrf
            @include('admin.categorias-prestamo._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Categoría</button>
                <a href="{{ route('categorias-prestamo.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
        <div class="alert alert-info mt-3 mb-0 small">
            <i class="bi bi-info-circle"></i> Una vez creada la categoría, podrás agregar sus documentos (requisitos, contratos, formularios) desde la pantalla de edición.
        </div>
    </div>
</div>
@endsection