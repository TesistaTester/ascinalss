<?php

namespace App\Services\Prestamos\Amortizacion;

class FlatInterestStrategy implements AmortizationStrategyInterface
{
    public function calcularCuota(float $capital, float $tasaPeriodica, int $cuotas): float
    {
        return ($capital + ($capital * $tasaPeriodica * $cuotas)) / $cuotas;
    }

    public function calcularPlan(float $capital, float $tasaPeriodica, int $cuotas): array
    {
        $interes = $capital * $tasaPeriodica; $amortizacion = $capital / $cuotas;
        $cuota = $interes + $amortizacion; $saldo = $capital; $plan = [];
        for ($numero = 1; $numero <= $cuotas; $numero++) {
            $saldoFinal = max(0, $saldo - $amortizacion);
            $plan[] = compact('numero', 'saldo', 'interes', 'amortizacion', 'cuota', 'saldoFinal');
            $saldo = $saldoFinal;
        }
        return $plan;
    }
}
