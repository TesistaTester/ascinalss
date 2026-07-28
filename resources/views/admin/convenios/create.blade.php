{{-- resources/views/admin/convenios/create.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Nuevo Convenio')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('convenios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.convenios._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Convenio</button>
                <a href="{{ route('convenios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection