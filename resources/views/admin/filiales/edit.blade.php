{{-- resources/views/admin/filiales/edit.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Editar Filial')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('filiales.update', $filial) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.filiales._form', ['filial' => $filial])
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Cambios</button>
                <a href="{{ route('filiales.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection