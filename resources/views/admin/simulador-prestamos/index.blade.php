@extends('layouts.admin')
@section('titulo', 'Simulador de Préstamos')
@section('breadcrumb')<li class="breadcrumb-item active">Configuración</li>@endsection

@section('contenido')
<div class="alert alert-warning"><i class="bi bi-shield-exclamation me-1"></i> Confirma únicamente condiciones validadas por ASCINALSS. Los valores pendientes no producirán estimaciones falsas.</div>
<div class="accordion" id="productosSimulador">
@foreach($productos as $producto)
@php($par = $producto->parametro) @php($reg = $producto->reglaCapacidad)
<div class="accordion-item border-0 panel-card mb-3 overflow-hidden">
 <h2 class="accordion-header"><button class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}" data-bs-toggle="collapse" data-bs-target="#producto-{{ $producto->pro_id }}"><strong>{{ $producto->pro_nombre }}</strong><span class="badge bg-secondary ms-2">{{ $producto->pro_codigo }}</span></button></h2>
 <div id="producto-{{ $producto->pro_id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#productosSimulador"><div class="accordion-body p-4">
 <form method="POST" action="{{ route('simulador-prestamos.update', $producto) }}">@csrf @method('PUT')
  <h6 class="text-uppercase text-muted mb-3">Producto</h6>
  <div class="row g-3">
   <div class="col-md-6"><label class="form-label">Nombre</label><input class="form-control" name="pro_nombre" value="{{ $producto->pro_nombre }}" required></div>
   <div class="col-md-2"><label class="form-label">Moneda</label><input class="form-control" name="pro_moneda" value="{{ $producto->pro_moneda }}" maxlength="3" required></div>
   <div class="col-md-2"><label class="form-label">Monto mínimo</label><input type="number" step=".01" class="form-control" name="pro_monto_minimo" value="{{ $producto->pro_monto_minimo }}"></div>
   <div class="col-md-2"><label class="form-label">Monto máximo</label><input type="number" step=".01" class="form-control" name="pro_monto_maximo" value="{{ $producto->pro_monto_maximo }}" required></div>
   <div class="col-md-3"><label class="form-label">Plazo mínimo</label><input type="number" class="form-control" name="pro_plazo_minimo_meses" value="{{ $producto->pro_plazo_minimo_meses }}"></div>
   <div class="col-md-3"><label class="form-label">Plazo máximo</label><input type="number" class="form-control" name="pro_plazo_maximo_meses" value="{{ $producto->pro_plazo_maximo_meses }}" required></div>
   <div class="col-md-3"><label class="form-label">Vigente desde</label><input type="date" class="form-control" name="pro_vigente_desde" value="{{ optional($producto->pro_vigente_desde)->format('Y-m-d') }}"></div>
   <div class="col-md-3"><label class="form-label">Vigente hasta</label><input type="date" class="form-control" name="pro_vigente_hasta" value="{{ optional($producto->pro_vigente_hasta)->format('Y-m-d') }}"></div>
   <div class="col-md-3"><label class="form-check mt-4"><input type="checkbox" class="form-check-input me-2" name="pro_activo" value="1" @checked($producto->pro_activo)>Activo</label></div>
   <div class="col-md-3"><label class="form-check mt-4"><input type="checkbox" class="form-check-input me-2" name="pro_requiere_garantes" value="1" @checked($producto->pro_requiere_garantes)>Requiere garantes</label></div>
   <div class="col-md-3"><label class="form-label">Cantidad garantes</label><input type="number" class="form-control" name="pro_cantidad_garantes" value="{{ $producto->pro_cantidad_garantes }}"></div>
   <div class="col-md-3"><label class="form-check mt-4"><input type="checkbox" class="form-check-input me-2" name="pro_considera_antiguedad" value="1" @checked($producto->pro_considera_antiguedad)>Considera antigüedad</label></div>
  </div>
  <hr class="my-4"><h6 class="text-uppercase text-muted mb-3">Condiciones financieras</h6>
  <div class="row g-3">
   <div class="col-md-2"><label class="form-label">Tasa (%)</label><input type="number" step=".000001" class="form-control" name="par_tasa" value="{{ $par?->par_tasa }}"></div>
   <div class="col-md-3"><label class="form-label">Tipo de tasa</label><select class="form-select" name="par_tipo_tasa"><option value="">Pendiente</option>@foreach(['ANUAL','ANUAL_NOMINAL','ANUAL_EFECTIVA','MENSUAL','PERSONALIZADA'] as $v)<option @selected($par?->par_tipo_tasa === $v)>{{ $v }}</option>@endforeach</select></div>
   <div class="col-md-3"><label class="form-label">Método</label><select class="form-select" name="par_metodo_amortizacion" required>@foreach(['PENDIENTE','FRANCES','AMORTIZACION_CONSTANTE','INTERES_PLANO','PERSONALIZADO'] as $v)<option @selected(($par?->par_metodo_amortizacion ?? 'PENDIENTE') === $v)>{{ $v }}</option>@endforeach</select></div>
   <div class="col-md-2"><label class="form-label">Base interés</label><select class="form-select" name="par_base_interes"><option value="">Pendiente</option>@foreach(['CAPITAL','SALDO','PERSONALIZADA'] as $v)<option @selected($par?->par_base_interes === $v)>{{ $v }}</option>@endforeach</select></div>
   <div class="col-md-2"><label class="form-label">Estado</label><select class="form-select" name="par_estado" required>@foreach(['PENDIENTE','CONFIRMADO','INFERIDO','HISTORICO'] as $v)<option @selected(($par?->par_estado ?? 'PENDIENTE') === $v)>{{ $v }}</option>@endforeach</select></div>
   <input type="hidden" name="par_frecuencia_pago" value="MENSUAL">
  </div>
  <hr class="my-4"><h6 class="text-uppercase text-muted mb-3">Capacidad de endeudamiento</h6>
  <div class="row g-3">
   <div class="col-md-4"><label class="form-check"><input type="checkbox" class="form-check-input me-2" name="reg_usar_porcentaje_maximo" value="1" @checked($reg?->reg_usar_porcentaje_maximo)>Usar porcentaje máximo</label><input type="number" step=".000001" min="0" max="1" class="form-control mt-2" name="reg_porcentaje_maximo_liquido" value="{{ $reg?->reg_porcentaje_maximo_liquido }}" placeholder="Ej. 0.30"></div>
   <div class="col-md-4"><label class="form-check"><input type="checkbox" class="form-check-input me-2" name="reg_usar_liquido_minimo" value="1" @checked($reg?->reg_usar_liquido_minimo)>Usar líquido mínimo</label><input type="number" step=".01" min="0" class="form-control mt-2" name="reg_liquido_minimo_residual" value="{{ $reg?->reg_liquido_minimo_residual }}"></div>
   <div class="col-md-4"><label class="form-check"><input type="checkbox" class="form-check-input me-2" name="reg_usar_factor_seguridad" value="1" @checked($reg?->reg_usar_factor_seguridad)>Usar factor de seguridad</label><input type="number" step=".000001" min="0" max="1" class="form-control mt-2" name="reg_factor_seguridad" value="{{ $reg?->reg_factor_seguridad }}"></div>
   <div class="col-md-4"><label class="form-check"><input type="checkbox" class="form-check-input me-2" name="reg_considerar_refinanciamiento" value="1" @checked($reg?->reg_considerar_refinanciamiento)>Considerar refinanciamiento</label></div>
   <div class="col-md-4"><label class="form-check"><input type="checkbox" class="form-check-input me-2" name="reg_liberar_cuota_refinanciada" value="1" @checked($reg?->reg_liberar_cuota_refinanciada)>Liberar cuota refinanciada</label></div>
   <div class="col-md-4"><label class="form-label">Estado de reglas</label><select class="form-select" name="reg_estado" required>@foreach(['PENDIENTE','CONFIRMADO','INFERIDO','HISTORICO'] as $v)<option @selected(($reg?->reg_estado ?? 'PENDIENTE') === $v)>{{ $v }}</option>@endforeach</select></div>
  </div>
  <div class="text-end mt-4"><button class="btn btn-dark"><i class="bi bi-check2-circle me-1"></i>Guardar configuración</button></div>
 </form>
 </div></div>
</div>
@endforeach
</div>
@endsection
