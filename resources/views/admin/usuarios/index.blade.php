{{-- resources/views/admin/usuarios/index.blade.php --}}
@extends('layouts.admin')

@section('titulo', 'Usuarios del Panel')

@section('contenido')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('usuarios.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg"></i> Nuevo Usuario
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Usuario</th>
                    <th>Nombre completo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usu)
                    <tr>
                        <td>{{ $usu->usu_nombre }}</td>
                        <td>{{ $usu->usu_nombre_completo }}</td>
                        <td>
                            @php
                                $etiquetasRol = [1 => 'Admin', 2 => 'Editor', 3 => 'Directorio'];
                                $coloresRol = [1 => 'bg-dark', 2 => 'bg-primary', 3 => 'bg-secondary'];
                            @endphp
                            <span class="badge {{ $coloresRol[$usu->usu_rol] ?? 'bg-secondary' }}">
                                {{ $etiquetasRol[$usu->usu_rol] ?? 'Desconocido' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $usu->usu_estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $usu->usu_estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('usuarios.edit', $usu) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($usu->usu_id !== auth()->id())
                                <form action="{{ route('usuarios.destroy', $usu) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este usuario?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No hay usuarios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $usuarios->links() }}</div>
@endsection