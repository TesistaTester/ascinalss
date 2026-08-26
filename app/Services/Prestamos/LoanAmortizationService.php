<?php

namespace App\Services\Prestamos;

use App\Models\ParametroFinanciero;
use App\Services\Prestamos\Amortizacion\ConstantAmortizationStrategy;
use App\Services\Prestamos\Amortizacion\FlatInterestStrategy;
use App\Services\Prestamos\Amortizacion\FrenchAmortizationStrategy;

class LoanAmortizationService
{
    public function calcularCuota(float $capital, int $meses, ParametroFinanciero $parametro): ?float
    {
        $estrategia = match ($parametro->par_metodo_amortizacion) {
            'FRANCES' => new FrenchAmortizationStrategy(),
            'AMORTIZACION_CONSTANTE' => new ConstantAmortizationStrategy(),
            'INTERES_PLANO' => new FlatInterestStrategy(),
            default => null,
        };
        $tasa = $this->tasaPeriodica($parametro);
        return ($estrategia && $tasa !== null) ? $estrategia->calcularCuota($capital, $tasa, $meses) : null;
    }

    public function tasaPeriodica(ParametroFinanciero $parametro): ?float
    {
        if ($parametro->par_tasa === null) return null;
        $tasa = (float) $parametro->par_tasa / 100;
        return match ($parametro->par_tipo_tasa) {
            'ANUAL', 'ANUAL_NOMINAL' => $tasa / 12,
            'ANUAL_EFECTIVA' => pow(1 + $tasa, 1 / 12) - 1,
            'MENSUAL' => $tasa,
            default => null,
        };
    }
}
