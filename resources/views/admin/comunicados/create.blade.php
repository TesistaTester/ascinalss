{{-- resources/views/admin/comunicados/create.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Nuevo Comunicado')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('comunicados.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.comunicados._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Publicar Comunicado</button>
                <a href="{{ route('comunicados.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection