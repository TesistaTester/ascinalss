{{-- resources/views/admin/usuarios/edit.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Editar Usuario')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.usuarios._form', ['usuario' => $usuario])
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Cambios</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection