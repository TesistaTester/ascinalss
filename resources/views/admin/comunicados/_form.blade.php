{{-- resources/views/admin/comunicados/_form.blade.php --}}
@php
    $com = $comunicado ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Título</label>
        <input type="text" name="com_titulo" class="form-control"
               value="{{ old('com_titulo', $com->com_titulo ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Tipo</label>
        <select name="com_tipo" class="form-select" id="com_tipo" required>
            @foreach(['normal' => 'Normal', 'modal' => 'Modal (ventana emergente)', 'destacado' => 'Destacado', 'novedad' => 'Novedad (video/presentación)'] as $valor => $etiqueta)
                <option value="{{ $valor }}" @selected(old('com_tipo', $com->com_tipo ?? '') === $valor)>
                    {{ $etiqueta }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Contenido</label>
        <textarea name="com_contenido" rows="6" class="form-control" required>{{ old('com_contenido', $com->com_contenido ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Fecha de publicación</label>
        <input type="date" name="com_fecha_publicacion" class="form-control"
               value="{{ old('com_fecha_publicacion', isset($com) ? $com->com_fecha_publicacion->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Fecha de expiración (opcional)</label>
        <input type="date" name="com_fecha_expiracion" class="form-control"
               value="{{ old('com_fecha_expiracion', $com?->com_fecha_expiracion?->format('Y-m-d')) }}">
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" name="com_fijado" value="1" class="form-check-input" id="com_fijado"
                   @checked(old('com_fijado', $com->com_fijado ?? false))>
            <label class="form-check-label" for="com_fijado">Fijar arriba de la lista</label>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Imagen (opcional)</label>
        <input type="file" name="com_imagen" class="form-control" accept="image/*">
        @if($com?->com_imagen)
            <img src="{{ Storage::url($com->com_imagen) }}" class="img-thumbnail mt-2" style="max-height: 100px;">
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">PDF adjunto (opcional)</label>
        <input type="file" name="com_pdf_archivo" class="form-control" accept="application/pdf">
        @if($com?->com_pdf_archivo)
            <a href="{{ Storage::url($com->com_pdf_archivo) }}" target="_blank" class="d-inline-block mt-2 small">
                <i class="bi bi-file-earmark-pdf"></i> Ver PDF actual
            </a>
        @endif
    </div>

    {{-- Campos exclusivos de "Novedad" --}}
    <div class="col-12">
        <hr>
        <div class="alert alert-info small mb-3">
            <i class="bi bi-info-circle"></i> Los siguientes campos solo aplican si el tipo es <strong>Novedad</strong>.
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">URL del video (YouTube, Vimeo, etc.)</label>
        <input type="url" name="com_video_url" class="form-control"
               value="{{ old('com_video_url', $com->com_video_url ?? '') }}"
               placeholder="https://www.youtube.com/watch?v=...">
        @if($com?->com_video_url)
            <a href="{{ $com->com_video_url }}" target="_blank" class="d-inline-block mt-2 small">
                <i class="bi bi-play-circle"></i> Ver video actual
            </a>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">
            Presentación PPTx (opcional)
            @if($com?->com_pptx_archivo) <span class="text-muted small">(deja en blanco para mantener la actual)</span> @endif
        </label>
        <input type="file" name="com_pptx_archivo" class="form-control" accept=".ppt,.pptx">
        @if($com?->com_pptx_archivo)
            <a href="{{ Storage::url($com->com_pptx_archivo) }}" target="_blank" class="d-inline-block mt-2 small">
                <i class="bi bi-file-earmark-ppt"></i> Descargar PPTx actual
            </a>
        @endif
    </div>
</div>