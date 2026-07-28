{{-- resources/views/publico/_nav.blade.php --}}
<nav class="nav">
    <a href="{{ route('publico.inicio') }}" class="logo">
        <img src="{{ asset('img/logo-top-menu.png') }}" alt="ASCINALSS">
    </a>
    <ul>
        <li><a href="{{ route('publico.inicio') }}#servicios"><span>Servicios</span></a></li>
        <li><a href="{{ route('publico.inicio') }}#convenios"><span>Convenios</span></a></li>
        <li><a href="{{ route('publico.filiales') }}"><span>Filiales</span></a></li>
        <li><a href="{{ route('publico.comunicados') }}"><span>Comunicados</span></a></li>
        <li><a href="{{ route('publico.informe-anual') }}"><span>Informe Anual</span></a></li>
        <li><a href="{{ route('publico.inicio') }}#contacto"><span>Contacto</span></a></li>
        <li>
            <a href="https://registro.ascinalss.org" target="_blank" class="intranet-btn">
                <span>Intranet <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px;"></i></span>
            </a>
        </li>
    </ul>
    <button class="burger" id="burger"><i class="fa-solid fa-bars"></i></button>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <a href="{{ route('publico.inicio') }}#servicios">Servicios</a>
    <a href="{{ route('publico.inicio') }}#convenios">Convenios</a>
    <a href="{{ route('publico.filiales') }}">Filiales</a>
    <a href="{{ route('publico.comunicados') }}">Comunicados</a>
    <a href="{{ route('publico.informe-anual') }}">Informe Anual</a>
    <a href="{{ route('publico.inicio') }}#contacto">Contacto</a>
    <a href="https://registro.ascinalss.org" target="_blank" class="intranet-btn">Intranet</a>
</div>


