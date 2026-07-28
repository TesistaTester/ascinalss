{{-- resources/views/publico/index.blade.php --}}
@extends('layouts.publico')

@section('titulo', 'ASCINALSS - Asociación Nacional de Suboficiales y Sargentos')

@section('contenido')

@include('publico._nav')

<section id="hero">
    <div class="hero-bg-img" data-hero-parallax></div>
    <div class="hero-overlay"></div>

    <div class="content">
        <img src="{{ asset('img/logo.png') }}" alt="ASCINALSS" class="hero-logo">
        <p class="hero-slogan">¡TRABAJAMOS POR UN FUTURO MEJOR!</p>

        <div class="logos-institucionales">
            <img src="{{ asset('img/ejercito.png') }}" alt="Ejército de Bolivia" class="logo-institucional">
            <img src="{{ asset('img/fab.png') }}" alt="Fuerza Aérea Boliviana" class="logo-institucional">
            <img src="{{ asset('img/armada.png') }}" alt="Armada Boliviana" class="logo-institucional">
        </div>
    </div>

    <div class="scroll-cue"><span>Desliza</span><i class="fa-solid fa-chevron-down" id="chevron"></i></div>
</section>

<section id="servicios">
    <div class="wrap">
        <div class="section-head">
            <p class="eyebrow">Servicios</p>
            <h2 class="split-title">Espacios para nuestros socios</h2>
        </div>
        <div class="cards">
            @foreach($servicios as $ser)
                <div class="card card-clickable" reveal
                    data-servicio
                    data-titulo="{{ $ser->ser_titulo }}"
                    data-descripcion="{{ $ser->ser_descripcion }}"
                    data-direccion="{{ $ser->ser_direccion ?? '' }}"
                    data-telefono="{{ $ser->ser_telefono_whatsapp ?? '' }}"
                    data-capacidad="{{ $ser->ser_capacidad ?? '' }}"
                    data-imagen="{{ $ser->ser_imagen ? Storage::url($ser->ser_imagen) : '' }}">
                    @if($ser->ser_imagen)
                        <img src="{{ Storage::url($ser->ser_imagen) }}" alt="{{ $ser->ser_titulo }}">
                    @else
                        <i class="fa-solid fa-building"></i>
                    @endif
                    <h3>{{ $ser->ser_titulo }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($ser->ser_descripcion, 110) }}</p>
                </div>
            @endforeach
        </div>        
    </div>
</section>


<section id="apoyo">
    <div class="apoyo-bg-img" data-hero-parallax-apoyo></div>
    <div class="apoyo-overlay"></div>
    <i class="fa-solid fa-sack-dollar icon-float" data-speed="0.35" style="top:15%;"></i>

    <div class="wrap">
        <div class="section-head">
            <p class="eyebrow">Apoyo Económico</p>
            <h2 class="split-title">Préstamos pensados para ti</h2>
        </div>

        <div class="cards cards-prestamo">
            @foreach($categoriasPrestamo as $cat)
                <div class="card card-clickable"
                     reveal
                     data-prestamo
                     data-nombre="{{ $cat->cat_nombre }}"
                     data-descripcion="{{ $cat->cat_descripcion }}"
                     data-icono="{{ $cat->cat_icono ?? 'fa-file-lines' }}"
                     data-documentos="{{ $cat->documentos->map(fn($doc) => [
                         'etiqueta' => $doc->doc_etiqueta,
                         'tipo' => $doc->doc_tipo,
                         'url' => Storage::url($doc->doc_pdf_archivo),
                     ])->toJson() }}">
                    <i class="fa-solid {{ $cat->cat_icono ?? 'fa-file-lines' }}"></i>
                    <h3>{{ $cat->cat_nombre }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($cat->cat_descripcion, 90) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="cta">
    <div class="cta-grid">
        <div class="cta-col-contenido">
            <div class="section-head">
                <div class="eyebrow">Filiales</div>
                <h2 class="split-title">Presencia a nivel nacional</h2>
                <p reveal>Encuentra la filial más cercana y descubre todo lo que ASCINALSS tiene para ti y tu familia.</p>
                <a href="{{ route('publico.filiales') }}" class="btn" reveal>Ver todas las filiales</a>
            </div>
        </div>
        <div class="cta-col-mapa" reveal>
            <div class="mapa-bolivia-wrap">
                <div id="mapaBolivia" class="mapa-bolivia" data-src="{{ asset('img/mapa-bolivia.svg') }}"></div>
            </div>
        </div>

    </div>
</section>

{{-- resources/views/publico/index.blade.php --}}

<section id="convenios">
    <div class="convenios-bg-img" data-hero-parallax-convenios></div>
    <div class="convenios-overlay"></div>

    <div class="wrap">
        <div class="section-head">
            <p class="eyebrow">Convenios</p>
            <h2 class="split-title">Aliados que suman beneficios</h2>
        </div>

        <div class="convenios-carousel" id="conveniosCarousel">
            <div class="convenios-track" id="conveniosTrack">
                @foreach($convenios as $conv)
                    <div class="convenio-card">
                        <div class="convenio-logo-wrap">
                            @if($conv->conv_logo)
                                <img src="{{ Storage::url($conv->conv_logo) }}" alt="{{ $conv->conv_empresa }}">
                            @else
                                <i class="fa-solid fa-handshake"></i>
                            @endif
                        </div>
                        <h3>{{ $conv->conv_empresa ?? $conv->conv_titulo }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($conv->conv_descripcion, 180) }}</p>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>

