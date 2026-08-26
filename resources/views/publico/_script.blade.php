/*
################################################################################
SCRIPTS PARA LAS PAGINAS
################################################################################
*/

gsap.registerPlugin(ScrollTrigger);

var particlesContainer = document.getElementById('particles');
if (particlesContainer) {
    for (var i = 0; i < 36; i++) {
        var p = document.createElement('div');
        p.className = 'particle';
        p.style.left = Math.random() * 100 + '%';
        p.style.top = Math.random() * 100 + '%';
        p.style.opacity = (Math.random() * 0.5 + 0.2).toFixed(2);
        particlesContainer.appendChild(p);
        gsap.to(p, { y: (Math.random()*40-20), x: (Math.random()*40-20), duration: 3+Math.random()*4, repeat: -1, yoyo: true, ease: 'sine.inOut' });
    }
}

gsap.to('#chevron', { y: 8, repeat: -1, yoyo: true, duration: 0.8, ease: 'sine.inOut' });

function splitLetters(el) {
    var words = el.textContent.trim().split(' ');
    el.textContent = '';
    words.forEach(function (word, wi) {
        var wordSpan = document.createElement('span');
        wordSpan.className = 'split-word';
        word.split('').forEach(function (char) {
            var charSpan = document.createElement('span');
            charSpan.className = 'split-char';
            charSpan.textContent = char;
            wordSpan.appendChild(charSpan);
        });
        el.appendChild(wordSpan);
        if (wi < words.length - 1) el.appendChild(document.createTextNode('\u00A0'));
    });
    return el.querySelectorAll('.split-char');
}

document.querySelectorAll('.split-title').forEach(function (el) {
    var chars = splitLetters(el);
    gsap.set(chars, { opacity: 0, y: 30, rotateX: -40 });
    gsap.to(chars, { opacity: 1, y: 0, rotateX: 0, duration: 0.6, stagger: 0.025, ease: 'power3.out', scrollTrigger: { trigger: el, start: 'top 85%' } });
});

//pagina publica inicial

var heroImg = document.querySelector('[data-hero-parallax]');
if (heroImg) {
    gsap.to(heroImg, {
        yPercent: -20,
        ease: 'none',
        scrollTrigger: {
            trigger: '#hero',
            start: 'top top',
            end: 'bottom top',
            scrub: true
        }
    });
}


var heroLogo = document.querySelector('.hero-logo');
var heroSlogan = document.querySelector('.hero-slogan');
var logosInstitucionales = document.querySelectorAll('.logo-institucional');

if (heroLogo) {
    var heroTl = gsap.timeline({
        scrollTrigger: {
            trigger: '#hero',
            start: 'top 60%',
            end: 'bottom top',
            toggleActions: 'play reverse play reverse'
        }
    });

    // 1. Logo: fade-in + acercamiento (zoom)
    heroTl.to(heroLogo, {
        opacity: 1,
        scale: 1,
        duration: 1,
        ease: 'power2.out'
    });

    // 2. Slogan: fade-in + deslizamiento, sin zoom (empieza un poco antes de que termine el logo)
    heroTl.to(heroSlogan, {
        opacity: 1,
        y: 0,
        duration: 0.8,
        ease: 'power1.out'
    }, '-=0.6');

    // 3. Logos institucionales: fade-in uno tras otro (stagger)
    heroTl.to(logosInstitucionales, {
        opacity: 1,
        y: 0,
        duration: 0.6,
        ease: 'power2.out',
        stagger: 0.25
    }, '-=0.2');
}

document.querySelectorAll('[data-speed]').forEach(function (el) {
    var speed = parseFloat(el.getAttribute('data-speed'));
    gsap.to(el, { yPercent: -100 * speed, ease: 'none', scrollTrigger: { trigger: el.closest('section'), start: 'top bottom', end: 'bottom top', scrub: true } });
});

document.querySelectorAll('[reveal]').forEach(function (el) {
    gsap.from(el, { opacity: 0, y: 40, duration: 0.8, ease: 'power2.out', scrollTrigger: { trigger: el, start: 'top 85%' } });
});


ScrollTrigger.create({
    start: 50,
    onUpdate: function () {
        var scrolled = window.scrollY > 50;
        document.querySelector('.nav').style.background = scrolled ? 'rgba(13,20,32,0.7)' : 'transparent';
        document.querySelector('.nav').style.backdropFilter = scrolled ? 'blur(10px)' : 'none';
    }
});

var burger = document.getElementById('burger');
var mobileMenu = document.getElementById('mobileMenu');
var menuOpen = false;
burger.addEventListener('click', function () {
    menuOpen = !menuOpen;
    if (menuOpen) {
        mobileMenu.classList.add('open');
        gsap.to(mobileMenu, { opacity: 1, visibility: 'visible', duration: 0.3 });
        gsap.to(mobileMenu.querySelectorAll('a'), { opacity: 1, y: 0, duration: 0.4, stagger: 0.06, delay: 0.1 });
        burger.innerHTML = '<i class="fa-solid fa-xmark"></i>';
    } else {
        gsap.to(mobileMenu, { opacity: 0, duration: 0.25, onComplete: function () { mobileMenu.classList.remove('open'); } });
        burger.innerHTML = '<i class="fa-solid fa-bars"></i>';
    }
});
mobileMenu.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
        menuOpen = false;
        gsap.to(mobileMenu, { opacity: 0, duration: 0.25, onComplete: function () { mobileMenu.classList.remove('open'); } });
        burger.innerHTML = '<i class="fa-solid fa-bars"></i>';
    });
});

/* Modales de comunicados: se abren automaticamente al cargar, uno tras otro */
var modales = document.querySelectorAll('.modal-comunicado');
if (modales.length > 0) {
    gsap.to(modales[0], { opacity: 1, visibility: 'visible', duration: 0.3 });
    gsap.fromTo(modales[0].querySelector('.modal-box'), { scale: 0.9 }, { scale: 1, duration: 0.3, ease: 'back.out(1.4)' });
}
document.querySelectorAll('[data-cerrar]').forEach(function (btn, idx) {
    btn.addEventListener('click', function () {
        var overlay = btn.closest('.modal-overlay');
        gsap.to(overlay, {
            opacity: 0, duration: 0.25,
            onComplete: function () {
                overlay.classList.remove('open');
                overlay.style.visibility = 'hidden';
                var siguiente = modales[idx + 1];
                if (siguiente) {
                    gsap.to(siguiente, { opacity: 1, visibility: 'visible', duration: 0.3 });
                    gsap.fromTo(siguiente.querySelector('.modal-box'), { scale: 0.9 }, { scale: 1, duration: 0.3, ease: 'back.out(1.4)' });
                }
            }
        });
    });
});

// PARA ANIMACION DE MODALS DE SERVICIOS
// resources/views/publico/_script.blade.php

var modalServicio = document.getElementById('modalServicio');
var cerrarModalServicio = document.getElementById('cerrarModalServicio');

if (modalServicio && cerrarModalServicio) {
    document.querySelectorAll('[data-servicio]').forEach(function (card) {
        card.addEventListener('click', function () {
            document.getElementById('msTitulo').textContent = card.dataset.titulo;
            document.getElementById('msDescripcion').textContent = card.dataset.descripcion;
            document.getElementById('msImagen').src = card.dataset.imagen;
            document.getElementById('msImagen').alt = card.dataset.titulo;

            var direccion = card.dataset.direccion;
            var telefono = card.dataset.telefono;
            var capacidad = card.dataset.capacidad;

            document.getElementById('msItemDireccion').style.display = direccion ? 'flex' : 'none';
            document.getElementById('msDireccion').textContent = direccion;

            document.getElementById('msItemTelefono').style.display = telefono ? 'flex' : 'none';
            document.getElementById('msTelefono').textContent = telefono;

            document.getElementById('msItemCapacidad').style.display = capacidad ? 'flex' : 'none';
            document.getElementById('msCapacidad').textContent = capacidad ? capacidad + ' personas' : '';

            modalServicio.classList.add('open');
            gsap.to(modalServicio, { opacity: 1, visibility: 'visible', duration: 0.3 });
            gsap.fromTo(modalServicio.querySelector('.modal-box'),
                { scale: 0.92, opacity: 0 },
                { scale: 1, opacity: 1, duration: 0.35, ease: 'back.out(1.3)' }
            );
        });
    });

    cerrarModalServicio.addEventListener('click', function () {
        gsap.to(modalServicio, {
            opacity: 0,
            duration: 0.25,
            onComplete: function () {
                modalServicio.classList.remove('open');
                modalServicio.style.visibility = 'hidden';
            }
        });
    });

    // Cerrar también al hacer click fuera de la tarjeta (en el overlay)
    modalServicio.addEventListener('click', function (e) {
        if (e.target === modalServicio) {
            cerrarModalServicio.click();
        }
    });
}



/* Parallax del fondo de Apoyo Económico (mismo mecanismo que el Hero) */
var apoyoImg = document.querySelector('[data-hero-parallax-apoyo]');
if (apoyoImg) {
    gsap.to(apoyoImg, {
        yPercent: -20,
        ease: 'none',
        scrollTrigger: {
            trigger: '#apoyo',
            start: 'top bottom',
            end: 'bottom top',
            scrub: true
        }
    });
}

/* Modal de Apoyo Económico */
var modalPrestamo = document.getElementById('modalPrestamo');
var cerrarModalPrestamo = document.getElementById('cerrarModalPrestamo');

if (modalPrestamo && cerrarModalPrestamo) {
    var iconosPorTipo = {
        requisitos: 'fa-file-lines',
        contrato: 'fa-file-signature',
        formulario: 'fa-file-pen'
    };
    var etiquetasPorTipo = {
        requisitos: 'Requisitos',
        contrato: 'Contrato',
        formulario: 'Formulario'
    };

    document.querySelectorAll('[data-prestamo]').forEach(function (card) {
        card.addEventListener('click', function () {
            document.getElementById('mpTitulo').textContent = card.dataset.nombre;
            document.getElementById('mpDescripcion').textContent = card.dataset.descripcion;

            var iconoEl = document.getElementById('mpIcono');
            iconoEl.className = 'fa-solid ' + card.dataset.icono;

            var documentos = JSON.parse(card.dataset.documentos || '[]');
            var contenedor = document.getElementById('mpDocumentos');
            contenedor.innerHTML = '';

            if (documentos.length === 0) {
                contenedor.innerHTML = '<p class="documentos-vacio">Aún no hay documentos disponibles para esta categoría.</p>';
            } else {
                documentos.forEach(function (doc) {
                    var a = document.createElement('a');
                    a.href = doc.url;
                    a.target = '_blank';
                    a.className = 'documento-item';
                    a.innerHTML =
                        '<i class="fa-solid doc-icono ' + (iconosPorTipo[doc.tipo] || 'fa-file') + '"></i>' +
                        '<span>' + doc.etiqueta + '<span class="doc-tipo-tag">' + (etiquetasPorTipo[doc.tipo] || doc.tipo) + '</span></span>' +
                        '<i class="fa-solid fa-arrow-up-right-from-square doc-descarga"></i>';
                    contenedor.appendChild(a);
                });
            }

            modalPrestamo.classList.add('open');
            gsap.to(modalPrestamo, { opacity: 1, visibility: 'visible', duration: 0.3 });
            gsap.fromTo(modalPrestamo.querySelector('.modal-box'),
                { scale: 0.92, opacity: 0 },
                { scale: 1, opacity: 1, duration: 0.35, ease: 'back.out(1.3)' }
            );
        });
    });

    cerrarModalPrestamo.addEventListener('click', function () {
        gsap.to(modalPrestamo, {
            opacity: 0, duration: 0.25,
            onComplete: function () {
                modalPrestamo.classList.remove('open');
                modalPrestamo.style.visibility = 'hidden';
            }
        });
    });

    modalPrestamo.addEventListener('click', function (e) {
        if (e.target === modalPrestamo) cerrarModalPrestamo.click();
    });
}


//svg mapa de bolivia
var mapaContenedor = document.getElementById('mapaBolivia');
if (mapaContenedor) {
    fetch(mapaContenedor.dataset.src)
        .then(function (res) { return res.text(); })
        .then(function (svgText) {
            mapaContenedor.innerHTML = svgText;

            var svgEl = mapaContenedor.querySelector('svg');
            if (svgEl) {
                svgEl.setAttribute('viewBox', '0 0 2000 2208');
                svgEl.removeAttribute('width');
                svgEl.removeAttribute('height');
                svgEl.setAttribute('preserveAspectRatio', 'xMidYMid meet');
            }

            mapaContenedor.querySelectorAll('text').forEach(function (t) { t.remove(); });

            var paths = mapaContenedor.querySelectorAll('path');
            var colorBase = { fill: 'rgba(201, 161, 90, 0.14)', stroke: 'rgba(201, 161, 90, 0.55)' };
            var colorActivo = { fill: 'rgba(201, 161, 90, 0.4)', stroke: '#e8cd9a' };

            function aplicarEstilo(path, color) {
                gsap.to(path, {
                    fill: color.fill,
                    stroke: color.stroke,
                    duration: 0.35,
                    ease: 'power1.inOut'
                });
            }

            paths.forEach(function (path) {
                path.style.fill = colorBase.fill;
                path.style.stroke = colorBase.stroke;
                path.style.strokeWidth = '4';
                path.style.strokeLinejoin = 'round';
                path.style.cursor = 'pointer';
            });

            // --- Recorrido automático, uno cada 0.5s (sin condicionarlo al scroll) ---
            var indiceActual = -1;
            var pausadoPorHover = false;

            function resaltarSiguiente() {
                if (pausadoPorHover) return;
                if (indiceActual >= 0) {
                    aplicarEstilo(paths[indiceActual], colorBase);
                }
                indiceActual = (indiceActual + 1) % paths.length;
                aplicarEstilo(paths[indiceActual], colorActivo);
            }

            resaltarSiguiente();
            setInterval(resaltarSiguiente, 800);

            // Pausa el ciclo si el usuario interactúa manualmente con el mapa
            paths.forEach(function (path) {
                path.addEventListener('mouseenter', function () {
                    pausadoPorHover = true;
                    if (indiceActual >= 0) aplicarEstilo(paths[indiceActual], colorBase);
                    aplicarEstilo(path, colorActivo);
                });
                path.addEventListener('mouseleave', function () {
                    pausadoPorHover = false;
                    aplicarEstilo(path, colorBase);
                });
            });

            gsap.to(mapaContenedor, {
                opacity: 1,
                scale: 1,
                duration: 1,
                ease: 'power2.out',
                scrollTrigger: { trigger: '#cta', start: 'top 70%' }
            });
        })
        .catch(function () {
            console.warn('No se pudo cargar el mapa de Bolivia SVG.');
        });
}

//motor del carrusel 

var carousel = document.getElementById('conveniosCarousel');
var track = document.getElementById('conveniosTrack');

if (carousel && track) {
    var cards = track.querySelectorAll('.convenio-card');
    var total = cards.length;
    var activo = 0;
    var visibles = 1; // antes: 2 — ahora solo prev/activo/next quedan visibles

    function calcularParametros() {
        var esDesktop = window.innerWidth >= 1024;
        return {
            espaciado: esDesktop ? 260 : 230,
            profundidad: esDesktop ? 220 : 200, // un poco menos: ya no necesitamos "esconder" tan lejos
            rotacion: esDesktop ? 32 : 30         // giro más sutil, se ve mejor con solo 1 nivel visible
        };
    }
    var params = calcularParametros();

    function offsetCircular(i, activo, total) {
        var diff = (i - activo) % total;
        if (diff > total / 2) diff -= total;
        if (diff < -total / 2) diff += total;
        return diff;
    }

    function calcularEstado(offset) {
        var abs = Math.abs(offset);
        return {
            x: offset * params.espaciado,
            z: -abs * params.profundidad,
            rotationY: offset * -params.rotacion,
            // Fade más agresivo: las que están justo despues del limite visible
            // ya casi no se notan, evitando el "salto" brusco a invisible
            opacity: abs > visibles ? 0 : (abs === 0 ? 1 : 0.5),
            scale: abs === 0 ? 1 : 0.82,
            zIndex: 100 - abs
        };
    }

    function actualizarPosiciones() {
        cards.forEach(function (card, i) {
            var offset = offsetCircular(i, activo, total);
            var abs = Math.abs(offset);
            var estado = calcularEstado(offset);

            gsap.to(card, Object.assign({}, estado, {
                duration: 0.8,
                ease: 'power3.inOut'
            }));
            card.style.pointerEvents = abs > visibles ? 'none' : 'auto';
        });
    }

    gsap.set(track, { transformPerspective: 1400 });
    cards.forEach(function (card, i) {
        var offset = offsetCircular(i, activo, total);
        var estado = calcularEstado(offset);
        gsap.set(card, Object.assign({}, estado, {
            xPercent: -50,
            yPercent: -50
        }));
    });

    function siguiente() {
        activo = (activo + 1) % total;
        actualizarPosiciones();
    }

    // Dispara la primera transición animada inmediatamente al cargar
    siguiente();

    // Luego inicia el ciclo automático
    var intervaloConvenios = setInterval(siguiente, 5000);

    carousel.addEventListener('mouseenter', function () { clearInterval(intervaloConvenios); });
    carousel.addEventListener('mouseleave', function () { intervaloConvenios = setInterval(siguiente, 2000); });

    cards.forEach(function (card, i) {
        card.addEventListener('click', function () {
            activo = i;
            actualizarPosiciones();
        });
    });

    var resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function () {
            params = calcularParametros();
            actualizarPosiciones();
        }, 200);
    });
}

/*
################################################################################
SCRIPTS PARA LAS PAGINAS
################################################################################
*/

//filiales
// resources/views/publico/_script.blade.php

/* Parallax del fondo de Filiales */
var filialesHeroBg = document.querySelector('[data-parallax-bg-filiales]');
if (filialesHeroBg) {
    gsap.to(filialesHeroBg, {
        yPercent: -35,
        ease: 'none',
        scrollTrigger: {
            trigger: '[data-parallax-filiales]',
            start: 'top top',
            end: 'bottom top',
            scrub: true
        }
    });
}

/* Mapa SVG animado de fondo */
var mapaFondoPagina = document.getElementById('mapaBolivia');
if (mapaFondoPagina && mapaFondoPagina.classList.contains('mapa-fondo-pagina')) {
    fetch(mapaFondoPagina.dataset.src)
        .then(function (res) { return res.text(); })
        .then(function (svgText) {
            mapaFondoPagina.innerHTML = svgText;

            var svgEl = mapaFondoPagina.querySelector('svg');
            if (svgEl) {
                svgEl.setAttribute('viewBox', '0 0 2000 2208');
                svgEl.removeAttribute('width');
                svgEl.removeAttribute('height');
            }
            mapaFondoPagina.querySelectorAll('text').forEach(function (t) { t.remove(); });

            var paths = mapaFondoPagina.querySelectorAll('path');

            // Colores más sutiles que en el CTA — aquí compite con la imagen de fondo
            var colorBase = { fill: 'rgba(201, 161, 90, 0.08)', stroke: 'rgba(201, 161, 90, 0.3)' };
            var colorActivo = { fill: 'rgba(201, 161, 90, 0.25)', stroke: 'rgba(201, 161, 90, 0.7)' };

            paths.forEach(function (path) {
                path.style.fill = colorBase.fill;
                path.style.stroke = colorBase.stroke;
                path.style.strokeWidth = '4';
            });

            var indiceActual = -1;
            function resaltarSiguiente() {
                if (indiceActual >= 0) {
                    gsap.to(paths[indiceActual], { fill: colorBase.fill, stroke: colorBase.stroke, duration: 0.4 });
                }
                indiceActual = (indiceActual + 1) % paths.length;
                gsap.to(paths[indiceActual], { fill: colorActivo.fill, stroke: colorActivo.stroke, duration: 0.4 });
            }

            resaltarSiguiente();
            setInterval(resaltarSiguiente, 800);

            // El mapa aparece con fade una vez cargado el SVG
            gsap.to(mapaFondoPagina, { opacity: 0.9, duration: 1.2, ease: 'power2.out' });
        })
        .catch(function () { console.warn('No se pudo cargar el mapa SVG.'); });
}


//comunicados

var modalComunicado = document.getElementById('modalComunicado');
var cerrarModalComunicado = document.getElementById('cerrarModalComunicado');

if (modalComunicado && cerrarModalComunicado) {
    var etiquetasTipo = {
        normal: 'Comunicado', destacado: 'Destacado',
        novedad: 'Novedad', modal: 'Aviso'
    };
    var coloresTipo = {
        normal: '', destacado: 'com-tag-destacado',
        novedad: 'com-tag-novedad', modal: 'com-tag-aviso'
    };

    document.querySelectorAll('[data-comunicado]').forEach(function (card) {
        card.addEventListener('click', function () {
            var titulo = card.dataset.titulo;
            var tipo = card.dataset.tipo;
            var fecha = card.dataset.fecha;
            var contenido = card.dataset.contenido;
            var imagen = card.dataset.imagen;
            var pdf = card.dataset.pdf;
            var video = card.dataset.video;
            var pptx = card.dataset.pptx;

            document.getElementById('mcdTitulo').textContent = titulo;
            document.getElementById('mcdContenido').textContent = contenido;
            document.getElementById('mcdFecha').textContent = fecha;

            var tagEl = document.getElementById('mcdTag');
            tagEl.textContent = etiquetasTipo[tipo] || 'Comunicado';
            tagEl.className = 'com-tag ' + (coloresTipo[tipo] || '');

            // Imagen
            var imagenWrap = document.getElementById('mcdImagenWrap');
            var imagenEl = document.getElementById('mcdImagen');
            if (imagen) {
                imagenEl.src = imagen;
                imagenWrap.style.display = 'block';
            } else {
                imagenWrap.style.display = 'none';
            }

            // Adjuntos dinámicos
            var adjuntos = document.getElementById('mcdAdjuntos');
            adjuntos.innerHTML = '';

            if (pdf) {
                adjuntos.innerHTML += '<a href="' + pdf + '" target="_blank" class="mcd-adjunto">' +
                    '<i class="fa-solid fa-file-pdf adjunto-icono"></i>' +
                    '<span>Ver documento PDF</span>' +
                    '<i class="fa-solid fa-arrow-up-right-from-square adjunto-flecha"></i></a>';
            }
            if (video) {
                adjuntos.innerHTML += '<a href="' + video + '" target="_blank" class="mcd-adjunto">' +
                    '<i class="fa-solid fa-circle-play adjunto-icono"></i>' +
                    '<span>Ver video</span>' +
                    '<i class="fa-solid fa-arrow-up-right-from-square adjunto-flecha"></i></a>';
            }
            if (pptx) {
                adjuntos.innerHTML += '<a href="' + pptx + '" target="_blank" class="mcd-adjunto">' +
                    '<i class="fa-solid fa-file-powerpoint adjunto-icono"></i>' +
                    '<span>Descargar presentación</span>' +
                    '<i class="fa-solid fa-arrow-up-right-from-square adjunto-flecha"></i></a>';
            }
            if (adjuntos.innerHTML === '') {
                adjuntos.style.display = 'none';
            } else {
                adjuntos.style.display = 'flex';
            }

            modalComunicado.classList.add('open');
            gsap.to(modalComunicado, { opacity: 1, visibility: 'visible', duration: 0.3 });
            gsap.fromTo(modalComunicado.querySelector('.modal-box'),
                { scale: 0.92, opacity: 0 },
                { scale: 1, opacity: 1, duration: 0.35, ease: 'back.out(1.3)' }
            );
        });
    });

    cerrarModalComunicado.addEventListener('click', function () {
        gsap.to(modalComunicado, {
            opacity: 0, duration: 0.25,
            onComplete: function () {
                modalComunicado.classList.remove('open');
                modalComunicado.style.visibility = 'hidden';
            }
        });
    });

    modalComunicado.addEventListener('click', function (e) {
        if (e.target === modalComunicado) cerrarModalComunicado.click();
    });
}

var comunicadosHeroBg = document.querySelector('[data-parallax-bg]');
if (comunicadosHeroBg) {
    gsap.to(comunicadosHeroBg, {
        yPercent: -35,
        ease: 'none',
        scrollTrigger: {
            trigger: '[data-parallax-comunicados]',
            start: 'top top',
            end: 'bottom top',
            scrub: true
        }
    });
}

//informes anuales
var informesHeroBg = document.querySelector('[data-parallax-bg-informes]');
if (informesHeroBg) {
    gsap.to(informesHeroBg, {
        yPercent: -35,
        ease: 'none',
        scrollTrigger: {
            trigger: '[data-parallax-informes]',
            start: 'top top',
            end: 'bottom top',
            scrub: true
        }
    });
}

// Simulador de préstamos
var modalSimulador = document.getElementById('modalSimulador');
var abrirSimulador = document.getElementById('abrirSimulador');
var cerrarSimulador = document.getElementById('cerrarSimulador');
var formSimulador = document.getElementById('formSimulador');

if (modalSimulador && abrirSimulador && cerrarSimulador && formSimulador) {
    var productoSim = document.getElementById('simProducto');
    var montoSim = document.getElementById('simMonto');
    var plazoSim = document.getElementById('simPlazo');
    var plazoValorSim = document.getElementById('simPlazoValor');
    var aniosGrupoSim = document.getElementById('simAniosGrupo');
    var aniosSim = document.getElementById('simAnios');
    var limitesSim = document.getElementById('simLimites');
    var erroresSim = document.getElementById('simErrores');
    var resultadoSim = document.getElementById('simResultado');

    function monedaSim(valor) {
        return new Intl.NumberFormat('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(valor));
    }

    function actualizarProductoSim() {
        var opcion = productoSim.options[productoSim.selectedIndex];
        var maximo = Number(opcion.dataset.maximo);
        var minimo = Number(opcion.dataset.minimo || 1);
        var plazoMaximo = Number(opcion.dataset.plazoMaximo);
        var plazoMinimo = Number(opcion.dataset.plazoMinimo || 1);
        montoSim.min = minimo; montoSim.max = maximo;
        plazoSim.min = plazoMinimo; plazoSim.max = plazoMaximo;
        if (Number(plazoSim.value) < plazoMinimo || Number(plazoSim.value) > plazoMaximo) plazoSim.value = Math.min(12, plazoMaximo);
        plazoValorSim.textContent = plazoSim.value;
        limitesSim.textContent = 'Hasta Bs ' + monedaSim(maximo) + ' · Plazo máximo: ' + plazoMaximo + ' meses';
        var requiereAnios = opcion.dataset.antiguedad === '1';
        aniosGrupoSim.hidden = !requiereAnios;
        aniosSim.required = requiereAnios;
        if (!requiereAnios) aniosSim.value = '';
        resultadoSim.hidden = true; erroresSim.hidden = true;
    }

    function abrirModalSim() {
        modalSimulador.classList.add('open');
        modalSimulador.setAttribute('aria-hidden', 'false');
        gsap.to(modalSimulador, { opacity: 1, visibility: 'visible', duration: .25 });
        gsap.fromTo(modalSimulador.querySelector('.modal-box'), { scale: .94, opacity: 0 }, { scale: 1, opacity: 1, duration: .3, ease: 'back.out(1.25)' });
        actualizarProductoSim();
        productoSim.focus();
    }

    function cerrarModalSim() {
        gsap.to(modalSimulador, { opacity: 0, duration: .2, onComplete: function () {
            modalSimulador.classList.remove('open'); modalSimulador.style.visibility = 'hidden'; modalSimulador.setAttribute('aria-hidden', 'true');
        }});
    }

    abrirSimulador.addEventListener('click', abrirModalSim);
    cerrarSimulador.addEventListener('click', cerrarModalSim);
    modalSimulador.addEventListener('click', function (e) { if (e.target === modalSimulador) cerrarModalSim(); });
    productoSim.addEventListener('change', actualizarProductoSim);
    plazoSim.addEventListener('input', function () { plazoValorSim.textContent = plazoSim.value; });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modalSimulador.classList.contains('open')) cerrarModalSim(); });

    formSimulador.addEventListener('submit', function (e) {
        e.preventDefault(); erroresSim.hidden = true; resultadoSim.hidden = true;
        if (!formSimulador.reportValidity()) return;
        var boton = formSimulador.querySelector('button[type="submit"]');
        boton.disabled = true; boton.textContent = 'Calculando...';
        fetch(formSimulador.action, {
            method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(formSimulador)
        }).then(function (response) {
            return response.json().then(function (data) { if (!response.ok) throw data; return data; });
        }).then(function (data) {
            var resumen = '';
            if (data.monto_solicitado !== undefined) {
                resumen = '<div class="simulador-resumen">' +
                    '<div class="simulador-dato">Monto solicitado<strong>Bs ' + monedaSim(data.monto_solicitado) + '</strong></div>' +
                    '<div class="simulador-dato">Plazo<strong>' + data.plazo + ' meses</strong></div>' +
                    '<div class="simulador-dato">Cuota mensual estimada<strong>Bs ' + monedaSim(data.cuota_mensual) + '</strong></div>' +
                    '<div class="simulador-dato">Monto máximo estimado<strong>Bs ' + monedaSim(data.monto_maximo_estimado) + '</strong></div></div>';
            }
            resultadoSim.innerHTML = '<h4>Resultado de tu simulación</h4>' + resumen + '<p class="simulador-mensaje">' + data.mensaje + '</p>';
            resultadoSim.hidden = false;
        }).catch(function (error) {
            var mensajes = error.errors ? Object.values(error.errors).flat().join(' ') : (error.message || 'No fue posible realizar la simulación. Intenta nuevamente.');
            erroresSim.textContent = mensajes; erroresSim.hidden = false;
        }).finally(function () {
            boton.disabled = false; boton.innerHTML = '<i class="fa-solid fa-calculator"></i> Calcular préstamo';
        });
    });
}
