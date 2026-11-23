@php($politica=\App\Services\Prestamos\LoanPolicy::for($producto))
<hr><h6>Reglamento y supuestos de simulación</h6>
<div class="row g-3">
@foreach(['permitir_aproximacion'=>'Permitir aproximación francesa si el método está pendiente','usar_capacidad_referencial'=>'Usar capacidad referencial si la regla institucional está pendiente'] as $key=>$label)
<div class="col-md-6"><label class="form-label">{{ $label }}</label><select class="form-select" name="politica[{{ $key }}]"><option value="1" @selected($politica[$key])>Sí</option><option value="0" @selected(!$politica[$key])>No</option></select></div>
@endforeach
@foreach(['porcentaje_referencial'=>'Fracción del líquido (0.75 = 75%)','residual_referencial'=>'Residual referencial (Bs)','limite_servicio_meses'=>'Límite de servicio (meses)','aportes_minimos'=>'Mínimo de aportes','cargo_inicial'=>'Cargo inicial (Bs)','cargo_mensual'=>'Cargo mensual incluido (Bs)'] as $key=>$label)
<div class="col-md-4"><label class="form-label">{{ $label }}</label><input class="form-control" type="number" min="0" step="{{ in_array($key,['limite_servicio_meses','aportes_minimos'])?'1':'.01' }}" name="politica[{{ $key }}]" value="{{ $politica[$key] }}" required></div>
@endforeach
<div class="col-12"><label class="form-label">Fuente o resolución de respaldo</label><input class="form-control" name="politica[fuente]" value="{{ $politica['fuente'] }}" required maxlength="500"></div>
<div class="col-12"><label class="form-label">Cargos no incluidos o pendientes</label><textarea class="form-control" name="politica[cargos_pendientes]" maxlength="1000">{{ $politica['cargos_pendientes'] }}</textarea></div>
<div class="col-12" data-escalas><label class="form-label">Escalas por aportes</label>
<input type="hidden" name="escalas_json" value="{{ json_encode($politica['escalas']) }}">
<table class="table table-sm"><thead><tr><th>Desde aporte</th><th>Hasta aporte</th><th>Monto máximo (Bs)</th><th></th></tr></thead><tbody>
@foreach($politica['escalas'] as $tramo)<tr>@foreach($tramo as $i=>$valor)<td><input type="number" class="form-control" min="{{ $i===2?'0.01':'0' }}" step="{{ $i===2?'0.01':'1' }}" value="{{ $valor }}" required aria-label="{{ ['Desde aporte','Hasta aporte','Monto máximo'][$i] }}"></td>@endforeach<td><button type="button" class="btn btn-outline-danger btn-sm" data-quitar>Quitar</button></td></tr>@endforeach
</tbody></table><button type="button" class="btn btn-outline-dark btn-sm" data-agregar>Agregar tramo</button>
<p class="small mt-2">Ordena los tramos sin solaparlos. Los casos fuera de escala se derivan a evaluación particular.</p></div>
</div>
@once
@push('scripts')
<script>
document.querySelectorAll('[data-escalas]').forEach(root=>{
 const body=root.querySelector('tbody'),hidden=root.querySelector('[name="escalas_json"]');
 function sync(){hidden.value=JSON.stringify([...body.rows].map(row=>[...row.querySelectorAll('input')].map(input=>Number(input.value))));}
 root.addEventListener('input',sync);
 root.addEventListener('click',e=>{if(e.target.matches('[data-quitar]')){e.target.closest('tr').remove();sync();}});
 root.querySelector('[data-agregar]').addEventListener('click',()=>{const row=body.insertRow();['Desde aporte','Hasta aporte','Monto máximo'].forEach((label,i)=>{const input=document.createElement('input');input.type='number';input.min=i===2?'.01':'0';input.step=i===2?'.01':'1';input.className='form-control';input.required=true;input.setAttribute('aria-label',label);row.insertCell().append(input);});const button=document.createElement('button');button.type='button';button.className='btn btn-outline-danger btn-sm';button.dataset.quitar='';button.textContent='Quitar';row.insertCell().append(button);sync();row.querySelector('input').focus();});
});
</script>
@endpush
@endonce
