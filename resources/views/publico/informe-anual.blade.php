{{-- resources/views/publico/informe-anual.blade.php --}}
@extends('layouts.publico')
@section('titulo', 'Informe Anual - ASCINALSS')

@section('contenido')
@include('publico._nav')

<section id="informe-hero" class="pagina-hero">
    <div class="informe-hero-bg" data-parallax-bg-informes></div>
    <div class="informe-hero-overlay"></div>
    <div class="pagina-hero-content">
        <p class="eyebrow">Transparencia institucional</p>
        <h1 class="split-title">Informe Anual</h1>
        <p class="pagina-hero-sub">Consulta nuestra gestión, resultados y actividades por año.</p>
    </div>
</section>

<section id="informe-lista">
    <div class="wrap">
        @forelse($informes as $inf)
            <div class="informe-row" reveal>
                <div class="informe-row-anio">{{ $inf->inf_anio }}</div>
                <div class="informe-row-info">
                    <h3>{{ $inf->inf_titulo }}</h3>
                    @if($inf->inf_fecha_publicacion)
                        <span class="informe-row-fecha">
                            <i class="fa-regular fa-calendar"></i>
                            Publicado el {{ $inf->inf_fecha_publicacion->format('d \d\e F \d\e Y') }}
                        </span>
                    @endif
                </div>
                <a href="{{ Storage::url($inf->inf_pdf_archivo) }}"
                   target="_blank"
                   class="informe-row-btn">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Descargar</span>
                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px; margin-left:4px;"></i>
                </a>
            </div>
        @empty
            <p style="color:rgba(255,255,255,.5); text-align:center;">
                No hay informes anuales publicados por el momento.
            </p>
        @endforelse
    </div>
</section>

<footer>© {{ date('Y') }} ASCINALSS — Todos los derechos reservados</footer>
@endsection