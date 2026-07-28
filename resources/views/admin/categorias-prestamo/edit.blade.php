{{-- resources/views/admin/categorias-prestamo/edit.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Editar Categoría: ' . $categoria->cat_nombre)

@section('contenido')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><strong>Datos de la categoría</strong></div>
    <div class="card-body">
        <form action="{{ route('categorias-prestamo.update', $categoria) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.categorias-prestamo._form', ['categoria' => $categoria])
            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Guardar Cambios</button>
                <a href="{{ route('categorias-prestamo.index') }}" class="btn btn-outline-secondary">Volver</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><strong>Documentos de esta categoría</strong></div>
    <div class="card-body p-0">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Etiqueta</th>
                    <th>Tipo</th>
                    <th>Orden</th>
                    <th>Archivo</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categoria->documentos as $doc)
                    <tr>
                        <td>{{ $doc->doc_etiqueta }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($doc->doc_tipo) }}</span></td>
                        <td>{{ $doc->doc_orden }}</td>
                        <td>
                            <a href="{{ Storage::url($doc->doc_pdf_archivo) }}" target="_blank">
                                <i class="bi bi-file-earmark-pdf text-danger"></i> Ver PDF
                            </a>
                        </td>
                        <td class="text-end">
                            <form action="{{ route('documentos-prestamo.destroy', $doc) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este documento?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Sin documentos aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Agregar nuevo documento</strong></div>
    <div class="card-body">
        <form action="{{ route('documentos-prestamo.store', $categoria) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tipo de documento</label>
                    <select name="doc_tipo" class="form-select" required>
                        <option value="requisitos">Requisitos</option>
                        <option value="contrato">Contrato</option>
                        <option value="formulario">Formulario</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Etiqueta (texto del enlace)</label>
                    <input type="text" name="doc_etiqueta" class="form-control" required
                           placeholder="Ej. Ver Requisitos">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Orden</label>
                    <input type="number" name="doc_orden" class="form-control" value="0">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Archivo PDF</label>
                    <input type="file" name="doc_pdf_archivo" class="form-control" accept="application/pdf" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-plus-lg"></i> Agregar Documento
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection