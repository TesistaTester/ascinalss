{{-- resources/views/publico/filiales.blade.php --}}
@extends('layouts.publico')
@section('titulo', 'Filiales - ASCINALSS')

@section('contenido')
@include('publico._nav')

{{-- resources/views/publico/filiales.blade.php --}}

<section id="filiales-hero" class="pagina-hero" data-parallax-filiales>
    <div class="filiales-hero-bg" data-parallax-bg-filiales></div>
    <div class="filiales-hero-overlay"></div>
    <div id="mapaBolivia"
         class="mapa-fondo-pagina"
         data-src="{{ asset('img/mapa-bolivia.svg') }}">
    </div>
    <div class="pagina-hero-content">
        <p class="eyebrow">Filiales</p>
        <h1 class="split-title">Presencia a nivel nacional</h1>
        <p class="pagina-hero-sub">{{ $filiales->count() }} filiales a lo largo de todo el país, listas para atenderte.</p>
    </div>
</section>

<section id="filiales-lista">
    <div class="wrap">
        <div class="filiales-grid-cards">
            @foreach($filiales as $fil)
                <div class="filial-card" reveal>
                    <div class="filial-card-imagen">
                        @if($fil->fil_imagen)
                            <img src="{{ Storage::url($fil->fil_imagen) }}" alt="{{ $fil->fil_nombre }}">
                        @else
                            <div class="filial-card-placeholder">
                                <i class="fa-solid fa-building-flag"></i>
                            </div>
                        @endif
                    </div>
                    <div class="filial-card-datos">
                        <h3>{{ $fil->fil_nombre }}</h3>
                        @if($fil->fil_ciudad)
                            <span class="filial-card-ciudad"><i class="fa-solid fa-location-dot"></i> {{ $fil->fil_ciudad }}</span>
                        @endif
                        @if($fil->fil_direccion)
                            <p class="filial-card-direccion">{{ $fil->fil_direccion }}</p>
                        @endif
                        @if($fil->fil_telefono)
                            <a href="https://wa.me/591{{ preg_replace('/\D/', '', $fil->fil_telefono) }}" target="_blank" class="filial-card-telefono">
                                <i class="fa-brands fa-whatsapp"></i> {{ $fil->fil_telefono }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<footer>© {{ date('Y') }} ASCINALSS — Todos los derechos reservados</footer>
@endsection