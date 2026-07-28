{{-- resources/views/admin/convenios/edit.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Editar Convenio')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('convenios.update', $convenio) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.convenios._form', ['convenio' => $convenio])
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Cambios</button>
                <a href="{{ route('convenios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection