<?php
// database/seeders/ConvenioSeeder.php

namespace Database\Seeders;

use App\Models\Convenio;
use Illuminate\Database\Seeder;

class ConvenioSeeder extends Seeder
{
    public function run(): void
    {
        $convenios = [
            [
                'conv_titulo' => 'Convenio Nacional Seguros Patrimoniales y Finanzas S.A.',
                'conv_empresa' => 'Nacional Seguros Patrimoniales y Finanzas S.A.',
                'conv_descripcion' => 'Brinda atención y servicios de seguros vehiculares para socios y sus familias a nivel nacional: reembolso por pérdida total, daños a terceros, daños parciales, asistencia vial y jurídica gratuita. Contacto: 78549008 (Ingrid Lobatón F.)',
                'conv_orden' => 1,
            ],
            [
                'conv_titulo' => 'Convenio Escuela Militar de Ingeniería (E.M.I.)',
                'conv_empresa' => 'Escuela Militar de Ingeniería',
                'conv_descripcion' => 'Convenio dirigido a los beneficiarios de los Suboficiales y Sargentos de las Fuerzas Armadas.',
                'conv_orden' => 2,
            ],
            [
                'conv_titulo' => 'Convenio Instituto Técnico ATENEA',
                'conv_empresa' => 'Instituto Técnico ATENEA',
                'conv_descripcion' => 'Capacitación a nivel Técnico Superior con título en Provisión Nacional en Diseño Gráfico, Contaduría General y Sistemas Informáticos (3 años de estudio).',
                'conv_orden' => 3,
            ],
            [
                'conv_titulo' => 'Universidad Técnica Privada Cosmos (UNITEPC)',
                'conv_empresa' => 'UNITEPC',
                'conv_descripcion' => 'Carreras de Ingeniería Comercial, Contaduría Pública y Administración de Empresas a nivel Licenciatura, en dos años (cuatro semestres).',
                'conv_orden' => 4,
            ],
            [
                'conv_titulo' => 'Unidad Educativa Integral "AMÉRICA"',
                'conv_empresa' => 'Unidad Educativa AMÉRICA',
                'conv_descripcion' => 'Descuento del 20% en el pago de la mensualidad para hijos/as de socios y personal administrativo de ASCINALSS.',
                'conv_orden' => 5,
            ],
        ];

        foreach ($convenios as $convenio) {
            $convenio['conv_estado'] = true;
            Convenio::create($convenio);
        }
    }
}