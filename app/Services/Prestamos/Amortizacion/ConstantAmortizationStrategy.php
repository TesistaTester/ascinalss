<?php

namespace App\Services\Prestamos\Amortizacion;

class ConstantAmortizationStrategy implements AmortizationStrategyInterface
{
    public function calcularCuota(float $capital, float $tasaPeriodica, int $cuotas): float
    {
        return ($capital / $cuotas) + ($capital * $tasaPeriodica);
    }

    public function calcularPlan(float $capital, float $tasaPeriodica, int $cuotas): array
    {
        $amortizacion = $capital / $cuotas; $saldo = $capital; $plan = [];
        for ($numero = 1; $numero <= $cuotas; $numero++) {
            $interes = $saldo * $tasaPeriodica;
            $cuota = $amortizacion + $interes;
            $saldoFinal = max(0, $saldo - $amortizacion);
            $plan[] = compact('numero', 'saldo', 'interes', 'amortizacion', 'cuota', 'saldoFinal');
            $saldo = $saldoFinal;
        }
        return $plan;
    }
}
