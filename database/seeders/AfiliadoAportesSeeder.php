<?php

namespace Database\Seeders;

use App\Models\AfiliadoAporteConsulta;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AfiliadoAportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar registros previos de prueba
        DB::table('afiliado_aportes_consulta')->truncate();

        $afiliados = [
            [
                'ci' => '4892110',
                'papeleta' => '10452',
                'nombre' => 'SOF. MTRE. CARLOS HUANCA MAMANI',
                'fuerza' => 'EJÉRCITO',
                'grado' => 'Suboficial Maestre',
                'gestiones' => [
                    2024 => ['meses' => 12, 'aporte' => 150.00, 'ganado' => 7850.00, 'liquido' => 6100.00],
                    2025 => ['meses' => 12, 'aporte' => 165.00, 'ganado' => 8250.00, 'liquido' => 6450.00],
                    2026 => ['meses' => 10, 'aporte' => 170.00, 'ganado' => 8500.00, 'liquido' => 6700.00],
                ]
            ],
            [
                'ci' => '3514208',
                'papeleta' => '23891',
                'nombre' => 'SOF. 1RO. JORGE CONDORI QUISPE',
                'fuerza' => 'FUERZA AÉREA',
                'grado' => 'Suboficial Primero',
                'gestiones' => [
                    2024 => ['meses' => 12, 'aporte' => 135.00, 'ganado' => 6700.00, 'liquido' => 5200.00],
                    2025 => ['meses' => 12, 'aporte' => 140.00, 'ganado' => 7100.00, 'liquido' => 5550.00],
                    2026 => ['meses' => 10, 'aporte' => 145.00, 'ganado' => 7350.00, 'liquido' => 5800.00],
                ]
            ],
            [
                'ci' => '6109442',
                'papeleta' => '41005',
                'nombre' => 'SGTO. 1RO. MARCELO FLORES CHOQUE',
                'fuerza' => 'ARMADA BOLIVIANA',
                'grado' => 'Sargento Primero',
                'gestiones' => [
                    2024 => ['meses' => 12, 'aporte' => 120.00, 'ganado' => 5900.00, 'liquido' => 4600.00],
                    2025 => ['meses' => 12, 'aporte' => 125.00, 'ganado' => 6200.00, 'liquido' => 4850.00],
                    2026 => ['meses' => 10, 'aporte' => 130.00, 'ganado' => 6450.00, 'liquido' => 5100.00],
                ]
            ],
        ];

        $registros = [];
        $ahora = now();
        $actualizadoAl = Carbon::create(2026, 10, 31)->toDateString();

        foreach ($afiliados as $a) {
            foreach ($a['gestiones'] as $anio => $info) {
                for ($m = 1; $m <= $info['meses']; $m++) {
                    $registros[] = [
                        'ci' => $a['ci'],
                        'numero_papeleta' => $a['papeleta'],
                        'nombre_completo' => $a['nombre'],
                        'fuerza' => $a['fuerza'],
                        'grado' => $a['grado'],
                        'anio' => $anio,
                        'mes' => $m,
                        'total_ganado' => $info['ganado'],
                        'monto_aporte' => $info['aporte'],
                        'liquido_pagable' => $info['liquido'],
                        'actualizado_al' => $actualizadoAl,
                        'created_at' => $ahora,
                        'updated_at' => $ahora,
                    ];
                }
            }
        }

        // Inserción en bloques
        foreach (array_chunk($registros, 50) as $chunk) {
            DB::table('afiliado_aportes_consulta')->insert($chunk);
        }
    }
}
