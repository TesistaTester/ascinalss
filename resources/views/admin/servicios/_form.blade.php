{{-- resources/views/admin/servicios/_form.blade.php --}}
@php $ser = $servicio ?? null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Título</label>
        <input type="text" name="ser_titulo" class="form-control" value="{{ old('ser_titulo', $ser->ser_titulo ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Capacidad (personas)</label>
        <input type="number" name="ser_capacidad" class="form-control" value="{{ old('ser_capacidad', $ser->ser_capacidad ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Orden</label>
        <input type="number" name="ser_orden" class="form-control" value="{{ old('ser_orden', $ser->ser_orden ?? 0) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea name="ser_descripcion" rows="3" class="form-control">{{ old('ser_descripcion', $ser->ser_descripcion ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Dirección</label>
        <input type="text" name="ser_direccion" class="form-control" value="{{ old('ser_direccion', $ser->ser_direccion ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">WhatsApp / Celular</label>
        <input type="text" name="ser_telefono_whatsapp" class="form-control" value="{{ old('ser_telefono_whatsapp', $ser->ser_telefono_whatsapp ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Imagen</label>
        <input type="file" name="ser_imagen" class="form-control" accept="image/*">
        @if($ser?->ser_imagen)
            <img src="{{ Storage::url($ser->ser_imagen) }}" class="img-thumbnail mt-2" style="max-height:100px;">
        @endif
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" name="ser_estado" value="1" class="form-check-input" id="ser_estado" @checked(old('ser_estado', $ser->ser_estado ?? true))>
            <label class="form-check-label" for="ser_estado">Activo (visible en el sitio público)</label>
        </div>
    </div>
</div>