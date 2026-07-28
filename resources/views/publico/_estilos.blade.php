{{-- resources/views/publico/_estilos.blade.php --}}
* { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; }
body { font-family: 'Segoe UI', system-ui, sans-serif; background: #0d1420; color: #fff; overflow-x: hidden; }

.nav { position: fixed; top: 0; left: 0; right: 0; display: flex; justify-content: space-between; align-items: center; padding: 18px 48px; z-index: 200; transition: background .3s, backdrop-filter .3s; }

.nav .logo {
    z-index: 210;
    display: flex;
    align-items: center;
}

.nav .logo img {
    height: 36px;
    width: auto;
    display: block;
}

.nav ul { list-style: none; display: flex; align-items: center; gap: 28px; }

.nav a {
    color: #fff;
    text-decoration: none;
    font-size: 13px;
    letter-spacing: .5px;
    opacity: .85;
    position: relative;
    display: inline-block;
    padding: 8px 4px;
    overflow: hidden;
    transition: color 0.4s ease, opacity 0.4s ease;
    border-radius: 7px;
}

.nav a::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        0deg,
        transparent,
        transparent 30%,
        rgba(201, 161, 90, 0.35)
    );
    transform: rotate(-45deg) translateY(-100%);
    transition: transform 0.5s ease, opacity 0.5s ease;
    opacity: 0;
    pointer-events: none;
    z-index: 0;
}

.nav a span {
    position: relative;
    z-index: 1;
}

.nav a:hover {
    opacity: 1;
    color: #e8cd9a;
    text-shadow: 0 0 12px rgba(201, 161, 90, 0.6);
}

.nav a:hover::before {
    opacity: 1;
    transform: rotate(-45deg) translateY(100%);
}

.nav .intranet-btn::before {
    background: linear-gradient(
        0deg,
        transparent,
        transparent 30%,
        rgba(255, 255, 255, 0.35)
    );
}
.nav .intranet-btn:hover {
    color: #241a06;
    text-shadow: none;
    box-shadow: 0 0 16px rgba(201, 161, 90, 0.5);
}

.nav .intranet-btn { background: #c9a15a; color: #241a06; font-weight: 700; padding: 8px 18px; border-radius: 6px; opacity: 1 !important; }
.burger { display: none; z-index: 210; font-size: 22px; background: none; border: none; color: #fff; cursor: pointer; }
.mobile-menu { position: fixed; inset: 0; background: #0d1420; z-index: 190; display: none; flex-direction: column; align-items: center; justify-content: center; gap: 28px; opacity: 0; visibility: hidden; }
.mobile-menu.open { display: flex; }
.mobile-menu a { color: #fff; text-decoration: none; font-size: 22px; letter-spacing: 1px; opacity: 0; transform: translateY(20px); }
.mobile-menu .intranet-btn { background: #c9a15a; color: #241a06; font-weight: 700; padding: 12px 28px; border-radius: 8px; font-size: 16px; }

section { position: relative; min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 100px 24px; }

.split-char { display: inline-block; will-change: transform, opacity; }
.split-word { display: inline-block; white-space: nowrap; }

#hero { background: linear-gradient(180deg, #0d1420 0%, #16213a 100%); }
#hero .bg-shield { position: absolute; font-size: min(60vw, 500px); color: rgba(255,255,255,0.03); z-index: 0; }
.bg-particles { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
.particle { position: absolute; width: 3px; height: 3px; background: #c9a15a; border-radius: 50%; }
#hero .content { position: relative; z-index: 2; text-align: center; max-width: 90vw; }
.eyebrow { font-size: clamp(13px,1.5vw,15px); letter-spacing: 3px; color: #c9a15a; margin-bottom: 14px; text-transform: uppercase; }
#hero h1 { font-size: clamp(40px, 10vw, 100px); font-weight: 800; letter-spacing: 2px; line-height: 1; }
#hero .sub { margin-top: 20px; font-size: clamp(14px,2vw,16px); color: rgba(255,255,255,.6); max-width: 520px; margin-left: auto; margin-right: auto; line-height: 1.6; }
.scroll-cue { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; flex-direction: column; align-items: center; gap: 8px; z-index: 2; color: rgba(255,255,255,.5); font-size: 11px; letter-spacing: 2px; text-transform: uppercase; }

#hero { background: #0d1420; }

.hero-bg-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 135%;
    background-image: url('{{ asset('img/hero-ascinalss.png') }}');
    background-size: cover;
    background-position: center top;
    z-index: 0;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(13,20,32,0.35) 0%, rgba(13,20,32,0.7) 100%);
    z-index: 1;
}

.hero-logo {
    display: block;
    margin: 0 auto 24px;
    width: 80vw;
    max-width: 800px;   /* evita que en monitores ultra anchos quede descomunal */
    min-width: 400px;   /* evita que en celulares muy chicos quede minúsculo */
    height: auto;
    opacity: 0;
    transform: scale(0.85);
}

.hero-slogan {
    font-size: clamp(15px, 2.2vw, 19px);
    color: #e8cd9a;
    letter-spacing: 1px;
    margin: 0 0 28px;
    font-style: italic;
    opacity: 0;
    transform: translateY(15px);
}

.logos-institucionales {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 40px;
    flex-wrap: wrap;
    margin-top: 32px;
}

.logo-institucional {
    width: 100px;
    height: auto;
    opacity: 0;
    transform: translateY(15px);
}

@media (max-width: 480px) {
    .hero-logo {
        width: 60vw;     /* en móvil, un poco más grande proporcionalmente */
        min-width: 160px;
    }
    .logos-institucionales { gap: 24px; }
    .logo-institucional { width: 64px; }
}

#hero .bg-shield { z-index: 1; opacity: 0.5; }
#hero .bg-particles { z-index: 2; }
#hero .content { z-index: 3; }
.scroll-cue { z-index: 3; }


#servicios { background: #12192b; }

#apoyo { background: #0d1420; }

.apoyo-bg-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 140%;
    background-image: url('{{ asset('img/prestamos.jpg') }}');
    background-size: cover;
    background-position: center;
    z-index: 0;
}

.apoyo-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(13,20,32,0.75) 0%, rgba(13,20,32,0.92) 100%);
    z-index: 1;
}

#apoyo .icon-float,
#apoyo .wrap { z-index: 2; }

/* resources/views/publico/_estilos.blade.php */

#convenios {
    min-height: auto;
    padding: 100px 24px;
    position: relative;
    overflow: hidden;
}

.convenios-bg-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 140%;
    background-image: url('{{ asset('img/convenios.avif') }}');
    background-size: cover;
    background-position: center;
    z-index: 0;
}

.convenios-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(18,25,43,0.85) 0%, rgba(18,25,43,0.95) 100%);
    z-index: 1;
}

#convenios .wrap { z-index: 2; }

.convenios-carousel {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 900px;
    height: 400px;   /* antes: 340px */
    perspective: 1400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Desktop: mas espacio para que el carrusel respire */
@media (min-width: 1024px) {
    .convenios-carousel {
        max-width: 950px;
        height: 410px; 
    }    
}

#filiales { background: #0d1420; }
#comunicados { background: #12192b; }
#informe { background: #0d1420; min-height: auto; padding: 90px 24px; }

.icon-float { position: absolute; font-size: clamp(140px,25vw,240px); color: rgba(255,255,255,0.04); z-index: 0; }
.section-head { text-align: center; margin-bottom: 48px; position: relative; z-index: 2; }
.section-head h2 { font-size: clamp(26px, 4vw, 42px); font-weight: 700; }

.cards { position: relative; z-index: 2; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 1000px; width: 100%; }
.card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 28px 22px; text-align: center; }
.card img { width: 100%; height: 140px; object-fit: cover; border-radius: 10px; margin-bottom: 16px; }
.card i { font-size: 30px; color: #c9a15a; margin-bottom: 14px; display: block; }
.card h3 { font-size: 16px; margin-bottom: 8px; }
.card p { font-size: 13px; color: rgba(255,255,255,.55); line-height: 1.5; }

.wrap { position: relative; z-index: 2; width: 100%; display: flex; flex-direction: column; align-items: center; }

.pill-row { position: relative; z-index: 2; display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; max-width: 700px; }
.pill { background: rgba(201,161,90,0.12); border: 1px solid rgba(201,161,90,0.3); color: #e0c088; font-size: 13px; padding: 10px 20px; border-radius: 30px; text-decoration: none; cursor: pointer; }
.pill:hover { background: rgba(201,161,90,0.22); }

.marquee { position: relative; z-index: 2; width: 100%; overflow: hidden; padding: 20px 0; border-top: 1px solid rgba(255,255,255,.08); border-bottom: 1px solid rgba(255,255,255,.08); }
.marquee-track { display: flex; gap: 50px; white-space: nowrap; width: max-content; align-items: center; }
.marquee-track span { font-size: clamp(15px,2.5vw,20px); font-weight: 600; color: rgba(255,255,255,.25); }
.marquee-track img { height: 32px; opacity: .5; filter: grayscale(1) brightness(2); }

.filial-grid { position: relative; z-index: 2; display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; max-width: 900px; width: 100%; }
.filial-chip { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 14px; text-align: center; font-size: 13px; color: rgba(255,255,255,.75); }
.filial-count { font-size: clamp(40px,7vw,64px); font-weight: 800; color: #c9a15a; text-align: center; margin-bottom: 8px; position: relative; z-index: 2; }

.comunicado-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 22px; }
.comunicado-card img { width: 100%; height: 120px; object-fit: cover; border-radius: 10px; margin-bottom: 12px; }
.comunicado-card .tag { display: inline-block; background: rgba(201,161,90,0.15); color: #e0c088; font-size: 11px; padding: 4px 10px; border-radius: 6px; margin-bottom: 10px; }
.comunicado-card h3 { font-size: 15px; margin-bottom: 8px; }
.comunicado-card p { font-size: 13px; color: rgba(255,255,255,.55); line-height: 1.5; }
.comunicado-card a.ver-pdf { display: inline-block; margin-top: 10px; font-size: 12px; color: #c9a15a; text-decoration: none; }

.informe-card { position: relative; z-index: 2; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 32px 40px; display: flex; align-items: center; gap: 24px; max-width: 500px; width: 100%; }
.informe-card i { font-size: 40px; color: #c9a15a; }
.informe-card h3 { font-size: 16px; margin-bottom: 6px; }
.informe-card p { font-size: 13px; color: rgba(255,255,255,.55); }
.informe-card .btn-descarga { margin-left: auto; background: #c9a15a; color: #241a06; font-size: 13px; font-weight: 700; padding: 10px 18px; border-radius: 8px; text-decoration: none; white-space: nowrap; }

#cta { background: linear-gradient(180deg, #0d1420 0%, #1a2332 100%); text-align: center; flex-direction: column; gap: 20px; }
#cta p { color: rgba(255,255,255,.6); max-width: 480px; position: relative; z-index: 2; }
.btn { position: relative; z-index: 2; background: #c9a15a; color: #241a06; border: none; font-size: 15px; font-weight: 700; padding: 15px 34px; border-radius: 8px; cursor: pointer; letter-spacing: .5px; text-decoration: none; display: inline-block; }

#cta {
    background: linear-gradient(180deg, #0d1420 0%, #1a2332 100%);
    text-align: left;
    padding: 100px 24px;
}

.cta-grid {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: 60px;
    max-width: 1100px;
    width: 100%;
}

.cta-col-mapa {
    display: flex;
    justify-content: center;
    align-items: center;
}

.mapa-bolivia-wrap { width: 100%; max-width: 420px; }
.mapa-bolivia { width: 100%; height: auto; opacity: 0; transform: scale(0.9); }
.mapa-bolivia svg { width: 100%; height: auto; display: block; }


.cta-col-contenido h2 { text-align: left; margin-bottom: 16px; }
.cta-col-contenido p {
    color: rgba(255,255,255,.6);
    max-width: 420px;
    margin-bottom: 28px;
    text-align: left;
}
.cta-col-contenido .btn { text-align: center; }

@media (max-width: 860px) {
    .cta-grid {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 40px;
    }
    .cta-col-contenido h2,
    .cta-col-contenido p { text-align: center; margin-left: auto; margin-right: auto; }
    .mapa-bolivia-wrap { max-width: 300px; }
}


/* --- Modal de comunicados --- */
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.7); z-index: 300; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; visibility: hidden; }
.modal-overlay.open { opacity: 1; visibility: visible; }
.modal-box { background: #12192b; border: 1px solid rgba(255,255,255,.1); border-radius: 16px; max-width: 460px; width: 100%; padding: 28px; position: relative; transform: scale(0.9); }
.modal-box .cerrar { position: absolute; top: 14px; right: 14px; background: none; border: none; color: rgba(255,255,255,.6); font-size: 18px; cursor: pointer; }
.modal-box img { width: 100%; border-radius: 10px; margin-bottom: 16px; }
.modal-box h3 { font-size: 18px; margin-bottom: 10px; }
.modal-box p { font-size: 14px; color: rgba(255,255,255,.65); line-height: 1.6; }

footer { text-align: center; padding: 28px; font-size: 12px; color: rgba(255,255,255,.3); }

@media (max-width: 860px) {
  .nav .logo img { height: 20px; }    
  .nav ul { display: none; }
  .burger { display: block; }
  .cards { grid-template-columns: 1fr; }
  .filial-grid { grid-template-columns: repeat(2, 1fr); }
  section { padding: 80px 20px; }
  .nav { padding: 16px 20px; }
  .informe-card { flex-direction: column; text-align: center; padding: 28px 20px; }
  .informe-card .btn-descarga { margin-left: 0; }
}
@media (max-width: 480px) {
  .filial-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
  .filial-chip { font-size: 12px; padding: 10px; }
}

/* 
-------------------------------------------------------
ESTILOS CARD MODAL DE SERVICIOS
-------------------------------------------------------
 */

.card-clickable {
    cursor: pointer;
    transition: transform 0.3s ease, border-color 0.3s ease;
}
.card-clickable:hover {
    transform: translateY(-4px);
    border-color: rgba(201, 161, 90, 0.4);
}

.modal-servicio {
    max-width: 780px;
    padding: 0;
    display: flex;
    overflow: hidden;
}

.modal-servicio .cerrar {
    background: rgba(13, 20, 32, 0.6);
    border-radius: 50%;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 5;
}

.servicio-col-imagen {
    flex: 0 0 42%;
    position: relative;
    min-height: 100%;
}
.servicio-col-imagen img {
    width: 100%;
    height: 100%;
    min-height: 320px;
    object-fit: cover;
    display: block;
    margin: 0;
    border-radius: 0;
}
.servicio-col-imagen::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent 60%, rgba(18, 25, 43, 0.5) 100%);
}

.servicio-col-datos {
    flex: 1;
    padding: 40px 36px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.servicio-eyebrow {
    font-size: 11px;
    letter-spacing: 2.5px;
    color: #c9a15a;
    text-transform: uppercase;
    margin-bottom: 10px;
}

.servicio-col-datos h3 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 14px;
    line-height: 1.2;
}

.servicio-descripcion {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.65);
    line-height: 1.7;
    margin-bottom: 24px;
}

.servicio-detalles {
    display: flex;
    flex-direction: column;
    gap: 14px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.servicio-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.85);
}

.servicio-item i {
    width: 20px;
    text-align: center;
    color: #c9a15a;
    font-size: 15px;
    flex-shrink: 0;
}

@media (max-width: 700px) {
    .modal-servicio {
        flex-direction: column;
        max-height: 85vh;
        overflow-y: auto;
    }
    .servicio-col-imagen img { min-height: 200px; }
    .servicio-col-datos { padding: 28px 24px; }
}

/*
----------------------------------------------------------
ESTILOS MODALS PRESTAMOS
----------------------------------------------------------
*/
.modal-prestamo { max-width: 680px; }

.prestamo-col-icono {
    flex: 0 0 38%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(201, 161, 90, 0.06);
    border-right: 1px solid rgba(255, 255, 255, 0.06);
}

.prestamo-icono-circulo {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: rgba(201, 161, 90, 0.15);
    border: 1px solid rgba(201, 161, 90, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.prestamo-icono-circulo i {
    font-size: 42px;
    color: #c9a15a;
}

/* Documentos como filas clickeables, en vez de texto estático */
.documento-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    text-decoration: none;
    color: rgba(255, 255, 255, 0.85);
    font-size: 13px;
    transition: background 0.25s ease, border-color 0.25s ease;
}
.documento-item:hover {
    background: rgba(201, 161, 90, 0.1);
    border-color: rgba(201, 161, 90, 0.35);
}
.documento-item i.doc-icono {
    color: #c9a15a;
    font-size: 16px;
    width: 20px;
    text-align: center;
    flex-shrink: 0;
}
.documento-item i.doc-descarga {
    margin-left: auto;
    color: rgba(255, 255, 255, 0.4);
    font-size: 12px;
}
.documento-item .doc-tipo-tag {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: rgba(255, 255, 255, 0.4);
    display: block;
    margin-top: 2px;
}

.documentos-vacio {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.4);
    font-style: italic;
}

@media (max-width: 700px) {
    .modal-prestamo { flex-direction: column; }
    .prestamo-col-icono {
        border-right: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        padding: 28px 0;
    }
}

/*
---------------------------------------
Estilos para contacto
---------------------------------------
*/

#contacto {
    background: #12192b;
    min-height: auto;
    padding: 100px 24px;
}

.contacto-grid {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 0.9fr 1.1fr;
    gap: 70px;
    max-width: 1050px;
    width: 100%;
    align-items: start;
}

.contacto-col-texto {
    text-align: left;
    position: sticky;
    top: 140px;
}
.contacto-col-texto .eyebrow { text-align: left; }
.contacto-col-texto h2 { text-align: left; margin-bottom: 18px; }
.contacto-intro {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.6);
    line-height: 1.8;
    max-width: 380px;
    margin-bottom: 32px;
}

.contacto-col-lista {
    display: flex;
    flex-direction: column;
}

.contacto-fila {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 22px 4px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    transition: padding-left 0.3s ease, border-color 0.3s ease;
}
.contacto-fila:first-child { padding-top: 0; }
.contacto-fila:hover {
    padding-left: 10px;
    border-color: rgba(201, 161, 90, 0.35);
}

.contacto-fila i {
    font-size: 18px;
    color: #c9a15a;
    width: 24px;
    text-align: center;
    flex-shrink: 0;
}

.contacto-fila-texto {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.contacto-label {
    font-size: 11px;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.4);
}
.contacto-valor {
    font-size: 16px;
    color: #fff;
    font-weight: 500;
}

@media (max-width: 860px) {
    .contacto-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    .contacto-col-texto {
        position: static;
        text-align: center;
    }
    .contacto-col-texto .eyebrow,
    .contacto-col-texto h2 { text-align: center; }
    .contacto-intro { margin-left: auto; margin-right: auto; text-align: center; }
}


/*
----------------------------------------------------------
ESTILOS TARJETA DE CONVENIO (carrusel 3D)
----------------------------------------------------------
*/

.convenio-card {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300px;
    min-height: 300px;      /* antes: sin altura fija, se ajustaba al contenido corto */
    padding: 36px 30px;
    background: rgba(255, 255, 255, 0.045);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    text-align: center;
    backface-visibility: hidden;
    will-change: transform, opacity;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
}

.convenio-logo-wrap {
    width: 64px;
    height: 64px;
    margin: 0 auto 20px;
    flex-shrink: 0;
    border-radius: 50%;
    background: rgba(201, 161, 90, 0.12);
    border: 1px solid rgba(201, 161, 90, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}
.convenio-logo-wrap img { max-width: 70%; max-height: 70%; object-fit: contain; }
.convenio-logo-wrap i { font-size: 24px; color: #c9a15a; }

.convenio-card h3 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 12px;
    line-height: 1.3;
}
.convenio-card p {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.6);
    line-height: 1.7;
}

@media (max-width: 700px) {
    .convenio-card { width: 240px; min-height: 280px; padding: 28px 22px; }
}

/*
##########################################################################
ESTILOS PAGINAS
##########################################################################
*/
/* resources/views/publico/_estilos.blade.php */

#filiales-hero {
    background: #0d1420;
    min-height: 90vh;
}

.filiales-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 150%;
    background-image: url('{{ asset('img/filiales.avif') }}');
    background-size: cover;
    background-position: center;
    z-index: 0;
}

.filiales-hero-overlay {
    position: absolute;
    inset: 0;
    /* Degradado más oscuro que los otros heros para que el mapa se lea bien */
    background: linear-gradient(180deg, rgba(13,20,32,0.65) 0%, rgba(13,20,32,0.92) 100%);
    z-index: 1;
}

.mapa-fondo-pagina {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: min(70%, 500px);
    opacity: 0;
    z-index: 2;
    pointer-events: none; /* no interfiere con clicks al texto */
}

.mapa-fondo-pagina svg { width: 100%; height: auto; display: block; }

{{-- .pagina-hero-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at center, rgba(13,20,32,0.4) 0%, rgba(13,20,32,0.92) 75%);
    z-index: 1;
} --}}

.pagina-hero-content { position: relative; z-index: 3; }
.pagina-hero-content h1 { font-size: clamp(30px, 5vw, 48px); font-weight: 700; margin-bottom: 14px; }
.pagina-hero-sub { font-size: 14px; color: rgba(255,255,255,.6); }

#filiales-lista { background: #12192b; min-height: auto; padding: 80px 24px 100px; }

.filiales-grid-cards {
    position: relative; z-index: 2;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    max-width: 1100px;
    width: 100%;
}

.filial-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.3s ease, border-color 0.3s ease;
}
.filial-card:hover { transform: translateY(-4px); border-color: rgba(201,161,90,0.35); }

.filial-card-imagen { width: 100%; height: 160px; background: rgba(255,255,255,0.02); }
.filial-card-imagen img { width: 100%; height: 100%; object-fit: cover; display: block; }
.filial-card-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: rgba(201,161,90,0.06);
}
.filial-card-placeholder i { font-size: 34px; color: rgba(201,161,90,0.35); }

.filial-card-datos { padding: 20px 22px; }
.filial-card-datos h3 { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
.filial-card-ciudad {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 11px; letter-spacing: .5px; text-transform: uppercase;
    color: #c9a15a; margin-bottom: 10px;
}
.filial-card-direccion { font-size: 13px; color: rgba(255,255,255,.55); line-height: 1.5; margin-bottom: 14px; }
.filial-card-telefono {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: 13px; color: #fff; text-decoration: none;
    background: rgba(37,211,102,0.12); border: 1px solid rgba(37,211,102,0.3);
    padding: 8px 14px; border-radius: 8px;
}
.filial-card-telefono i { color: #25d366; }

@media (max-width: 860px) {
    .filiales-grid-cards { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 560px) {
    .filiales-grid-cards { grid-template-columns: 1fr; }
}



/* resources/views/publico/_estilos.blade.php */

/* --- Hero de página comunicados (sin imagen, solo degradado) --- */


#comunicados-hero {
    background: #0d1420;
    min-height: 90vh;
}

.comunicados-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 150%;
    background-image: url('{{ asset('img/comunicados.png') }}');
    background-size: cover;
    background-position: center;
    z-index: 0;
}

.comunicados-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(13,20,32,0.55) 0%, rgba(13,20,32,0.88) 100%);
    z-index: 1;
}

/* --- Grid de comunicados --- */
#comunicados-lista {
    background: #0d1420;
    min-height: auto;
    padding: 80px 24px 100px;
}

.comunicados-grid {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    max-width: 1100px;
    width: 100%;
}

/* --- Card de comunicado --- */
.com-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease, border-color 0.3s ease;
    display: flex;
    flex-direction: column;
}
.com-card:hover {
    transform: translateY(-4px);
    border-color: rgba(201,161,90,0.35);
}

.com-card-imagen {
    position: relative;
    width: 100%;
    height: 150px;
    flex-shrink: 0;
}
.com-card-imagen img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
}
.com-card-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,0.03);
}
.com-card-placeholder i { font-size: 32px; color: rgba(201,161,90,0.3); }

.com-badge-fijado {
    position: absolute; top: 10px; right: 10px;
    background: rgba(201,161,90,0.9);
    color: #241a06; width: 28px; height: 28px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px;
}

.com-card-cuerpo {
    padding: 18px 20px 22px;
    display: flex; flex-direction: column; gap: 8px; flex: 1;
}

.com-card-meta {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.com-tag {
    font-size: 10px; letter-spacing: 1px; text-transform: uppercase;
    background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.6);
    padding: 3px 10px; border-radius: 20px;
}
.com-tag-destacado { background: rgba(201,161,90,0.15); color: #e0c088; }
.com-tag-novedad { background: rgba(99,179,237,0.15); color: #90cdf4; }
.com-tag-aviso { background: rgba(252,129,74,0.15); color: #fca572; }

.com-fecha { font-size: 11px; color: rgba(255,255,255,0.4); }

.com-card-cuerpo h3 { font-size: 15px; font-weight: 600; line-height: 1.4; }
.com-card-cuerpo p { font-size: 13px; color: rgba(255,255,255,.55); line-height: 1.5; flex: 1; }

.com-leer-mas {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; color: #c9a15a; margin-top: 4px;
}
.com-leer-mas i { font-size: 10px; transition: transform 0.2s; }
.com-card:hover .com-leer-mas i { transform: translateX(4px); }

/* --- Paginación --- */
.paginacion {
    position: relative; z-index: 2;
    margin-top: 48px; width: 100%; max-width: 1100px;
}
/* Sobrescribe el estilo Tailwind/Bootstrap que genera Laravel para la paginación */
.paginacion nav { display: flex; justify-content: center; }
.paginacion span, .paginacion a {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 36px; height: 36px;
    margin: 0 3px; border-radius: 8px;
    font-size: 13px; text-decoration: none;
    border: 1px solid rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.6);
    background: rgba(255,255,255,0.04);
    padding: 0 10px;
    transition: background .2s, border-color .2s;
}
.paginacion a:hover {
    background: rgba(201,161,90,0.15);
    border-color: rgba(201,161,90,0.4);
    color: #e8cd9a;
}
.paginacion [aria-current="page"] span {
    background: rgba(201,161,90,0.2);
    border-color: rgba(201,161,90,0.5);
    color: #e8cd9a;
}

/* --- Modal de detalle de comunicado --- */
.modal-comunicado-detalle {
    max-width: 620px;
    padding: 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: 85vh;
}

.mcd-imagen-wrap { width: 100%; flex-shrink: 0; }
.mcd-imagen-wrap img { width: 100%; height: 220px; object-fit: cover; display: block; }

.mcd-cuerpo {
    padding: 28px 32px 32px;
    display: flex; flex-direction: column; gap: 12px;
    overflow-y: auto;
}
.mcd-cuerpo h2 { font-size: 20px; font-weight: 700; line-height: 1.3; }
.mcd-cuerpo #mcdContenido {
    font-size: 14px; color: rgba(255,255,255,.7);
    line-height: 1.8; white-space: pre-wrap;
}

.mcd-adjuntos {
    display: flex; flex-direction: column; gap: 10px;
    padding-top: 16px;
    border-top: 1px solid rgba(255,255,255,0.08);
    margin-top: 8px;
}
.mcd-adjunto {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 10px;
    text-decoration: none; color: rgba(255,255,255,0.85); font-size: 13px;
    transition: background .2s, border-color .2s;
}
.mcd-adjunto:hover { background: rgba(201,161,90,0.1); border-color: rgba(201,161,90,0.35); }
.mcd-adjunto i.adjunto-icono { font-size: 18px; color: #c9a15a; width: 22px; flex-shrink: 0; }
.mcd-adjunto i.adjunto-flecha { margin-left: auto; font-size: 11px; color: rgba(255,255,255,0.35); }

@media (max-width: 860px) {
    .comunicados-grid { grid-template-columns: repeat(2, 1fr); }
    .mcd-cuerpo { padding: 22px 22px 28px; }
}
@media (max-width: 560px) {
    .comunicados-grid { grid-template-columns: 1fr; }
    .mcd-imagen-wrap img { height: 160px; }
}

/*
----------------------------------------------------------
ESTILOS PAGINA INFORMES
----------------------------------------------------------
*/
/* resources/views/publico/_estilos.blade.php */

/* --- Hero --- */
#informe-hero {
    background: #0d1420;
    min-height: 90vh;
}

.informe-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 150%;
    background-image: url('{{ asset('img/informes.avif') }}');
    background-size: cover;
    background-position: center;
    z-index: 0;
}

.informe-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(13,20,32,0.55) 0%, rgba(13,20,32,0.88) 100%);
    z-index: 1;
}

/* --- Lista de informes --- */
#informe-lista {
    background: #0d1420;
    min-height: auto;
    padding: 80px 24px 100px;
}

#informe-lista .wrap { gap: 0; }

.informe-row {
    display: grid;
    grid-template-columns: 120px 1fr auto;
    align-items: center;
    gap: 32px;
    padding: 28px 0;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    transition: padding-left 0.3s ease, border-color 0.3s ease;
    max-width: 900px;
    width: 100%;
}
.informe-row:first-of-type { border-top: 1px solid rgba(255,255,255,0.07); }
.informe-row:hover {
    padding-left: 12px;
    border-color: rgba(201,161,90,0.3);
}

.informe-row-anio {
    font-size: clamp(36px, 5vw, 52px);
    font-weight: 800;
    color: rgba(201,161,90,0.25);
    line-height: 1;
    transition: color 0.3s ease;
    text-align: center;
}
.informe-row:hover .informe-row-anio { color: #c9a15a; }

.informe-row-info h3 {
    font-size: 17px;
    font-weight: 600;
    margin-bottom: 6px;
    line-height: 1.3;
}
.informe-row-fecha {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: rgba(255,255,255,0.4);
}

.informe-row-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(201,161,90,0.1);
    border: 1px solid rgba(201,161,90,0.3);
    color: #e8cd9a;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 10px;
    white-space: nowrap;
    transition: background 0.25s ease, border-color 0.25s ease;
    flex-shrink: 0;
}
.informe-row-btn:hover {
    background: rgba(201,161,90,0.2);
    border-color: rgba(201,161,90,0.55);
    color: #fff;
}
.informe-row-btn i:first-child { font-size: 16px; color: #c9a15a; }

@media (max-width: 640px) {
    .informe-row {
        grid-template-columns: 70px 1fr;
        grid-template-rows: auto auto;
        gap: 12px 16px;
        padding: 24px 0;
    }
    .informe-row-anio { font-size: 32px; }
    .informe-row-btn {
        grid-column: 1 / -1;
        justify-content: center;
    }
    .informe-row:hover { padding-left: 6px; }
}
