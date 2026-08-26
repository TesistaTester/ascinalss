<?php

namespace Tests\Unit;

use App\Models\ReglaCapacidadPrestamo;
use App\Services\Prestamos\LoanCapacityService;
use PHPUnit\Framework\TestCase;

class LoanCapacityServiceTest extends TestCase
{
    public function test_aplica_limite_mas_restrictivo_y_factor(): void
    {
        $regla = new ReglaCapacidadPrestamo([
            'reg_usar_porcentaje_maximo' => true, 'reg_porcentaje_maximo_liquido' => .30,
            'reg_usar_liquido_minimo' => true, 'reg_liquido_minimo_residual' => 2500,
            'reg_usar_factor_seguridad' => true, 'reg_factor_seguridad' => .90,
        ]);
        $this->assertSame(1350.0, (new LoanCapacityService())->calcular(5000, $regla));
    }

    public function test_retorna_null_sin_reglas_configuradas(): void
    {
        $this->assertNull((new LoanCapacityService())->calcular(5000, new ReglaCapacidadPrestamo()));
    }
}
