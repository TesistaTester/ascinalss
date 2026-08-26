<?php

namespace Tests\Unit;

use App\Models\ParametroFinanciero;
use App\Services\Prestamos\LoanAmortizationService;
use PHPUnit\Framework\TestCase;

class LoanAmortizationServiceTest extends TestCase
{
    public function test_calcula_cuota_francesa_con_tasa_anual_nominal(): void
    {
        $parametro = new ParametroFinanciero(['par_tasa' => 12, 'par_tipo_tasa' => 'ANUAL_NOMINAL', 'par_metodo_amortizacion' => 'FRANCES']);
        $cuota = (new LoanAmortizationService())->calcularCuota(10000, 12, $parametro);
        $this->assertEqualsWithDelta(888.49, $cuota, 0.01);
    }

    public function test_no_calcula_metodo_pendiente(): void
    {
        $parametro = new ParametroFinanciero(['par_tasa' => 11.99, 'par_tipo_tasa' => 'ANUAL', 'par_metodo_amortizacion' => 'PENDIENTE']);
        $this->assertNull((new LoanAmortizationService())->calcularCuota(10000, 12, $parametro));
    }
}
