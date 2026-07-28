{{-- resources/views/admin/filiales/create.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Nueva Filial')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('filiales.store') }}" method="POST">
            @csrf
            @include('admin.filiales._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Filial</button>
                <a href="{{ route('filiales.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection