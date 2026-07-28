{{-- resources/views/admin/categorias-prestamo/index.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Apoyo Económico - Categorías de Préstamo')

@section('contenido')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('categorias-prestamo.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg"></i> Nueva Categoría
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Documentos</th>
                    <th>Orden</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categorias as $cat)
                    <tr>
                        <td>{{ $cat->cat_nombre }}</td>
                        <td><code class="small">{{ $cat->cat_slug }}</code></td>
                        <td>
                            <span class="badge bg-info-subtle text-info-emphasis">
                                {{ $cat->documentos_count }} archivo(s)
                            </span>
                        </td>
                        <td>{{ $cat->cat_orden }}</td>
                        <td>
                            <span class="badge {{ $cat->cat_estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $cat->cat_estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('categorias-prestamo.edit', $cat) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Gestionar
                            </a>
                            <form action="{{ route('categorias-prestamo.destroy', $cat) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar esta categoría? También se eliminarán sus documentos asociados.');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No hay categorías registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $categorias->links() }}</div>
@endsection