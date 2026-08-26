<?php

namespace Database\Seeders;

use App\Models\HistorialCondicionPrestamo;
use App\Models\ParametroFinanciero;
use App\Models\ProductoSimulador;
use App\Models\ReglaCapacidadPrestamo;
use Illuminate\Database\Seeder;

class ProductoSimuladorSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            ['REG_GAR', 'Regular con Garantes', 245000, 180, 11.99, 'ANUAL', true, 2, false],
            ['REG_SIN', 'Regular sin Garantes', 140000, 120, 11.99, 'ANUAL', false, null, true],
            ['EMERG', 'Emergencia', 80000, 60, null, null, false, null, false],
            ['INIC', 'Iniciación', 28000, 30, 2, 'MENSUAL', false, null, false],
        ];

        foreach ($productos as [$codigo, $nombre, $maximo, $plazo, $tasa, $tipoTasa, $garantes, $cantidad, $antiguedad]) {
            $producto = ProductoSimulador::updateOrCreate(['pro_codigo' => $codigo], [
                'pro_nombre' => $nombre, 'pro_monto_maximo' => $maximo, 'pro_moneda' => 'BOB',
                'pro_plazo_maximo_meses' => $plazo, 'pro_requiere_garantes' => $garantes,
                'pro_cantidad_garantes' => $cantidad, 'pro_considera_antiguedad' => $antiguedad, 'pro_activo' => true,
            ]);
            ParametroFinanciero::firstOrCreate(['par_producto_id' => $producto->pro_id], [
                'par_tasa' => $tasa, 'par_tipo_tasa' => $tipoTasa, 'par_base_interes' => $codigo === 'INIC' ? 'CAPITAL' : null,
                'par_metodo_amortizacion' => 'PENDIENTE', 'par_frecuencia_pago' => 'MENSUAL', 'par_estado' => 'PENDIENTE',
            ]);
            ReglaCapacidadPrestamo::firstOrCreate(['reg_producto_id' => $producto->pro_id], [
                'reg_estado' => 'PENDIENTE',
            ]);
        }

        $sinGarantes = ProductoSimulador::where('pro_codigo', 'REG_SIN')->first();
        if ($sinGarantes) {
            HistorialCondicionPrestamo::firstOrCreate([
                'his_producto_id' => $sinGarantes->pro_id, 'his_parametro' => 'escala_antiguedad_usd',
            ], [
                'his_valor' => json_encode(['1-3' => 4000, '4-6' => 6000, '7-9' => 8000, '10-15' => 10000, '16-18' => 12000, '19-25' => 15000, '26+' => null]),
                'his_tipo' => 'TABLA', 'his_fuente' => 'Documentación institucional anterior', 'his_estado' => 'HISTORICO',
            ]);
        }
    }
}
