<?php

namespace App\Services\Prestamos\Amortizacion;

class FrenchAmortizationStrategy implements AmortizationStrategyInterface
{
    public function calcularCuota(float $capital, float $tasaPeriodica, int $cuotas): float
    {
        if ($tasaPeriodica == 0.0) return $capital / $cuotas;
        $factor = pow(1 + $tasaPeriodica, $cuotas);
        return $capital * (($tasaPeriodica * $factor) / ($factor - 1));
    }

    public function calcularPlan(float $capital, float $tasaPeriodica, int $cuotas): array
    {
        $cuota = $this->calcularCuota($capital, $tasaPeriodica, $cuotas);
        $saldo = $capital; $plan = [];
        for ($numero = 1; $numero <= $cuotas; $numero++) {
            $interes = $saldo * $tasaPeriodica;
            $amortizacion = min($saldo, $cuota - $interes);
            $saldoFinal = max(0, $saldo - $amortizacion);
            $plan[] = compact('numero', 'saldo', 'interes', 'amortizacion', 'cuota', 'saldoFinal');
            $saldo = $saldoFinal;
        }
        return $plan;
    }
}
