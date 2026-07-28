{{-- resources/views/admin/convenios/_form.blade.php --}}
@php $conv = $convenio ?? null; @endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Título</label>
        <input type="text" name="conv_titulo" class="form-control"
               value="{{ old('conv_titulo', $conv->conv_titulo ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Orden</label>
        <input type="number" name="conv_orden" class="form-control"
               value="{{ old('conv_orden', $conv->conv_orden ?? 0) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Empresa/Entidad</label>
        <input type="text" name="conv_empresa" class="form-control"
               value="{{ old('conv_empresa', $conv->conv_empresa ?? '') }}">
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" name="conv_estado" value="1" class="form-check-input" id="conv_estado"
                   @checked(old('conv_estado', $conv->conv_estado ?? true))>
            <label class="form-check-label" for="conv_estado">Activo (visible en el sitio público)</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea name="conv_descripcion" rows="4" class="form-control">{{ old('conv_descripcion', $conv->conv_descripcion ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Logo (opcional)</label>
        <input type="file" name="conv_logo" class="form-control" accept="image/*">
        @if($conv?->conv_logo)
            <img src="{{ Storage::url($conv->conv_logo) }}" class="img-thumbnail mt-2" style="max-height: 100px;">
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">PDF del convenio (opcional)</label>
        <input type="file" name="conv_pdf_archivo" class="form-control" accept="application/pdf">
        @if($conv?->conv_pdf_archivo)
            <a href="{{ Storage::url($conv->conv_pdf_archivo) }}" target="_blank" class="d-inline-block mt-2 small">
                <i class="bi bi-file-earmark-pdf"></i> Ver PDF actual
            </a>
        @endif
    </div>
</div>