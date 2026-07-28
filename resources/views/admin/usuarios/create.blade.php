{{-- resources/views/admin/usuarios/create.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Nuevo Usuario')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            @include('admin.usuarios._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Usuario</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection