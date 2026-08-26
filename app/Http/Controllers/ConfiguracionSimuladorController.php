<?php

namespace App\Http\Controllers;

use App\Models\HistorialCondicionPrestamo;
use App\Models\ParametroFinanciero;
use App\Models\ProductoSimulador;
use App\Models\ReglaCapacidadPrestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracionSimuladorController extends Controller
{
    public function index()
    {
        $productos = ProductoSimulador::with(['parametro', 'reglaCapacidad'])->orderBy('pro_id')->get();
        return view('admin.simulador-prestamos.index', compact('productos'));
    }

    public function update(Request $request, ProductoSimulador $producto)
    {
        $datos = $request->validate([
            'pro_nombre' => 'required|string|max:255', 'pro_monto_minimo' => 'nullable|numeric|min:0',
            'pro_monto_maximo' => 'required|numeric|min:0.01', 'pro_moneda' => 'required|string|size:3',
            'pro_plazo_minimo_meses' => 'nullable|integer|min:1', 'pro_plazo_maximo_meses' => 'required|integer|min:1',
            'pro_cantidad_garantes' => 'nullable|integer|min:0|max:10', 'pro_vigente_desde' => 'nullable|date',
            'pro_vigente_hasta' => 'nullable|date|after_or_equal:pro_vigente_desde',
            'par_tasa' => 'nullable|numeric|min:0', 'par_tipo_tasa' => 'nullable|in:ANUAL,ANUAL_NOMINAL,ANUAL_EFECTIVA,MENSUAL,PERSONALIZADA',
            'par_base_interes' => 'nullable|in:CAPITAL,SALDO,PERSONALIZADA',
            'par_metodo_amortizacion' => 'required|in:FRANCES,AMORTIZACION_CONSTANTE,INTERES_PLANO,PERSONALIZADO,PENDIENTE',
            'par_frecuencia_pago' => 'required|in:MENSUAL', 'par_estado' => 'required|in:CONFIRMADO,INFERIDO,PENDIENTE,HISTORICO',
            'reg_porcentaje_maximo_liquido' => 'nullable|numeric|min:0|max:1', 'reg_liquido_minimo_residual' => 'nullable|numeric|min:0',
            'reg_factor_seguridad' => 'nullable|numeric|min:0|max:1', 'reg_estado' => 'required|in:CONFIRMADO,INFERIDO,PENDIENTE,HISTORICO',
        ]);

        DB::transaction(function () use ($request, $producto, $datos) {
            $camposProducto = array_filter($datos, fn ($key) => str_starts_with($key, 'pro_'), ARRAY_FILTER_USE_KEY);
            $camposProducto['pro_activo'] = $request->boolean('pro_activo');
            $camposProducto['pro_requiere_garantes'] = $request->boolean('pro_requiere_garantes');
            $camposProducto['pro_considera_antiguedad'] = $request->boolean('pro_considera_antiguedad');
            $this->registrarCambios($producto, $camposProducto, $producto->pro_id);
            $producto->update($camposProducto);

            $parametros = array_filter($datos, fn ($key) => str_starts_with($key, 'par_'), ARRAY_FILTER_USE_KEY);
            $parametro = ParametroFinanciero::firstOrNew(['par_producto_id' => $producto->pro_id]);
            $this->registrarCambios($parametro, $parametros, $producto->pro_id);
            $parametro->fill($parametros)->save();
            $reglas = array_filter($datos, fn ($key) => str_starts_with($key, 'reg_'), ARRAY_FILTER_USE_KEY);
            foreach (['reg_usar_porcentaje_maximo', 'reg_usar_liquido_minimo', 'reg_usar_factor_seguridad', 'reg_considerar_refinanciamiento', 'reg_liberar_cuota_refinanciada'] as $campo) {
                $reglas[$campo] = $request->boolean($campo);
            }
            $regla = ReglaCapacidadPrestamo::firstOrNew(['reg_producto_id' => $producto->pro_id]);
            $this->registrarCambios($regla, $reglas, $producto->pro_id);
            $regla->fill($reglas)->save();
        });
        return back()->with('exito', 'Configuración del simulador actualizada correctamente.');
    }

    private function registrarCambios($modelo, array $nuevosValores, int $productoId): void
    {
        if (!$modelo->exists) return;
        foreach ($nuevosValores as $campo => $valor) {
            if ((string) $modelo->{$campo} === (string) $valor) continue;
            HistorialCondicionPrestamo::create([
                'his_producto_id' => $productoId,
                'his_parametro' => $campo,
                'his_valor' => is_bool($modelo->{$campo}) ? (string) (int) $modelo->{$campo} : (string) $modelo->{$campo},
                'his_tipo' => 'VALOR',
                'his_fuente' => 'Cambio desde el CMS',
                'his_estado' => 'HISTORICO',
                'his_vigente_hasta' => now()->toDateString(),
            ]);
        }
    }
}
