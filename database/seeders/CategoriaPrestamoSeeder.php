<?php
// database/seeders/CategoriaPrestamoSeeder.php

namespace Database\Seeders;

use App\Models\CategoriaPrestamo;
use App\Models\DocumentoPrestamo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriaPrestamoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'cat_nombre' => 'Préstamos de Emergencia',
                'cat_descripcion' => 'Realice su solicitud de préstamo de emergencia directamente en línea.',
                'cat_icono' => 'fa-money-bill-transfer',
                'cat_orden' => 1,
                'documentos' => [
                    ['doc_tipo' => 'requisitos', 'doc_etiqueta' => 'Ver Requisitos', 'doc_pdf_archivo' => 'prestamos/documentos/emergencia.pdf'],
                ],
            ],
            [
                'cat_nombre' => 'Regulares con Garantes',
                'cat_descripcion' => 'Descargue el contrato y formulario de préstamo con garante.',
                'cat_icono' => 'fa-handshake-angle',
                'cat_orden' => 2,
                'documentos' => [
                    ['doc_tipo' => 'requisitos', 'doc_etiqueta' => 'Requisitos con Garantes', 'doc_pdf_archivo' => 'prestamos/documentos/pcongarantes.pdf'],
                    ['doc_tipo' => 'formulario', 'doc_etiqueta' => 'Formulario de Solicitud', 'doc_pdf_archivo' => 'prestamos/documentos/formulario-de-solicitud.pdf'],
                ],
            ],
            [
                'cat_nombre' => 'Regulares sin Garantes',
                'cat_descripcion' => 'Descargue el contrato y formulario de préstamo sin garante.',
                'cat_icono' => 'fa-hand-holding-dollar',
                'cat_orden' => 3,
                'documentos' => [
                    ['doc_tipo' => 'requisitos', 'doc_etiqueta' => 'Requisitos sin Garantes', 'doc_pdf_archivo' => 'prestamos/documentos/psingarantes.pdf'],
                    ['doc_tipo' => 'formulario', 'doc_etiqueta' => 'Formulario de Solicitud', 'doc_pdf_archivo' => 'prestamos/documentos/formulario-de-solicitud.pdf'],
                ],
            ],
            [
                'cat_nombre' => 'Préstamos de Iniciación',
                'cat_descripcion' => 'Descargue el contrato y formulario de préstamo de iniciación.',
                'cat_icono' => 'fa-seedling',
                'cat_orden' => 4,
                'documentos' => [
                    ['doc_tipo' => 'requisitos', 'doc_etiqueta' => 'Requisitos de Iniciación', 'doc_pdf_archivo' => 'prestamos/documentos/piniciacion.pdf'],
                    ['doc_tipo' => 'formulario', 'doc_etiqueta' => 'Formulario de Solicitud', 'doc_pdf_archivo' => 'prestamos/documentos/formulario-de-solicitud.pdf'],
                ],
            ],
            [
                'cat_nombre' => 'D.A.A.R.O.',
                'cat_descripcion' => 'Devolución de Aportes Acumulados y Rendimientos Obtenidos.',
                'cat_icono' => 'fa-piggy-bank',
                'cat_orden' => 5,
                'documentos' => [
                    ['doc_tipo' => 'requisitos', 'doc_etiqueta' => 'Requisitos D.A.A.R.O. Cumplimiento de Plazo', 'doc_pdf_archivo' => 'prestamos/documentos/daarocumplimiento.pdf'],
                    ['doc_tipo' => 'requisitos', 'doc_etiqueta' => 'Requisitos D.A.A.R.O. Jubilación', 'doc_pdf_archivo' => 'prestamos/documentos/daarojubilacion.pdf'],
                    ['doc_tipo' => 'requisitos', 'doc_etiqueta' => 'Requisitos D.A.A.R.O. Fallecimiento', 'doc_pdf_archivo' => 'prestamos/documentos/daarofallecimiento.pdf'],
                    ['doc_tipo' => 'requisitos', 'doc_etiqueta' => 'Requisitos D.A.A.R.O. por Retiro', 'doc_pdf_archivo' => 'prestamos/documentos/daaroretiro.pdf'],
                ],
            ],
            [
                'cat_nombre' => 'Afiliaciones',
                'cat_descripcion' => 'Requisitos para la obtención del carnet de socio.',
                'cat_icono' => 'fa-id-card',
                'cat_orden' => 6,
                'documentos' => [
                    ['doc_tipo' => 'requisitos', 'doc_etiqueta' => 'Requisitos Carnet de Socio', 'doc_pdf_archivo' => 'prestamos/documentos/carnet-socio-titular.pdf'],
                ],
            ],
        ];

        foreach ($categorias as $catData) {
            $documentos = $catData['documentos'];
            unset($catData['documentos']);

            $catData['cat_slug'] = Str::slug($catData['cat_nombre']);
            $catData['cat_estado'] = true;

            $categoria = CategoriaPrestamo::create($catData);

            foreach ($documentos as $orden => $doc) {
                $doc['doc_categoria_id'] = $categoria->cat_id;
                $doc['doc_orden'] = $orden + 1;
                $doc['doc_estado'] = true;
                DocumentoPrestamo::create($doc);
            }
        }
    }
}