<?php

namespace App\Services\Prestamos;

use App\Models\ReglaCapacidadPrestamo;

class LoanCapacityService
{
    public function calcular(float $liquido, ReglaCapacidadPrestamo $regla, float $cuotaLiberada = 0): ?float
    {
        $liquidoAjustado = $liquido;
        if ($regla->reg_considerar_refinanciamiento && $regla->reg_liberar_cuota_refinanciada) {
            $liquidoAjustado += $cuotaLiberada;
        }
        $limites = [];
        if ($regla->reg_usar_porcentaje_maximo) {
            if ($regla->reg_porcentaje_maximo_liquido === null) return null;
            $limites[] = $liquidoAjustado * (float) $regla->reg_porcentaje_maximo_liquido;
        }
        if ($regla->reg_usar_liquido_minimo) {
            if ($regla->reg_liquido_minimo_residual === null) return null;
            $limites[] = $liquidoAjustado - (float) $regla->reg_liquido_minimo_residual;
        }
        if (!$limites) return null;
        $capacidad = max(0, min($limites));
        if ($regla->reg_usar_factor_seguridad) {
            if ($regla->reg_factor_seguridad === null) return null;
            $capacidad *= (float) $regla->reg_factor_seguridad;
        }
        return $capacidad;
    }
}
