{{-- resources/views/admin/convenios/index.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Convenios')

@section('contenido')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('convenios.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg"></i> Nuevo Convenio
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th></th>
                    <th>Título</th>
                    <th>Empresa/Entidad</th>
                    <th>PDF</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($convenios as $conv)
                    <tr>
                        <td>
                            @if($conv->conv_logo)
                                <img src="{{ Storage::url($conv->conv_logo) }}" style="width:48px;height:48px;object-fit:contain;border-radius:6px;">
                            @endif
                        </td>
                        <td>{{ $conv->conv_titulo }}</td>
                        <td>{{ $conv->conv_empresa ?? '—' }}</td>
                        <td>
                            @if($conv->conv_pdf_archivo)
                                <a href="{{ Storage::url($conv->conv_pdf_archivo) }}" target="_blank">
                                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $conv->conv_estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $conv->conv_estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('convenios.edit', $conv) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('convenios.destroy', $conv) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este convenio?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No hay convenios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $convenios->links() }}</div>
@endsection