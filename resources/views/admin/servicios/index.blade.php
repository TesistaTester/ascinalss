{{-- resources/views/admin/servicios/index.blade.php --}}
@extends('layouts.admin')
@section('titulo', 'Servicios')
@section('contenido')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('servicios.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg"></i> Nuevo Servicio</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr><th></th><th>Título</th><th>Dirección</th><th>Capacidad</th><th>Estado</th><th class="text-end">Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($servicios as $ser)
                    <tr>
                        <td>
                            @if($ser->ser_imagen)
                                <img src="{{ Storage::url($ser->ser_imagen) }}" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
                            @endif
                        </td>
                        <td>{{ $ser->ser_titulo }}</td>
                        <td>{{ $ser->ser_direccion }}</td>
                        <td>{{ $ser->ser_capacidad ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $ser->ser_estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $ser->ser_estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('servicios.edit', $ser) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('servicios.destroy', $ser) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No hay servicios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $servicios->links() }}</div>
@endsection