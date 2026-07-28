{{-- resources/views/admin/comunicados/index.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Comunicados')

@section('contenido')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('comunicados.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg"></i> Nuevo Comunicado
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Publicación</th>
                    <th>Fijado</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comunicados as $com)
                    <tr>
                        <td>{{ $com->com_titulo }}</td>
                        <td>
                            @php
                                $coloresTipo = ['normal' => 'bg-secondary', 'modal' => 'bg-warning text-dark', 'destacado' => 'bg-primary', 'novedad' => 'bg-info text-dark'];
                            @endphp
                            <span class="badge {{ $coloresTipo[$com->com_tipo] ?? 'bg-secondary' }}">{{ ucfirst($com->com_tipo) }}</span>
                        </td>
                        <td>{{ $com->com_fecha_publicacion->format('d/m/Y') }}</td>
                        <td>
                            @if($com->com_fijado)
                                <i class="bi bi-pin-angle-fill text-warning"></i>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $com->com_estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $com->com_estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('comunicados.edit', $com) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('comunicados.destroy', $com) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este comunicado?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No hay comunicados registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $comunicados->links() }}
</div>
@endsection