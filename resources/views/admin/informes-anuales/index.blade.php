{{-- resources/views/admin/informes-anuales/index.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Informes Anuales')

@section('contenido')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('informes-anuales.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg"></i> Nuevo Informe
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Año</th>
                    <th>Título</th>
                    <th>PDF</th>
                    <th>Fecha de publicación</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($informes as $inf)
                    <tr>
                        <td>{{ $inf->inf_anio }}</td>
                        <td>{{ $inf->inf_titulo }}</td>
                        <td>
                            <a href="{{ Storage::url($inf->inf_pdf_archivo) }}" target="_blank">
                                <i class="bi bi-file-earmark-pdf text-danger"></i> Ver PDF
                            </a>
                        </td>
                        <td>{{ $inf->inf_fecha_publicacion?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $inf->inf_estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $inf->inf_estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('informes-anuales.edit', $inf) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('informes-anuales.destroy', $inf) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este informe?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No hay informes anuales registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $informes->links() }}</div>
@endsection