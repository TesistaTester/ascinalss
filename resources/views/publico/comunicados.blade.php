{{-- resources/views/publico/comunicados.blade.php --}}
@extends('layouts.publico')
@section('titulo', 'Comunicados - ASCINALSS')

@section('contenido')
@include('publico._nav')

<section id="comunicados-hero" class="pagina-hero">
    <div class="comunicados-hero-bg" data-parallax-bg></div>
    <div class="comunicados-hero-overlay"></div>
    <div class="pagina-hero-content">
        <p class="eyebrow">Comunicados</p>
        <h1 class="split-title">Mantente informado</h1>
        <p class="pagina-hero-sub">Resoluciones, convocatorias, novedades y avisos oficiales de ASCINALSS.</p>
    </div>
</section>

<section id="comunicados-lista">
    <div class="wrap">
        <div class="comunicados-grid">
            @forelse($comunicados as $com)
                <div class="com-card"
                     reveal
                     data-comunicado
                     data-titulo="{{ $com->com_titulo }}"
                     data-tipo="{{ $com->com_tipo }}"
                     data-fecha="{{ $com->com_fecha_publicacion->format('d/m/Y') }}"
                     data-contenido="{{ \Illuminate\Support\Str::limit(strip_tags($com->com_contenido), 600) }}"
                     data-imagen="{{ $com->com_imagen ? Storage::url($com->com_imagen) : '' }}"
                     data-pdf="{{ $com->com_pdf_archivo ? Storage::url($com->com_pdf_archivo) : '' }}"
                     data-video="{{ $com->com_video_url ?? '' }}"
                     data-pptx="{{ $com->com_pptx_archivo ? Storage::url($com->com_pptx_archivo) : '' }}">

                    <div class="com-card-imagen">
                        @if($com->com_imagen)
                            <img src="{{ Storage::url($com->com_imagen) }}" alt="{{ $com->com_titulo }}">
                        @else
                            <div class="com-card-placeholder">
                                @php
                                    $iconos = ['normal'=>'fa-bullhorn','destacado'=>'fa-star','novedad'=>'fa-video','modal'=>'fa-bell'];
                                @endphp
                                <i class="fa-solid {{ $iconos[$com->com_tipo] ?? 'fa-bullhorn' }}"></i>
                            </div>
                        @endif
                        @if($com->com_fijado)
                            <span class="com-badge-fijado"><i class="fa-solid fa-thumbtack"></i></span>
                        @endif
                    </div>

                    <div class="com-card-cuerpo">
                        <div class="com-card-meta">
                            @php
                                $etiquetas = ['normal'=>'Comunicado','destacado'=>'Destacado','novedad'=>'Novedad','modal'=>'Aviso'];
                                $colores = ['normal'=>'','destacado'=>'com-tag-destacado','novedad'=>'com-tag-novedad','modal'=>'com-tag-aviso'];
                            @endphp
                            <span class="com-tag {{ $colores[$com->com_tipo] ?? '' }}">{{ $etiquetas[$com->com_tipo] ?? 'Comunicado' }}</span>
                            <span class="com-fecha">{{ $com->com_fecha_publicacion->format('d M Y') }}</span>
                        </div>
                        <h3>{{ $com->com_titulo }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($com->com_contenido), 100) }}</p>
                        <span class="com-leer-mas">Leer más <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </div>
            @empty
                <p style="color:rgba(255,255,255,.5); text-align:center; grid-column: 1/-1;">
                    No hay comunicados publicados por el momento.
                </p>
            @endforelse
        </div>

        <div class="paginacion">
            {{ $comunicados->links() }}
        </div>
    </div>
</section>

{{-- Modal de detalle de comunicado --}}
<div class="modal-overlay" id="modalComunicado">
    <div class="modal-box modal-comunicado-detalle">
        <button class="cerrar" id="cerrarModalComunicado"><i class="fa-solid fa-xmark"></i></button>

        <div class="mcd-imagen-wrap" id="mcdImagenWrap">
            <img id="mcdImagen" src="" alt="">
        </div>

        <div class="mcd-cuerpo">
            <div class="com-card-meta">
                <span class="com-tag" id="mcdTag"></span>
                <span class="com-fecha" id="mcdFecha"></span>
            </div>
            <h2 id="mcdTitulo"></h2>
            <p id="mcdContenido"></p>

            <div class="mcd-adjuntos" id="mcdAdjuntos"></div>
        </div>
    </div>
</div>

<footer>© {{ date('Y') }} ASCINALSS — Todos los derechos reservados</footer>
@endsection