@php($d=$datosSim ?? [])
<div class="simulador-grid">
<label id="tour-grupo-liquido">Líquido pagable de tu boleta (Bs)
    <input type="number" name="liquido_pagable" id="sim_liquido_pagable" value="{{ $d['liquido_pagable']??'' }}" min="0" max="1000000" step=".01" placeholder="Ej. 4500" required
           data-ayuda-titulo="Líquido pagable oficial"
           data-ayuda-desc="Ingresa el importe líquido percibido que figura en tu boleta oficial de pago, descontando préstamos y retenciones previas.">
</label>
<label id="tour-grupo-situacion">Situación de servicio
    <select name="situacion" id="sim_situacion" required
            data-ayuda-titulo="Estado del afiliado"
            data-ayuda-desc="Personal en servicio activo o reserva activa. Los socios en jubilación/pasivo requieren evaluación particular de DAARO.">
        <option value="ACTIVO" @selected(($d['situacion']??'')==='ACTIVO')>Activo / reserva activa</option>
        <option value="PASIVO" @selected(($d['situacion']??'')==='PASIVO')>En jubilación / pasivo</option>
    </select>
</label>
<label id="tour-grupo-anios_servicio">Años completos de servicio
    <input type="number" name="anios_servicio" id="sim_anios_servicio" value="{{ $d['anios_servicio']??'' }}" min="0" max="60" placeholder="0" required
           data-ayuda-titulo="Años de carrera militar"
           data-ayuda-desc="Tus años de servicio cumplidos. El plazo del crédito no puede exceder el tiempo restante para alcanzar el tope de 35 años (420 meses).">
</label>
<label id="tour-grupo-meses_servicio">Meses adicionales de servicio
    <input type="number" name="meses_servicio" id="sim_meses_servicio" value="{{ $d['meses_servicio']??'' }}" min="0" max="11" placeholder="0" required
           data-ayuda-titulo="Meses adicionales"
           data-ayuda-desc="Fracción de meses adicionales al último año cumplido de servicio (entre 0 y 11 meses).">
</label>
<label id="tour-grupo-socio_habilitado">¿Eres socio habilitado con aportes activos?
    <select name="socio_habilitado" id="sim_socio_habilitado" required
            data-ayuda-titulo="Afiliación y aportes"
            data-ayuda-desc="Para acceder a cualquier modalidad debes encontrarte como socio activo y al día con tus aportes gremiales en ASCINALSS.">
        <option value="">Selecciona</option>
        <option value="1" @selected(isset($d['socio_habilitado']) && $d['socio_habilitado']==1)>Sí</option>
        <option value="0" @selected(isset($d['socio_habilitado']) && $d['socio_habilitado']==0)>No</option>
    </select>
</label>
<label id="tour-grupo-mora">¿Tienes cuotas vencidas como titular o garante?
    <select name="mora" id="sim_mora" required
            data-ayuda-titulo="Historial de morosidad"
            data-ayuda-desc="No debes registrar cuotas en mora, ya sea en tus propios préstamos o como garante de otro camarada.">
        <option value="">Selecciona</option>
        <option value="1" @selected(isset($d['mora']) && $d['mora']==1)>Sí</option>
        <option value="0" @selected(isset($d['mora']) && $d['mora']==0)>No</option>
    </select>
</label>
<label data-productos="REG_SIN EMERG INIC" id="tour-grupo-aportes">Número de aportes a ASCINALSS
    <input type="number" name="aportes" id="sim_aportes" value="{{ $d['aportes']??'' }}" min="0" max="720" placeholder="Ej. 72" required
           data-ayuda-titulo="Aportes institucionales"
           data-ayuda-desc="Para préstamos Sin Garantes se requieren al menos 61 aportes; para Emergencia mínimo 6 aportes continuos.">
</label>
<label data-productos="EMERG" id="tour-grupo-aportes_continuos">¿Tus últimos seis aportes fueron continuos?
    <select name="aportes_continuos" id="sim_aportes_continuos" required
            data-ayuda-titulo="Continuidad de aportes"
            data-ayuda-desc="El Préstamo de Emergencia exige que los 6 aportes previos hayan sido realizados de forma ininterrumpida.">
        <option value="">Selecciona</option>
        <option value="1" @selected(isset($d['aportes_continuos']) && $d['aportes_continuos']==1)>Sí</option>
        <option value="0" @selected(isset($d['aportes_continuos']) && $d['aportes_continuos']==0)>No</option>
    </select>
</label>
<label data-productos="INIC" id="tour-grupo-recien_egresado">¿Eres sargento recién egresado y estás en tu primer año como asociado?
    <select name="recien_egresado" id="sim_recien_egresado" required
            data-ayuda-titulo="Requisito de Iniciación"
            data-ayuda-desc="Modalidad exclusiva para sargentos recién egresados durante su primer año de incorporación a la institución.">
        <option value="">Selecciona</option>
        <option value="1" @selected(isset($d['recien_egresado']) && $d['recien_egresado']==1)>Sí</option>
        <option value="0" @selected(isset($d['recien_egresado']) && $d['recien_egresado']==0)>No</option>
    </select>
</label>
<label data-productos="REG_GAR" id="tour-grupo-dos_garantes">¿Cuentas con dos socios activos que puedan ser tus garantes?
    <select name="dos_garantes" id="sim_dos_garantes" required
            data-ayuda-titulo="Garantes requeridos"
            data-ayuda-desc="La modalidad Regular con Garantes requiere dos socios en servicio activo con capacidad crediticia disponible.">
        <option value="">Selecciona</option>
        <option value="1" @selected(isset($d['dos_garantes']) && $d['dos_garantes']==1)>Sí</option>
        <option value="0" @selected(isset($d['dos_garantes']) && $d['dos_garantes']==0)>No</option>
    </select>
</label>
<label id="tour-grupo-operacion">Tipo de operación
    <select name="operacion" id="sim_operacion" required
            data-ayuda-titulo="Tipo de trámite"
            data-ayuda-desc="Selecciona 'Préstamo nuevo' o 'Refinanciamiento' si vas a cancelar obligaciones pendientes para liberar cuota.">
        <option value="NUEVO" @selected(($d['operacion']??'')==='NUEVO')>Préstamo nuevo / adicional</option>
        <option value="REFINANCIAMIENTO" @selected(($d['operacion']??'')==='REFINANCIAMIENTO')>Refinanciamiento</option>
    </select>
</label>
<label id="tour-grupo-saldo-modalidad">Saldo vigente de esta modalidad (Bs; 0 si no tienes)
    <input type="number" name="saldo_modalidad" id="sim_saldo_modalidad" value="{{ $d['saldo_modalidad']??'' }}" min="0" max="10000000" step=".01" placeholder="0" required
           data-ayuda-titulo="Saldo pendiente actual"
           data-ayuda-desc="Si tienes un saldo no consolidado en esta misma modalidad, indícalo aquí (o 0 si no tienes deuda).">
</label>
<label data-refinanciamiento id="tour-grupo-saldo-cancelar">Total para cancelar todos los préstamos a consolidar (Bs)
    <input type="number" name="saldo_cancelar" id="sim_saldo_cancelar" value="{{ $d['saldo_cancelar']??'' }}" min="0" max="10000000" step=".01" placeholder="0" required
           data-ayuda-titulo="Monto para cancelación"
           data-ayuda-desc="Monto total necesario para cancelar y extinguir las deudas anteriores que deseas consolidar.">
</label>
<label data-refinanciamiento id="tour-grupo-cuota-liberada">Cuotas de esos préstamos ya descontadas en tu boleta que se liberarán (Bs)
    <input type="number" name="cuota_liberada" id="sim_cuota_liberada" value="{{ $d['cuota_liberada']??'' }}" min="0" max="1000000" step=".01" placeholder="0" required
           data-ayuda-titulo="Cuota mensual liberada"
           data-ayuda-desc="Importe mensual que actualmente te descuentan y que se sumará a tu capacidad disponible una vez cancelado el préstamo anterior.">
</label>
<label data-productos="EMERG" id="tour-grupo-emergencias-mes">Préstamos de Emergencia obtenidos este mes
    <input type="number" name="emergencias_mes" id="sim_emergencias_mes" value="{{ $d['emergencias_mes']??'' }}" min="0" max="100" placeholder="0" required
           data-ayuda-titulo="Frecuencia mensual de Emergencia"
           data-ayuda-desc="El reglamento autoriza un máximo de dos operaciones de Emergencia otorgadas en un mismo mes calendario.">
</label>
<label data-productos="EMERG" id="tour-grupo-bonos-temporales">Bonos temporales incluidos en tu líquido (Bs; 0 si no tienes)
    <input type="number" name="bonos_temporales" id="sim_bonos_temporales" value="{{ $d['bonos_temporales']??0 }}" min="0" step=".01"
           data-ayuda-titulo="Bonos y asignaciones temporales"
           data-ayuda-desc="Bonos que no son permanentes. Para préstamos que excedan la gestión actual, no se computan dentro de la capacidad de pago.">
</label>
</div>
<p>Los aportes no equivalen necesariamente a los años de servicio. Los casos especiales y las garantías serán verificados en oficina.</p>
