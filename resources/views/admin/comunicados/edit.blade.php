{{-- resources/views/admin/comunicados/edit.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Editar Comunicado')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('comunicados.update', $comunicado) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.comunicados._form', ['comunicado' => $comunicado])
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Cambios</button>
                <a href="{{ route('comunicados.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection