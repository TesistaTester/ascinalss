<?php
// database/seeders/ConfiguracionSeeder.php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        $config = [
            'direccion_principal' => 'Zona Miraflores / Calle Díaz Romero Esq. Lucas Jaimes N° 1783, La Paz - Bolivia',
            'telefono_central' => '02 2228770 - 02 2228771',
            'telefono_prestamos' => '71270945',
            'telefono_cobranzas' => '71224326',
            'telefono_daaro' => '71291147',
            'telefono_tesoreria' => '67197470',
            'whatsapp_solicitudes' => '71554528',
            'facebook_url' => 'http://facebook.com/ASCINALSS',
            'nombre_institucion' => 'Asociación Nacional de Suboficiales y Sargentos de las Fuerzas Armadas de la Nación',
            'sigla_institucion' => 'ASCINALSS',
        ];

        foreach ($config as $clave => $valor) {
            Configuracion::establecer($clave, $valor);
        }
    }
}