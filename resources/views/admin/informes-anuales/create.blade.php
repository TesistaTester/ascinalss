{{-- resources/views/admin/informes-anuales/create.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Nuevo Informe Anual')

@section('contenido')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('informes-anuales.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.informes-anuales._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Publicar Informe</button>
                <a href="{{ route('informes-anuales.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection