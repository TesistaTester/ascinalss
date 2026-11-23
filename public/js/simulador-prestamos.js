(() => {
    'use strict';
    const modal = document.getElementById('modalSimulador');
    const abrir = document.getElementById('abrirSimulador');
    if (!modal || !abrir) return;

    const form = document.getElementById('formSimulador');
    const anterior = document.getElementById('simAnterior');
    const siguiente = document.getElementById('simSiguiente');
    const errores = document.getElementById('simErrores');
    const resultado = document.getElementById('simResultado');
    const secciones = [...modal.querySelectorAll('[data-paso]')];
    const pasos = [...modal.querySelectorAll('.sim-pasos li')];

    let paso = 0, estimacion = null, ocupado = false, version = 0, focoPrevio, overflowPrevio;
    const money = n => n === null || n === undefined ? 'Pendiente de evaluación' : 'Bs ' + Number(n).toLocaleString('es-BO', {minimumFractionDigits:2, maximumFractionDigits:2});
    const reduced = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // --- TOOLTIP CONTEXTUAL EN VIVO AL CLIC / ENFOQUE (DISEÑO CLARO) ---
    const tooltipBox = document.createElement('div');
    tooltipBox.className = 'sim-tooltip-contextual';
    tooltipBox.setAttribute('role', 'tooltip');
    tooltipBox.setAttribute('aria-hidden', 'true');
    document.body.appendChild(tooltipBox);

    function posicionarTooltip(el) {
        const rect = el.getBoundingClientRect();
        const boxWidth = 290;
        let left = rect.left;
        let top = rect.bottom + 6;

        if (left + boxWidth > window.innerWidth - 12) {
            left = window.innerWidth - boxWidth - 12;
        }
        if (left < 10) left = 10;

        tooltipBox.style.left = left + 'px';
        tooltipBox.style.top = (top + window.scrollY) + 'px';
    }

    function mostrarTooltip(el) {
        const titulo = el.dataset.ayudaTitulo;
        let desc = el.dataset.ayudaDesc;
        if (!titulo || !desc) {
            ocultarTooltip();
            return;
        }

        // Si es el campo monto, enriquecer dinámicamente con los límites de la modalidad activa
        if (el.name === 'monto_solicitado') {
            const selectProd = form.elements.producto;
            const opt = selectProd?.selectedOptions[0];
            if (opt) {
                const max = Number(opt.dataset.max || 0);
                const min = Number(opt.dataset.min || 1);
                desc += `<br><strong style="color:#0f172a; display:inline-block; margin-top:6px;">Límites para esta modalidad:</strong><br>` +
                        `• Mínimo: <strong>Bs ${min.toLocaleString('es-BO')}</strong><br>` +
                        `• Máximo permitido: <strong>Bs ${max.toLocaleString('es-BO')}</strong>`;
            }
        }

        // Si es el campo plazo, enriquecer con el plazo máximo
        if (el.name === 'plazo') {
            const selectProd = form.elements.producto;
            const opt = selectProd?.selectedOptions[0];
            if (opt) {
                const plazoMax = opt.dataset.plazoMax || 180;
                desc += `<br><strong style="color:#0f172a; display:inline-block; margin-top:6px;">Plazo máximo de esta modalidad:</strong> <strong>${plazoMax} meses</strong> (${Math.round(plazoMax/12)} años).`;
            }
        }

        tooltipBox.innerHTML = `
            <div class="sim-tooltip-contextual-titulo">${titulo}</div>
            <p class="sim-tooltip-contextual-desc">${desc}</p>
        `;
        posicionarTooltip(el);
        tooltipBox.classList.add('visible');
        tooltipBox.setAttribute('aria-hidden', 'false');
    }

    function ocultarTooltip() {
        tooltipBox.classList.remove('visible');
        tooltipBox.setAttribute('aria-hidden', 'true');
    }

    modal.querySelectorAll('[data-ayuda-titulo]').forEach(el => {
        el.addEventListener('focus', () => mostrarTooltip(el));
        el.addEventListener('click', () => mostrarTooltip(el));
        el.addEventListener('blur', ocultarTooltip);
    });

    modal.querySelector('.modal-box')?.addEventListener('scroll', ocultarTooltip, { passive: true });

    function error(text) { errores.textContent = text; errores.hidden = !text; }

    function actualizarLimitesVisibles() {
        const selectProd = form.elements.producto;
        const opt = selectProd?.selectedOptions[0];
        const montoInput = form.elements.monto_solicitado;
        const plazoInput = form.elements.plazo;
        const txtMonto = document.getElementById('sim_monto_limites');
        const txtPlazo = document.getElementById('sim_plazo_limites');

        if (opt) {
            const max = Number(opt.dataset.max || 0);
            const min = Number(opt.dataset.min || 1);
            const plazoMax = Number(opt.dataset.plazoMax || 180);

            if (montoInput) {
                montoInput.max = max;
                montoInput.min = min;
            }
            if (plazoInput) {
                plazoInput.max = plazoMax;
            }

            if (txtMonto) {
                txtMonto.textContent = `Permitido: de Bs ${min.toLocaleString('es-BO')} hasta Bs ${max.toLocaleString('es-BO')}`;
            }
            if (txtPlazo) {
                txtPlazo.textContent = `Plazo máximo para esta modalidad: ${plazoMax} meses (${Math.round(plazoMax/12)} años)`;
            }
        }
    }

    function condicionales() {
        const codigo = form.elements.producto.value;
        const ref = form.elements.operacion.value === 'REFINANCIAMIENTO';
        form.querySelectorAll('[data-productos], [data-refinanciamiento]').forEach(el => {
            const visible = el.hasAttribute('data-refinanciamiento') ? ref : el.dataset.productos.split(' ').includes(codigo);
            el.hidden = !visible;
            el.querySelectorAll('input,select').forEach(input => { input.disabled = !visible; });
        });
        actualizarLimitesVisibles();
    }

    function mostrar(n) {
        ocultarTooltip();
        paso = n;
        secciones.forEach((el, i) => { el.hidden = i !== n; });
        condicionales();
        pasos.forEach((el, i) => {
            if (i === n) el.setAttribute('aria-current', 'step');
            else el.removeAttribute('aria-current');
        });

        // Configuración de botones de navegación
        anterior.hidden = n === 0;
        anterior.textContent = n === 2 ? '← Modificar datos / Recalcular' : '← Anterior';

        siguiente.hidden = n === 2;
        siguiente.textContent = n === 1 ? 'Calcular estimación' : 'Continuar →';

        const active = secciones[n];
        if (window.gsap && !reduced()) gsap.fromTo(active, {opacity: 0, y: 10}, {opacity: 1, y: 0, duration: .22, clearProps: 'opacity,transform'});
        active.querySelector('legend,h4')?.focus();
        modal.querySelector('.modal-box').scrollTop = 0;
    }

    function validarPaso() {
        const fields = [...secciones[paso].querySelectorAll('input,select')];
        const invalid = fields.find(el => !el.disabled && !el.checkValidity());
        if (invalid) { invalid.reportValidity(); return false; }
        return true;
    }

    function invalidar() {
        estimacion = null;
        version++;
        document.getElementById('simPlan').hidden = true;
        const btnVerPlan = document.getElementById('simVerPlan');
        if (btnVerPlan) btnVerPlan.innerHTML = '<i class="bi bi-table"></i> Ver plan de pagos';
    }

    async function post(url, datos) {
        const r = await fetch(url, {
            method: 'POST',
            body: datos,
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        });
        let data;
        try { data = await r.json(); }
        catch { throw new Error('No fue posible procesar la respuesta. Recarga la página y vuelve a intentar.'); }
        if (!r.ok) throw new Error(data.errors ? Object.values(data.errors).flat().join(' ') : data.message || 'No fue posible completar la operación.');
        return data;
    }

    function presentar(data) {
        resultado.replaceChildren();

        const wrapper = document.createElement('div');
        wrapper.className = 'sim-resultado-container';

        // 1. Configurar estado y banner
        let estadoClase = 'evaluacion';
        let estadoBadge = 'Requiere evaluación';
        let estadoTitulo = 'Consulta institucional necesaria';
        let estadoIcono = '<i class="bi bi-info-circle-fill"></i>';

        if (data.estado === 'COMPATIBLE') {
            estadoClase = 'compatible';
            estadoBadge = 'Precalificación favorable';
            estadoTitulo = 'Cumples con los criterios referenciales';
            estadoIcono = '<i class="bi bi-check-circle-fill"></i>';
        } else if (data.estado === 'SUPERA_CAPACIDAD') {
            estadoClase = 'supera';
            estadoBadge = 'Capacidad excedida';
            estadoTitulo = 'La cuota estimada supera tu margen disponible';
            estadoIcono = '<i class="bi bi-exclamation-triangle-fill"></i>';
        } else if (data.estado === 'INCUMPLE_REQUISITO') {
            estadoClase = 'incumple';
            estadoBadge = 'Requisito pendiente';
            estadoTitulo = 'Condición institucional no satisfecha';
            estadoIcono = '<i class="bi bi-x-circle-fill"></i>';
        }

        // Banner de estado
        const banner = document.createElement('div');
        banner.className = `sim-status-banner sim-status--${estadoClase}`;
        banner.innerHTML = `
            <div class="sim-status-icono">${estadoIcono}</div>
            <div class="sim-status-info">
                <span class="sim-status-badge">${estadoBadge}</span>
                <div class="sim-status-titulo">${estadoTitulo}</div>
                <p class="sim-status-mensaje">${data.mensaje || ''}</p>
            </div>
        `;
        wrapper.appendChild(banner);

        // 2. Si hay plan numérico generado
        if (data.plan) {
            // Cuota Hero
            const cuotaHero = document.createElement('div');
            cuotaHero.className = 'sim-cuota-destacada';
            cuotaHero.innerHTML = `
                <div class="sim-cuota-header">
                    <span class="sim-cuota-label"><i class="bi bi-calendar-check"></i> Cuota mensual estimada</span>
                </div>
                <div class="sim-cuota-monto">${money(data.cuota_mensual)}</div>
                <div class="sim-cuota-subtexto">
                    Amortización sistema francés · Plazo: <strong>${data.plazo} meses</strong> · Modalidad: <strong>${data.nombre_producto || 'Préstamo ASCINALSS'}</strong>
                </div>
            `;
            wrapper.appendChild(cuotaHero);

            // Grid de Métricas (KPIs)
            const grid = document.createElement('div');
            grid.className = 'sim-resumen-grid';

            const kpis = [
                {
                    label: 'Monto solicitado',
                    valor: money(data.monto_solicitado),
                    sub: null
                },
                {
                    label: 'Monto máximo al que puedes acceder',
                    valor: money(data.monto_maximo_estimado),
                    sub: 'Sujeto a aportes y calificación'
                },
                {
                    label: 'Neto estimado a recibir',
                    valor: money(data.neto_estimado),
                    sub: data.cargo_inicial > 0 ? `Deducción inicial: ${money(data.cargo_inicial)}` : 'Desembolso directo'
                },
                {
                    label: 'Total de cuotas',
                    valor: money(data.totales?.cuotas),
                    sub: 'Capital + Interés + Cargos'
                }
            ];

            grid.innerHTML = kpis.map(k => `
                <div class="sim-card-kpi">
                    <div class="sim-kpi-content">
                        <span class="sim-kpi-label">${k.label}</span>
                        <span class="sim-kpi-val">${k.valor}</span>
                        ${k.sub ? `<span class="sim-kpi-sub">${k.sub}</span>` : ''}
                    </div>
                </div>
            `).join('');
            wrapper.appendChild(grid);
        }

        // 3. Sugerencia de plazo alternativo si existe
        if (data.plazo_sugerido) {
            const sugerencia = document.createElement('div');
            sugerencia.className = 'sim-alerta-sugerencia';
            sugerencia.innerHTML = `
                <i class="bi bi-lightbulb-fill"></i>
                <div>
                    <strong>Sugerencia de plazo:</strong> Podrías probar un plazo de <strong>${data.plazo_sugerido} meses</strong> para ajustar tu cuota dentro de tu capacidad referencial. Puedes pulsar <em>"Modificar datos"</em> para recalcular.
                </div>
            `;
            wrapper.appendChild(sugerencia);
        }

        // 4. Observaciones y Condiciones Institucionales
        const todosAvisos = [...(data.razones || []), ...(data.avisos || [])];
        if (todosAvisos.length > 0) {
            const notasBox = document.createElement('div');
            notasBox.className = 'sim-notas-box';
            notasBox.innerHTML = `
                <div class="sim-notas-titulo">
                    <i class="bi bi-shield-check"></i> Condiciones y observaciones de la simulación
                </div>
                <ul class="sim-notas-lista">
                    ${todosAvisos.map(t => `<li><i class="bi bi-check2"></i><span>${t}</span></li>`).join('')}
                </ul>
            `;
            wrapper.appendChild(notasBox);
        }

        resultado.appendChild(wrapper);

        // Control de visibilidad de botones de exportación
        const btnImprimir = document.getElementById('simImprimir');
        const btnVerPlan = document.getElementById('simVerPlan');
        if (btnImprimir) {
            btnImprimir.hidden = !data.plan;
            if (data.plan) btnImprimir.href = data.imprimir_url;
        }
        if (btnVerPlan) {
            btnVerPlan.hidden = !data.plan;
        }
    }

    async function avanzar() {
        if (ocupado || !validarPaso()) return;
        if (paso === 0) {
            mostrar(1);
            return;
        }
        if (paso === 1) {
            ocupado = true;
            siguiente.disabled = true;
            error('');
            const currentVersion = version;
            try {
                const data = await post(form.action, new FormData(form));
                if (currentVersion !== version) return;
                estimacion = data;
                presentar(data);
                mostrar(2);
            } catch (e) {
                error(e.message);
            } finally {
                ocupado = false;
                siguiente.disabled = false;
            }
            return;
        }
    }

    form.addEventListener('input', invalidar);
    form.addEventListener('change', () => { invalidar(); condicionales(); });
    siguiente.addEventListener('click', avanzar);
    anterior.addEventListener('click', () => {
        if (!ocupado) mostrar(Math.max(0, paso - 1));
    });

    document.getElementById('simVerPlan')?.addEventListener('click', () => {
        const container = document.getElementById('simPlan');
        const btn = document.getElementById('simVerPlan');
        if (!estimacion?.plan) return;
        if (!container.hidden) {
            container.hidden = true;
            btn.innerHTML = '<i class="bi bi-table"></i> Ver plan de pagos';
            return;
        }
        container.replaceChildren();
        const wrap = document.createElement('div');
        wrap.className = 'sim-tabla';
        const table = document.createElement('table');
        const head = table.createTHead().insertRow();
        ['Cuota', 'Fecha tentativa', 'Capital', 'Interés', 'Cargos', 'Total', 'Saldo'].forEach(t => {
            const th = document.createElement('th');
            th.textContent = t;
            head.append(th);
        });
        const body = table.createTBody();
        estimacion.plan.forEach(f => {
            const row = body.insertRow();
            [f.numero, f.fecha || 'Sin fecha', ...['capital', 'interes', 'cargos', 'cuota', 'saldo'].map(k => money(f[k]))].forEach(t => row.insertCell().textContent = t);
        });
        wrap.append(table);
        container.append(wrap);
        container.hidden = false;
        btn.innerHTML = '<i class="bi bi-eye-slash"></i> Ocultar plan de pagos';
        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    function cerrar() {
        if (ocupado) return;
        ocultarTooltip();
        modal.classList.remove('open');
        modal.style.visibility = 'hidden';
        modal.style.opacity = '0';
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = overflowPrevio;
        focoPrevio?.focus();
    }

    abrir.addEventListener('click', () => {
        focoPrevio = document.activeElement;
        overflowPrevio = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        modal.classList.add('open');
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        modal.setAttribute('aria-hidden', 'false');
        mostrar(paso);
    });

    document.getElementById('cerrarSimulador').addEventListener('click', cerrar);
    modal.addEventListener('click', e => { if (e.target === modal) cerrar(); });
    modal.addEventListener('keydown', e => {
        if (e.key === 'Escape') cerrar();
        if (e.key === 'Tab') {
            const focusables = [...modal.querySelectorAll('button,a[href],input,select,summary,[tabindex="0"]')].filter(el => !el.disabled && el.getClientRects().length);
            const first = focusables[0], last = focusables[focusables.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }
    });

    condicionales();
})();
