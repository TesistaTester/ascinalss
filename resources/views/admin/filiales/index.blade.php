{{-- resources/views/admin/filiales/index.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Filiales')

@section('contenido')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('filiales.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg"></i> Nueva Filial
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Ciudad</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Orden</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filiales as $fil)
                    <tr>
                        <td>{{ $fil->fil_nombre }}</td>
                        <td>{{ $fil->fil_ciudad ?? '—' }}</td>
                        <td>{{ $fil->fil_direccion ?? '—' }}</td>
                        <td>{{ $fil->fil_telefono ?? '—' }}</td>
                        <td>{{ $fil->fil_orden }}</td>
                        <td>
                            <span class="badge {{ $fil->fil_estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $fil->fil_estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('filiales.edit', $fil) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('filiales.destroy', $fil) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar esta filial?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay filiales registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $filiales->links() }}</div>
@endsection