@php($d=$datosSim ?? [])
<div class="simulador-grid">
<label id="tour-grupo-producto">Tipo de préstamo
    <select name="producto" id="sim_producto" required
            data-ayuda-titulo="Tipo de préstamo"
            data-ayuda-desc="Elige la modalidad que necesitas: Regular (con o sin garantes), Emergencia (hasta 60 meses) o Iniciación (para recién egresados).">
        @foreach($productos as $prod)
            <option value="{{ $prod->pro_codigo }}"
                    data-min="{{ (float)($prod->pro_monto_minimo ?? ($prod->pro_codigo==='EMERG' ? 300 : 1000)) }}"
                    data-max="{{ (float)$prod->pro_monto_maximo }}"
                    data-plazo-max="{{ (int)$prod->pro_plazo_maximo_meses }}"
                    @selected(($d['producto']??'')===$prod->pro_codigo)>
                {{ $prod->pro_nombre }} (hasta Bs {{ number_format($prod->pro_monto_maximo, 0, ',', '.') }})
            </option>
        @endforeach
    </select>
</label>
<label id="tour-grupo-monto">Monto solicitado (Bs)
    <input type="number" name="monto_solicitado" id="sim_monto_solicitado" value="{{ $d['monto_solicitado']??'' }}" min="1" max="10000000" step=".01" placeholder="Ej. 25000" required
           data-ayuda-titulo="Monto del préstamo"
           data-ayuda-desc="Ingresa el monto que necesitas. Se evaluará contra el tope de la modalidad elegida y tu capacidad de pago según boleta.">
    <small id="sim_monto_limites" class="sim-limite-texto" style="font-size: 11.5px; color: #c9a15a; margin-top: 2px; font-weight: 500;"></small>
</label>
<label id="tour-grupo-plazo">Plazo (meses)
    <input type="number" name="plazo" id="sim_plazo" value="{{ $d['plazo']??12 }}" min="1" max="180" placeholder="Ej. 24" required
           data-ayuda-titulo="Plazo de amortización"
           data-ayuda-desc="Cantidad de meses para pagar. Los préstamos regulares permiten hasta 180 meses (15 años) y Emergencia hasta 60 meses (5 años).">
    <small id="sim_plazo_limites" class="sim-limite-texto" style="font-size: 11.5px; color: #94a3b8; margin-top: 2px;"></small>
</label>
<label id="tour-grupo-fecha">Fecha tentativa de desembolso (opcional)
    <input type="date" name="fecha_inicio" id="sim_fecha_inicio" value="{{ $d['fecha_inicio']??'' }}" min="{{ now()->toDateString() }}" max="{{ now()->addYear()->toDateString() }}"
           data-ayuda-titulo="Fecha estimada de inicio"
           data-ayuda-desc="Opcional. Permite proyectar el calendario de cuotas mes a mes con fechas de vencimiento tentativas.">
</label>
</div>
<p>Los límites personales se verificarán con tus condiciones. Las fechas del plan son tentativas.</p>
