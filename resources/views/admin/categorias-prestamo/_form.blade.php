{{-- resources/views/admin/categorias-prestamo/_form.blade.php --}}
@php $cat = $categoria ?? null; @endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Nombre de la categoría</label>
        <input type="text" name="cat_nombre" class="form-control"
               value="{{ old('cat_nombre', $cat->cat_nombre ?? '') }}" required
               placeholder="Ej. Préstamos de Emergencia">
    </div>

    <div class="col-md-4">
        <label class="form-label">Orden</label>
        <input type="number" name="cat_orden" class="form-control"
               value="{{ old('cat_orden', $cat->cat_orden ?? 0) }}">
    </div>

    <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea name="cat_descripcion" rows="3" class="form-control">{{ old('cat_descripcion', $cat->cat_descripcion ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Ícono (opcional)</label>
        <input type="text" name="cat_icono" class="form-control"
               value="{{ old('cat_icono', $cat->cat_icono ?? '') }}"
               placeholder="Ej. transferencia-movil">
        <small class="text-muted">Nombre del ícono a usar en el frontend público (según tu librería de íconos).</small>
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" name="cat_estado" value="1" class="form-check-input" id="cat_estado"
                   @checked(old('cat_estado', $cat->cat_estado ?? true))>
            <label class="form-check-label" for="cat_estado">Activo (visible en el sitio público)</label>
        </div>
    </div>
</div>