<section id="contacto">
    <div class="contacto-grid">
        <div class="contacto-col-texto">
            <p class="eyebrow">Contacto</p>
            <h2 class="split-title">Estamos para ayudarte</h2>
            <p class="contacto-intro" reveal>
                Nuestro equipo está disponible para resolver tus consultas sobre
                préstamos, servicios y beneficios. Elige el área correcta y comunícate directamente.
            </p>
            @if($contacto['facebook'])
                <a href="{{ $contacto['facebook'] }}" target="_blank" class="btn" reveal>
                    <i class="fa-brands fa-facebook"></i> Síguenos en Facebook
                </a>
            @endif
        </div>

        <div class="contacto-col-lista">
            <div class="contacto-fila" reveal>
                <i class="fa-solid fa-location-dot"></i>
                <div class="contacto-fila-texto">
                    <span class="contacto-label">Oficina Central</span>
                    <span class="contacto-valor">{{ $contacto['direccion'] }}</span>
                </div>
            </div>
            <div class="contacto-fila" reveal>
                <i class="fa-solid fa-phone"></i>
                <div class="contacto-fila-texto">
                    <span class="contacto-label">Central telefónica</span>
                    <span class="contacto-valor">{{ $contacto['telefono_central'] }}</span>
                </div>
            </div>
            <div class="contacto-fila" reveal>
                <i class="fa-solid fa-hand-holding-dollar"></i>
                <div class="contacto-fila-texto">
                    <span class="contacto-label">Préstamos</span>
                    <span class="contacto-valor">{{ $contacto['telefono_prestamos'] }}</span>
                </div>
            </div>
            <div class="contacto-fila" reveal>
                <i class="fa-solid fa-cash-register"></i>
                <div class="contacto-fila-texto">
                    <span class="contacto-label">Cobranzas</span>
                    <span class="contacto-valor">{{ $contacto['telefono_cobranza'] }}</span>
                </div>
            </div>
            <div class="contacto-fila" reveal>
                <i class="fa-solid fa-piggy-bank"></i>
                <div class="contacto-fila-texto">
                    <span class="contacto-label">D.A.A.R.O.</span>
                    <span class="contacto-valor">{{ $contacto['telefono_daaro'] }}</span>
                </div>
            </div>
            <div class="contacto-fila" reveal>
                <i class="fa-solid fa-vault"></i>
                <div class="contacto-fila-texto">
                    <span class="contacto-label">Tesorería</span>
                    <span class="contacto-valor">{{ $contacto['telefono_tesoreria'] }}</span>
                </div>
            </div>
            <div class="contacto-fila" reveal>
                <i class="fa-brands fa-whatsapp"></i>
                <div class="contacto-fila-texto">
                    <span class="contacto-label">WhatsApp</span>
                    <span class="contacto-valor">{{ $contacto['whatsapp'] }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<footer>© {{ date('Y') }} ASCINALSS — Todos los derechos reservados</footer>


{{-- Modal de detalle de servicio --}}
<div class="modal-overlay modal-servicio-overlay" id="modalServicio">
    <div class="modal-box modal-servicio">
        <button class="cerrar" id="cerrarModalServicio"><i class="fa-solid fa-xmark"></i></button>
        <div class="servicio-col-imagen">
            <img id="msImagen" src="" alt="">
        </div>
        <div class="servicio-col-datos">
            <p class="servicio-eyebrow">Servicio institucional</p>
            <h3 id="msTitulo"></h3>
            <p id="msDescripcion" class="servicio-descripcion"></p>
            <div class="servicio-detalles">
                <div class="servicio-item" id="msItemDireccion">
                    <i class="fa-solid fa-location-dot"></i>
                    <span id="msDireccion"></span>
                </div>
                <div class="servicio-item" id="msItemTelefono">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span id="msTelefono"></span>
                </div>
                <div class="servicio-item" id="msItemCapacidad">
                    <i class="fa-solid fa-users"></i>
                    <span id="msCapacidad"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modalPrestamo">
    <div class="modal-box modal-servicio modal-prestamo">
        <button class="cerrar" id="cerrarModalPrestamo"><i class="fa-solid fa-xmark"></i></button>
        <div class="prestamo-col-icono">
            <div class="prestamo-icono-circulo">
                <i id="mpIcono" class="fa-solid"></i>
            </div>
        </div>
        <div class="servicio-col-datos">
            <p class="servicio-eyebrow">Apoyo Económico</p>
            <h3 id="mpTitulo"></h3>
            <p id="mpDescripcion" class="servicio-descripcion"></p>
            <div class="servicio-detalles" id="mpDocumentos">
                {{-- Se llena dinámicamente vía JS --}}
            </div>
        </div>
    </div>
</div>

{{-- Modales de comunicados tipo "modal", vigentes --}}
@foreach($comunicadosModales as $modal)
    <div class="modal-overlay modal-comunicado" id="modal-{{ $modal->com_id }}">
        <div class="modal-box">
            <button class="cerrar" data-cerrar="{{ $modal->com_id }}"><i class="fa-solid fa-xmark"></i></button>
            @if($modal->com_imagen)
                <img src="{{ Storage::url($modal->com_imagen) }}" alt="{{ $modal->com_titulo }}">
            @endif
            <h3>{{ $modal->com_titulo }}</h3>
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($modal->com_contenido), 200) }}</p>
            @if($modal->com_pdf_archivo)
                <a href="{{ Storage::url($modal->com_pdf_archivo) }}" target="_blank" class="ver-pdf">
                    <i class="fa-solid fa-file-pdf"></i> Ver documento completo
                </a>
            @endif
        </div>
    </div>
@endforeach

@endsection