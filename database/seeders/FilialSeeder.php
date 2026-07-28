<?php
// database/seeders/FilialSeeder.php

namespace Database\Seeders;

use App\Models\Filial;
use Illuminate\Database\Seeder;

class FilialSeeder extends Seeder
{
    public function run(): void
    {
        $filiales = [
            ['fil_nombre' => 'La Paz - Oficina Central', 'fil_ciudad' => 'La Paz', 'fil_direccion' => 'Calle Díaz Romero Nro. 1783', 'fil_telefono' => '71528059'],
            ['fil_nombre' => 'Filial Camiri', 'fil_ciudad' => 'Camiri', 'fil_direccion' => 'Calle Sucre Esq. Sargento Maceda', 'fil_telefono' => '72256967'],
            ['fil_nombre' => 'Filial Cobija', 'fil_ciudad' => 'Cobija', 'fil_direccion' => 'Calle Villa De la Cruz', 'fil_telefono' => '74779662'],
            ['fil_nombre' => 'Filial Cochabamba', 'fil_ciudad' => 'Cochabamba', 'fil_direccion' => 'Calle Mariano Melgarejo Nro. 1795, entre Av. Gral. Pando', 'fil_telefono' => '71224845'],
            ['fil_nombre' => 'Filial Guayaramerín', 'fil_ciudad' => 'Guayaramerín', 'fil_direccion' => 'Av. Oruro Nro. 383 (media cuadra Plaza German Busch)', 'fil_telefono' => '72846179'],
            ['fil_nombre' => 'Filial Oruro', 'fil_ciudad' => 'Oruro', 'fil_direccion' => 'Av. Sgto. Flores entre calle Vásquez y 6 de Octubre Nro. 403', 'fil_telefono' => '71224754'],
            ['fil_nombre' => 'Filial Potosí', 'fil_ciudad' => 'Potosí', 'fil_direccion' => 'Calle Smith Esq. Chayanta Nro. 403', 'fil_telefono' => '73758666'],
            ['fil_nombre' => 'Filial Puerto Quijarro', 'fil_ciudad' => 'Puerto Quijarro', 'fil_direccion' => 'Av. Naval, media cuadra de la Ruta Virgen de Cotoca S/N', 'fil_telefono' => '63605791'],
            ['fil_nombre' => 'Filial Puerto Suárez', 'fil_ciudad' => 'Puerto Suárez', 'fil_direccion' => 'Av. Mariscal Sucre S/N, frente exrestaurant Avenida', 'fil_telefono' => '73668838'],
            ['fil_nombre' => 'Filial Riberalta', 'fil_ciudad' => 'Riberalta', 'fil_direccion' => 'Av. Nicolás Suárez Esq. Calle Santa Cruz', 'fil_telefono' => '74729961'],
            ['fil_nombre' => 'Filial Roboré', 'fil_ciudad' => 'Roboré', 'fil_direccion' => 'Calle La Paz Nro. 447', 'fil_telefono' => '68617331'],
            ['fil_nombre' => 'Filial Rurrenabaque', 'fil_ciudad' => 'Rurrenabaque', 'fil_direccion' => 'Av. Aniceto Arce, Loc. Rurrenabaque-Beni', 'fil_telefono' => '63107533'],
            ['fil_nombre' => 'Filial Santa Cruz', 'fil_ciudad' => 'Santa Cruz', 'fil_direccion' => 'Calle Manuel Ignacio Salvatierra Nro. 997, Barrio Lindo', 'fil_telefono' => '71225133'],
            ['fil_nombre' => 'Filial Sucre', 'fil_ciudad' => 'Sucre', 'fil_direccion' => 'Calle Almirante Grau Nro. 126', 'fil_telefono' => '71269707'],
            ['fil_nombre' => 'Filial Tarija', 'fil_ciudad' => 'Tarija', 'fil_direccion' => 'Barrio San Jerónimo, IV Brigada Aérea', 'fil_telefono' => '72596209'],
            ['fil_nombre' => 'Filial Trinidad', 'fil_ciudad' => 'Trinidad', 'fil_direccion' => 'Av. Marbán Nro. 137, Oficina 143', 'fil_telefono' => '71130775'],
            ['fil_nombre' => 'Filial Tupiza', 'fil_ciudad' => 'Tupiza', 'fil_direccion' => 'Calle Bolívar Nro. 134', 'fil_telefono' => '71124470'],
            ['fil_nombre' => 'Filial Villamontes', 'fil_ciudad' => 'Villamontes', 'fil_direccion' => 'Av. Ayacucho Esq. Subteniente Barrau', 'fil_telefono' => '68950096'],
            ['fil_nombre' => 'Filial Yacuiba', 'fil_ciudad' => 'Yacuiba', 'fil_direccion' => 'Calle Comercio entre calle Paraguay y Hernando Siles (Zona Sud)', 'fil_telefono' => '71160472'],
        ];

        foreach ($filiales as $orden => $filial) {
            $filial['fil_orden'] = $orden + 1;
            $filial['fil_estado'] = true;
            Filial::create($filial);
        }
    }
}