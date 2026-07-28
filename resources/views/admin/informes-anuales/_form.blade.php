{{-- resources/views/admin/informes-anuales/_form.blade.php --}}
@php $inf = $informe ?? null; @endphp

<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Año</label>
        <input type="number" name="inf_anio" class="form-control" min="2000" max="2100"
               value="{{ old('inf_anio', $inf->inf_anio ?? now()->year) }}" required>
    </div>

    <div class="col-md-9">
        <label class="form-label">Título</label>
        <input type="text" name="inf_titulo" class="form-control"
               value="{{ old('inf_titulo', $inf->inf_titulo ?? '') }}" required
               placeholder='Ej. Revista Gestión 2023-2025 "ASCINALSS"'>
    </div>

    <div class="col-md-6">
        <label class="form-label">Fecha de publicación (opcional)</label>
        <input type="date" name="inf_fecha_publicacion" class="form-control"
               value="{{ old('inf_fecha_publicacion', $inf?->inf_fecha_publicacion?->format('Y-m-d')) }}">
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" name="inf_estado" value="1" class="form-check-input" id="inf_estado"
                   @checked(old('inf_estado', $inf->inf_estado ?? true))>
            <label class="form-check-label" for="inf_estado">Activo (visible en el sitio público)</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label">
            Archivo PDF
            @if($inf) <span class="text-muted small">(deja en blanco para mantener el actual)</span> @endif
        </label>
        <input type="file" name="inf_pdf_archivo" class="form-control" accept="application/pdf"
               {{ $inf ? '' : 'required' }}>
        @if($inf?->inf_pdf_archivo)
            <a href="{{ Storage::url($inf->inf_pdf_archivo) }}" target="_blank" class="d-inline-block mt-2 small">
                <i class="bi bi-file-earmark-pdf"></i> Ver PDF actual
            </a>
        @endif
    </div>
</div>