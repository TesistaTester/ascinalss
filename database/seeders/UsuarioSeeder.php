<?php
// database/seeders/UsuarioSeeder.php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'usu_rol' => Usuario::ROL_ADMIN,
            'usu_nombre' => 'admin',
            'password' => Hash::make('2026*'),
            'usu_nombre_completo' => 'Administrador Website ASCINALSS',
            'usu_estado' => true,
        ]);

        Usuario::create([
            'usu_rol' => Usuario::ROL_EDITOR,
            'usu_nombre' => 'editor',
            'password' => Hash::make('2026*'),
            'usu_nombre_completo' => 'Editor de Contenido',
            'usu_estado' => true,
        ]);

        Usuario::create([
            'usu_rol' => Usuario::ROL_DIRECTORIO,
            'usu_nombre' => 'directorio',
            'password' => Hash::make('2026*'),
            'usu_nombre_completo' => 'Directorio ASCINALSS',
            'usu_estado' => true,
        ]);
    }
}