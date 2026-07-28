<?php
// database/seeders/ComunicadoSeeder.php

namespace Database\Seeders;

use App\Models\Comunicado;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class ComunicadoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Usuario::where('usu_nombre', 'admin')->first();

        $comunicados = [
            [
                'com_titulo' => 'Reunión Extraordinaria - Resoluciones',
                'com_contenido' => 'Publicación de las resoluciones emitidas en la Reunión Extraordinaria del Directorio de ASCINALSS.',
                'com_tipo' => Comunicado::TIPO_NORMAL,
                'com_fijado' => true,
            ],
            [
                'com_titulo' => 'Comité Nacional Electoral - Elecciones CEN 2022-2025',
                'com_contenido' => 'Convocatoria, cronograma de actividades, relación nominal de candidatos y resoluciones del Comité Nacional Electoral para las elecciones del Consejo Ejecutivo Nacional (CEN).',
                'com_tipo' => Comunicado::TIPO_NORMAL,
                'com_fijado' => false,
            ],
            [
                'com_titulo' => 'Convenio de Colaboración Empresarial',
                'com_contenido' => 'Documento del convenio de colaboración empresarial suscrito por ASCINALSS.',
                'com_tipo' => Comunicado::TIPO_NORMAL,
                'com_fijado' => false,
            ],
            [
                'com_titulo' => 'Convocatoria Campeonato',
                'com_contenido' => 'Convocatoria oficial al campeonato deportivo organizado por ASCINALSS.',
                'com_tipo' => Comunicado::TIPO_NORMAL,
                'com_fijado' => false,
            ],
            [
                'com_titulo' => 'Estatuto y Documento Base de Contratación (DBC)',
                'com_contenido' => 'Comunicado informativo con el Documento Base de Contratación y Estatuto vigente de ASCINALSS.',
                'com_tipo' => Comunicado::TIPO_NORMAL,
                'com_fijado' => false,
            ],
        ];

        foreach ($comunicados as $com) {
            $com['com_usuario_id'] = $admin?->usu_id;
            $com['com_fecha_publicacion'] = now();
            Comunicado::create($com);
        }
    }
}