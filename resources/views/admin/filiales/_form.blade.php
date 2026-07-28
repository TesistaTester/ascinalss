{{-- resources/views/admin/filiales/_form.blade.php --}}
@php $fil = $filial ?? null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nombre de la Filial</label>
        <input type="text" name="fil_nombre" class="form-control"
               value="{{ old('fil_nombre', $fil->fil_nombre ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Ciudad</label>
        <input type="text" name="fil_ciudad" class="form-control"
               value="{{ old('fil_ciudad', $fil->fil_ciudad ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Orden</label>
        <input type="number" name="fil_orden" class="form-control"
               value="{{ old('fil_orden', $fil->fil_orden ?? 0) }}">
    </div>

    <div class="col-md-8">
        <label class="form-label">Dirección</label>
        <input type="text" name="fil_direccion" class="form-control"
               value="{{ old('fil_direccion', $fil->fil_direccion ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Teléfono / Celular</label>
        <input type="text" name="fil_telefono" class="form-control"
               value="{{ old('fil_telefono', $fil->fil_telefono ?? '') }}">
    </div>

    <div class="col-12">
        <div class="form-check">
            <input type="checkbox" name="fil_estado" value="1" class="form-check-input" id="fil_estado"
                   @checked(old('fil_estado', $fil->fil_estado ?? true))>
            <label class="form-check-label" for="fil_estado">Activo (visible en el sitio público)</label>
        </div>
    </div>
</div>