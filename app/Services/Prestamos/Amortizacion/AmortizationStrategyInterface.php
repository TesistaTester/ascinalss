<?php

namespace App\Services\Prestamos\Amortizacion;

interface AmortizationStrategyInterface
{
    public function calcularCuota(float $capital, float $tasaPeriodica, int $cuotas): float;
    public function calcularPlan(float $capital, float $tasaPeriodica, int $cuotas): array;
}
