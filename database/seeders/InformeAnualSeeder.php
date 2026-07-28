<?php
// database/seeders/InformeAnualSeeder.php

namespace Database\Seeders;

use App\Models\InformeAnual;
use Illuminate\Database\Seeder;

class InformeAnualSeeder extends Seeder
{
    public function run(): void
    {
        InformeAnual::create([
            'inf_anio' => 2025,
            'inf_titulo' => 'Revista Gestión 2023-2025 "ASCINALSS"',
            'inf_pdf_archivo' => 'informes-anuales/revista-gestion-2023-2025.pdf', // reemplazar con el PDF real subido
            'inf_fecha_publicacion' => now(),
            'inf_estado' => true,
        ]);
    }
}