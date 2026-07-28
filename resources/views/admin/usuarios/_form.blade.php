{{-- resources/views/admin/usuarios/_form.blade.php --}}
@php $usu = $usuario ?? null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nombre de usuario (para login)</label>
        <input type="text" name="usu_nombre" class="form-control"
               value="{{ old('usu_nombre', $usu->usu_nombre ?? '') }}" required
               {{ $usu && $usu->usu_id === auth()->id() ? '' : '' }}>
    </div>

    <div class="col-md-6">
        <label class="form-label">Nombre completo</label>
        <input type="text" name="usu_nombre_completo" class="form-control"
               value="{{ old('usu_nombre_completo', $usu->usu_nombre_completo ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">
            Contraseña
            @if($usu) <span class="text-muted small">(deja en blanco para mantener la actual)</span> @endif
        </label>
        <input type="password" name="password" class="form-control" {{ $usu ? '' : 'required' }} minlength="6">
    </div>

    <div class="col-md-3">
        <label class="form-label">Rol</label>
        <select name="usu_rol" class="form-select" required>
            <option value="1" @selected(old('usu_rol', $usu->usu_rol ?? '') == 1)>Admin</option>
            <option value="2" @selected(old('usu_rol', $usu->usu_rol ?? '') == 2)>Editor</option>
            <option value="3" @selected(old('usu_rol', $usu->usu_rol ?? '') == 3)>Directorio</option>
        </select>
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" name="usu_estado" value="1" class="form-check-input" id="usu_estado"
                   @checked(old('usu_estado', $usu->usu_estado ?? true))>
            <label class="form-check-label" for="usu_estado">Activo</label>
        </div>
    </div>
</div>