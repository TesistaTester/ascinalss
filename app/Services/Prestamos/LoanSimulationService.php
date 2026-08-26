<?php

namespace App\Services\Prestamos;

use App\Models\ProductoSimulador;

class LoanSimulationService
{
    public function __construct(
        private LoanAmortizationService $amortizacion,
        private LoanCapacityService $capacidad
    ) {}

    public function simular(ProductoSimulador $producto, array $datos): array
    {
        $parametro = $producto->parametro;
        $regla = $producto->reglaCapacidad;
        if (!$producto->pro_activo) return $this->resultado('PRODUCTO_NO_DISPONIBLE', 'Esta modalidad no se encuentra disponible.');
        if ($producto->pro_monto_minimo !== null && (float) $datos['monto_solicitado'] < (float) $producto->pro_monto_minimo) {
            return $this->resultado('REGLA_PENDIENTE', 'El monto solicitado es inferior al mínimo de esta modalidad.');
        }
        if ((float) $datos['monto_solicitado'] > (float) $producto->pro_monto_maximo) {
            return $this->resultado('SUPERA_MONTO_PRODUCTO', 'El monto solicitado supera el máximo de esta modalidad.');
        }
        if ($producto->pro_plazo_minimo_meses !== null && (int) $datos['plazo'] < (int) $producto->pro_plazo_minimo_meses) {
            return $this->resultado('SUPERA_PLAZO_PRODUCTO', 'El plazo solicitado es inferior al mínimo de esta modalidad.');
        }
        if ((int) $datos['plazo'] > (int) $producto->pro_plazo_maximo_meses) {
            return $this->resultado('SUPERA_PLAZO_PRODUCTO', 'El plazo solicitado supera el máximo de esta modalidad.');
        }
        if (!$parametro || $parametro->par_estado !== 'CONFIRMADO') {
            return $this->resultado('REGLA_PENDIENTE', 'La tasa o el método de cálculo de esta modalidad aún no se encuentra confirmado.');
        }
        $cuota = $this->amortizacion->calcularCuota((float) $datos['monto_solicitado'], (int) $datos['plazo'], $parametro);
        if ($cuota === null) return $this->resultado('REGLA_PENDIENTE', 'No existe información suficiente para calcular la cuota con precisión.');
        $capacidad = ($regla && $regla->reg_estado === 'CONFIRMADO')
            ? $this->capacidad->calcular((float) $datos['liquido_pagable'], $regla) : null;
        $montoFinanciero = null;
        if ($capacidad !== null && $parametro->par_metodo_amortizacion === 'FRANCES') {
            $i = $this->amortizacion->tasaPeriodica($parametro); $n = (int) $datos['plazo'];
            $montoFinanciero = $i == 0 ? $capacidad * $n : $capacidad * ((pow(1 + $i, $n) - 1) / ($i * pow(1 + $i, $n)));
        }
        $montoMaximo = $montoFinanciero === null ? (float) $producto->pro_monto_maximo : min($montoFinanciero, (float) $producto->pro_monto_maximo);
        $estado = $capacidad === null ? 'REGLA_PENDIENTE' : ($cuota <= $capacidad ? 'COMPATIBLE' : 'SUPERA_CAPACIDAD');
        $mensaje = match ($estado) {
            'COMPATIBLE' => 'El monto solicitado se encuentra dentro de tu capacidad estimada.',
            'SUPERA_CAPACIDAD' => 'La cuota estimada supera tu capacidad de pago.',
            default => 'La cuota fue estimada, pero faltan reglas confirmadas para evaluar tu capacidad de pago.',
        };
        return $this->resultado($estado, $mensaje, [
            'monto_solicitado' => round((float) $datos['monto_solicitado'], 2), 'plazo' => (int) $datos['plazo'],
            'cuota_mensual' => round($cuota, 2), 'cuota_maxima_estimada' => $capacidad === null ? null : round($capacidad, 2),
            'monto_maximo_estimado' => round($montoMaximo, 2),
        ]);
    }

    public function buscarPlazoMinimo(ProductoSimulador $producto, float $monto, float $capacidadPago): ?int
    {
        if (!$producto->parametro || $producto->parametro->par_estado !== 'CONFIRMADO') return null;
        $desde = (int) ($producto->pro_plazo_minimo_meses ?? 1);
        $hasta = (int) $producto->pro_plazo_maximo_meses;
        for ($plazo = $desde; $plazo <= $hasta; $plazo++) {
            $cuota = $this->amortizacion->calcularCuota($monto, $plazo, $producto->parametro);
            if ($cuota !== null && $cuota <= $capacidadPago) return $plazo;
        }
        return null;
    }

    private function resultado(string $estado, string $mensaje, array $datos = []): array
    {
        return array_merge(['estado' => $estado, 'mensaje' => $mensaje], $datos);
    }
}
