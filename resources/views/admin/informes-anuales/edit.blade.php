{{-- resources/views/admin/informes-anuales/edit.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Editar Informe Anual')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('informes-anuales.update', $informe) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.informes-anuales._form', ['informe' => $informe])
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Cambios</button>
                <a href="{{ route('informes-anuales.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection