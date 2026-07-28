<?php
// database/seeders/ServicioSeeder.php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'ser_titulo' => 'Salón Dorado',
                'ser_descripcion' => 'Salón con capacidad para 150 personas, que incluye mesas y sillas; ideal para actividades o eventos sociales como matrimonios, cumpleaños, 15 años y otros.',
                'ser_direccion' => 'Calle Díaz Romero Nro. 1783',
                'ser_telefono_whatsapp' => '71528059',
                'ser_capacidad' => 150,
                'ser_orden' => 1,
            ],
            [
                'ser_titulo' => 'Hotel Casa Comunitaria',
                'ser_descripcion' => 'Hotel con 25 habitaciones (matrimonial, simple, dobles), Tv Cable, sala de internet, sala de espera.',
                'ser_direccion' => 'Zona de Miraflores, Calle Díaz Romero Nro. 1799',
                'ser_telefono_whatsapp' => '71224842',
                'ser_capacidad' => null,
                'ser_orden' => 2,
            ],
            [
                'ser_titulo' => 'Complejo Deportivo Cota Cota',
                'ser_descripcion' => 'Cancha polifuncional (Futsal, Básquetbol, Vóleibol), cabaña con parrillero, karaoke, áreas verdes y jardín para niños.',
                'ser_direccion' => 'Final Costanera, Av. J. Aguirre Acha Nro. 207',
                'ser_telefono_whatsapp' => '71526912',
                'ser_capacidad' => null,
                'ser_orden' => 3,
            ],
            [
                'ser_titulo' => 'Multifamiliar Juancito Pinto',
                'ser_descripcion' => 'Condominio de 50 departamentos (2 y 3 dormitorios) y garzonieres, con cancha deportiva y jardín recreativo.',
                'ser_direccion' => 'Zona de Miraflores, entre calles E. Guilarte y Juancito Pinto',
                'ser_telefono_whatsapp' => '71528059',
                'ser_capacidad' => null,
                'ser_orden' => 4,
            ],
            [
                'ser_titulo' => 'Salón de Banderas',
                'ser_descripcion' => 'Salón con capacidad para 80 personas; ideal para actividades militares, actos protocolares, seminarios y talleres.',
                'ser_direccion' => 'Calle Díaz Romero Nro. 1799',
                'ser_telefono_whatsapp' => '71528059',
                'ser_capacidad' => 80,
                'ser_orden' => 5,
            ],
        ];

        foreach ($servicios as $servicio) {
            $servicio['ser_estado'] = true;
            Servicio::create($servicio);
        }
    }
}