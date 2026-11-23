@if($productosSimulador->isNotEmpty())
<div class="modal-overlay simulador-overlay" id="modalSimulador" aria-hidden="true">
<div class="modal-box simulador-modal" role="dialog" aria-modal="true" aria-labelledby="simTitulo">
<button type="button" class="cerrar" id="cerrarSimulador" aria-label="Cerrar">×</button>
<h3 id="simTitulo">Calcula tu préstamo</h3>
<ol class="sim-pasos" aria-label="Progreso"><li aria-current="step">1. Tu préstamo</li><li>2. Tus condiciones</li><li>3. Estimación</li></ol>
<form id="formSimulador" action="{{ route('prestamos.simular') }}" novalidate>
@csrf
<fieldset data-paso="0"><legend tabindex="-1">Tu préstamo</legend>@include('prestamos._prestamo',['productos'=>$productosSimulador])</fieldset>
<fieldset data-paso="1" hidden><legend tabindex="-1">Tus condiciones</legend>@include('prestamos._condiciones_form')</fieldset>
</form>
<section data-paso="2" hidden>
<h4 tabindex="-1">Resultado de tu estimación</h4>
<div id="simResultado" aria-live="polite"></div>
<div class="sim-acciones sim-acciones-estimacion">
    <button type="button" id="simVerPlan" class="btn-sim-secundario"><i class="bi bi-table"></i> Ver plan de pagos</button>
    <a id="simImprimir" target="_blank" rel="noopener" class="btn-sim-primario"><i class="bi bi-printer"></i> Imprimir / Guardar PDF</a>
</div>
<div id="simPlan" hidden></div>
</section>
<div id="simErrores" class="simulador-errores" role="alert" hidden></div>
<div class="sim-acciones"><button type="button" id="simAnterior" hidden>Anterior</button><button type="button" id="simSiguiente">Continuar</button></div>
<details class="sim-condiciones"><summary>Condiciones de uso y política institucional</summary><p>{{ \App\Services\Prestamos\LoanPolicy::CONDICIONES }}</p></details>
</div></div>
@endif
