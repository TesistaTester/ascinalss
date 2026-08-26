<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsuarioSeeder::class,
            ConfiguracionSeeder::class,
            ServicioSeeder::class,
            ConvenioSeeder::class,
            FilialSeeder::class,
            InformeAnualSeeder::class,
            CategoriaPrestamoSeeder::class,
            ComunicadoSeeder::class,
            ProductoSimuladorSeeder::class,
        ]);
    }
}
