<?php

namespace App\Http\Controllers;

use App\Models\ProductoSimulador;
use App\Services\Prestamos\LoanSimulationService;
use Illuminate\Http\Request;

class SimuladorPrestamoController extends Controller
{
    public function simular(Request $request, LoanSimulationService $servicio)
    {
        $datos = $request->validate([
            'producto' => 'required|string|exists:productos_simulador,pro_codigo',
            'liquido_pagable' => 'required|numeric|min:0.01|max:99999999.99',
            'monto_solicitado' => 'required|numeric|min:0.01|max:99999999.99',
            'plazo' => 'required|integer|min:1|max:600',
            'anios_servicio' => 'nullable|integer|min:1|max:60',
        ]);
        $producto = ProductoSimulador::with(['parametro', 'reglaCapacidad'])->where('pro_codigo', $datos['producto'])->firstOrFail();
        if ($producto->pro_considera_antiguedad && empty($datos['anios_servicio'])) {
            return response()->json(['message' => 'Los años de servicio son obligatorios para esta modalidad.', 'errors' => ['anios_servicio' => ['Ingresa tus años de servicio.']]], 422);
        }
        return response()->json($servicio->simular($producto, $datos));
    }
}
