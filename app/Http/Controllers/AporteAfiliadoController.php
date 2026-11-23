<?php

namespace App\Http\Controllers;

use App\Models\AfiliadoAporteConsulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AporteAfiliadoController extends Controller
{
    /**
     * Muestra el formulario de acceso para el afiliado.
     */
    public function formularioLogin()
    {
        $sesion = session('afiliado_consulta');
        if ($sesion) {
            // Verificar expiración de sesión (10 minutos)
            if (isset($sesion['expira_en']) && now()->timestamp > $sesion['expira_en']) {
                session()->forget('afiliado_consulta');
                return view('publico.aportes.login')->with('status', 'Su sesión de consulta expiró por inactividad.');
            }
            return redirect()->route('aportes.estado-cuenta');
        }

        return view('publico.aportes.login');
    }

    /**
     * Valida el CI y Nro de Papeleta e inicia la sesión de consulta.
     */
    public function autenticar(Request $request)
    {
        // 1. Trampa Anti-Bot (Honeypot)
        abort_if(!empty($request->input('telefono_confirmacion')), 422, 'Petición inválida detectada.');

        $credentials = $request->validate([
            'ci' => ['required', 'string', 'max:20'],
            'numero_papeleta' => ['required', 'string', 'max:20'],
        ], [
            'ci.required' => 'Por favor ingrese su Cédula de Identidad.',
            'numero_papeleta.required' => 'Por favor ingrese su Número de Papeleta / Matrícula.',
        ]);

        $ci = trim($credentials['ci']);
        $papeleta = trim($credentials['numero_papeleta']);

        $afiliado = AfiliadoAporteConsulta::porAfiliado($ci, $papeleta)->first();

        if (!$afiliado) {
            Log::warning('Intento fallido de consulta de aportes', [
                'ci' => $ci,
                'papeleta' => $papeleta,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withInput()->withErrors([
                'login' => 'Los datos ingresados no coinciden con ningún registro activo de aportes. Verifique su CI y Número de Papeleta o comuníquese con secretaría de ASCINALSS.'
            ]);
        }

        // 2. Regenerar ID de sesión para prevenir Session Fixation
        $request->session()->regenerate();

        // 3. Guardar sesión de consulta con expiración a 10 minutos
        session([
            'afiliado_consulta' => [
                'ci' => $afiliado->ci,
                'papeleta' => $afiliado->numero_papeleta,
                'nombre' => $afiliado->nombre_completo,
                'fuerza' => $afiliado->fuerza,
                'grado' => $afiliado->grado,
                'hora_ingreso' => now()->format('d/m/Y H:i'),
                'expira_en' => now()->addMinutes(10)->timestamp,
            ]
        ]);

        return redirect()->route('aportes.estado-cuenta');
    }

    /**
     * Muestra el estado de cuenta y reporte de aportes del afiliado autenticado.
     */
    public function estadoCuenta()
    {
        $sesion = session('afiliado_consulta');
        if (!$sesion) {
            return redirect()->route('aportes.login');
        }

        // Verificar expiración de 10 minutos por inactividad
        if (isset($sesion['expira_en']) && now()->timestamp > $sesion['expira_en']) {
            session()->forget('afiliado_consulta');
            return redirect()->route('aportes.login')->with('status', 'Su sesión de consulta ha expirado por inactividad.');
        }

        // Renovar tiempo de expiración ante actividad
        session(['afiliado_consulta.expira_en' => now()->addMinutes(10)->timestamp]);

        $ci = $sesion['ci'];
        $papeleta = $sesion['papeleta'];

        // 1. Todos los registros ordenados cronológicamente
        $todosAportes = AfiliadoAporteConsulta::porAfiliado($ci, $papeleta)
            ->orderByDesc('anio')
            ->orderByDesc('mes')
            ->get();

        if ($todosAportes->isEmpty()) {
            session()->forget('afiliado_consulta');
            return redirect()->route('aportes.login');
        }

        // 2. Resumen anual (totales por año)
        $resumenAnual = AfiliadoAporteConsulta::porAfiliado($ci, $papeleta)
            ->selectRaw('anio, count(*) as meses, sum(monto_aporte) as total_anual, avg(total_ganado) as prom_ganado')
            ->groupBy('anio')
            ->orderByDesc('anio')
            ->get();

        // 3. Matriz Pivot Anual (Gestión en filas, meses Ene-Dic en columnas)
        $matrizPivot = [];
        $gestiones = $todosAportes->pluck('anio')->unique()->values();

        foreach ($gestiones as $anio) {
            $mesesFila = array_fill(1, 12, 0.0);
            $totalAnio = 0.0;

            foreach ($todosAportes->where('anio', $anio) as $reg) {
                $mesesFila[$reg->mes] = (float) $reg->monto_aporte;
                $totalAnio += (float) $reg->monto_aporte;
            }

            $matrizPivot[] = [
                'anio' => $anio,
                'meses' => $mesesFila,
                'total_anual' => $totalAnio,
            ];
        }

        // 4. Totales acumulados históricos
        $totalHistoricoAportado = $todosAportes->sum('monto_aporte');
        $totalMesesAportados = $todosAportes->count();
        $ultimaActualizacion = $todosAportes->first()->actualizado_al ?? now();

        return response()->view('publico.aportes.estado-cuenta', compact(
            'sesion',
            'todosAportes',
            'resumenAnual',
            'matrizPivot',
            'totalHistoricoAportado',
            'totalMesesAportados',
            'ultimaActualizacion'
        ))->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
          ->header('Pragma', 'no-cache')
          ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    /**
     * Cierra la sesión de consulta.
     */
    public function cerrarSesion()
    {
        session()->forget('afiliado_consulta');
        return redirect()->route('aportes.login')->with('status', 'Sesión de consulta cerrada correctamente.');
    }
}
