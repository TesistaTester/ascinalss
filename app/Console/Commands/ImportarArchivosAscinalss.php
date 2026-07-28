<?php
// app/Console/Commands/ImportarArchivosAscinalss.php

namespace App\Console\Commands;

use App\Models\Comunicado;
use App\Models\Convenio;
use App\Models\DocumentoPrestamo;
use App\Models\InformeAnual;
use App\Models\Servicio;
use App\Models\Filial;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ImportarArchivosAscinalss extends Command
{
    protected $signature = 'ascinalss:importar-archivos {--forzar : Vuelve a descargar aunque el archivo ya exista}';
    protected $description = 'Descarga los archivos reales del sitio antiguo ascinalss.org y los coloca en storage/app/public, actualizando los registros sembrados.';

    private const BASE = 'http://www.ascinalss.org/ascinalss/';

    public function handle(): int
    {
        $forzar = (bool) $this->option('forzar');

        $this->info('Iniciando importación de archivos desde ascinalss.org...');

        $this->importarServicios($forzar);
        $this->importarConvenios($forzar);
        $this->importarInformeAnual($forzar);
        $this->importarDocumentosPrestamo($forzar);
        $this->importarComunicados($forzar);
        $this->importarFiliales($forzar);

        $this->info('Importación finalizada. Revisa el resumen de errores (si hubo) arriba.');

        return self::SUCCESS;
    }

    /**
     * Descarga un archivo remoto y lo guarda en el disco public.
     * Retorna true si se guardó (o ya existía y no se forzó), false si falló.
     */
    private function descargar(string $urlRelativa, string $rutaDestino, bool $forzar): bool
    {
        if (!$forzar && Storage::disk('public')->exists($rutaDestino)) {
            $this->line("  ↷ Ya existe, se omite: {$rutaDestino}");
            return true;
        }

        // Codifica espacios y caracteres especiales en la URL sin tocar las barras "/"
        $urlCompleta = self::BASE . implode('/', array_map('rawurlencode', explode('/', $urlRelativa)));

        try {
            $respuesta = Http::timeout(30)->retry(2, 500)->get($urlCompleta);

            if (!$respuesta->successful()) {
                $this->error("  ✗ Falló ({$respuesta->status()}): {$urlCompleta}");
                return false;
            }

            Storage::disk('public')->put($rutaDestino, $respuesta->body());
            $this->info("  ✓ Descargado: {$rutaDestino}");
            return true;
        } catch (\Throwable $e) {
            $this->error("  ✗ Error de conexión en {$urlCompleta}: {$e->getMessage()}");
            return false;
        }
    }

    private function importarServicios(bool $forzar): void
    {
        $this->comment('Servicios (imágenes)...');

        $mapa = [
            'Salón Dorado' => ['imagenes/SALON DORADO 2.jpg', 'servicios/salon-dorado.jpg'],
            'Hotel Casa Comunitaria' => ['imagenes/Hotel3.jpg', 'servicios/hotel-casa-comunitaria.jpg'],
            'Complejo Deportivo Cota Cota' => ['imagenes/cota cota 1.jpg', 'servicios/complejo-cota-cota.jpg'],
            'Multifamiliar Juancito Pinto' => ['imagenes/juanp.jpg', 'servicios/multifamiliar-juancito-pinto.jpg'],
            'Salón de Banderas' => ['imagenes/banderas.jpg', 'servicios/salon-banderas.jpg'],
        ];

        foreach ($mapa as $titulo => [$origen, $destino]) {
            if ($this->descargar($origen, $destino, $forzar)) {
                Servicio::where('ser_titulo', $titulo)->update(['ser_imagen' => $destino]);
            }
        }
    }

    private function importarConvenios(bool $forzar): void
    {
        $this->comment('Convenios (PDFs)...');

        $mapa = [
            'Convenio Nacional Seguros Patrimoniales y Finanzas S.A.' => ['imagenes_doc/convenioseguro.pdf', 'convenios/pdfs/convenio-seguro-nacional.pdf'],
            'Convenio Escuela Militar de Ingeniería (E.M.I.)' => ['imagenes_doc/conv_emi.pdf', 'convenios/pdfs/convenio-emi.pdf'],
            'Convenio Instituto Técnico ATENEA' => ['imagenes_doc/ATENEA 2023.pdf', 'convenios/pdfs/convenio-atenea.pdf'],
            'Universidad Técnica Privada Cosmos (UNITEPC)' => ['imagenes_doc/UNITEPEC.pdf', 'convenios/pdfs/convenio-unitepc.pdf'],
            'Unidad Educativa Integral "AMÉRICA"' => ['imagenes_doc/UNIDAD EDUCATIVA AMERICA.pdf', 'convenios/pdfs/convenio-america.pdf'],
        ];

        foreach ($mapa as $titulo => [$origen, $destino]) {
            if ($this->descargar($origen, $destino, $forzar)) {
                Convenio::where('conv_titulo', $titulo)->update(['conv_pdf_archivo' => $destino]);
            }
        }
    }

    private function importarInformeAnual(bool $forzar): void
    {
        $this->comment('Informe anual (PDF + portada)...');

        $origenPdf = 'imagenes_doc/REVISTA ASCINALSS 2023-2025.pdf';
        $destinoPdf = 'informes-anuales/revista-gestion-2023-2025.pdf';

        if ($this->descargar($origenPdf, $destinoPdf, $forzar)) {
            InformeAnual::where('inf_anio', 2025)->update(['inf_pdf_archivo' => $destinoPdf]);
        }

        // Portada de la revista, útil si luego agregas un campo de portada al modelo
        $this->descargar('comunicados/cararevista.jpeg', 'informes-anuales/portada-2023-2025.jpeg', $forzar);
    }

    private function importarDocumentosPrestamo(bool $forzar): void
    {
        $this->comment('Documentos de préstamo (requisitos, contratos, formularios)...');

        $mapa = [
            'Ver Requisitos|Préstamos de Emergencia' => ['imagenes_doc/emergencia.pdf', 'prestamos/documentos/emergencia.pdf'],
            'Requisitos con Garantes|Regulares con Garantes' => ['imagenes_doc/pcongarantes.pdf', 'prestamos/documentos/pcongarantes.pdf'],
            'Requisitos sin Garantes|Regulares sin Garantes' => ['imagenes_doc/psingarantes.pdf', 'prestamos/documentos/psingarantes.pdf'],
            'Requisitos de Iniciación|Préstamos de Iniciación' => ['imagenes_doc/piniciacion.pdf', 'prestamos/documentos/piniciacion.pdf'],
            'Requisitos D.A.A.R.O. Cumplimiento de Plazo|D.A.A.R.O.' => ['imagenes_doc/daarocumplimiento.pdf', 'prestamos/documentos/daarocumplimiento.pdf'],
            'Requisitos D.A.A.R.O. Jubilación|D.A.A.R.O.' => ['imagenes_doc/daarojubilacion.pdf', 'prestamos/documentos/daarojubilacion.pdf'],
            'Requisitos D.A.A.R.O. Fallecimiento|D.A.A.R.O.' => ['imagenes_doc/daarofallecimiento.pdf', 'prestamos/documentos/daarofallecimiento.pdf'],
            'Requisitos D.A.A.R.O. por Retiro|D.A.A.R.O.' => ['imagenes_doc/daaroretiro.pdf', 'prestamos/documentos/daaroretiro.pdf'],
            'Requisitos Carnet de Socio|Afiliaciones' => ['imagenes_doc/CARNET DE SOCIO TITULAR.pdf', 'prestamos/documentos/carnet-socio-titular.pdf'],
        ];

        // El formulario de solicitud se repite en 3 categorías; se descarga una sola vez
        $formularioDescargado = $this->descargar(
            'imagenes_doc/formulario-de-solicitud.pdf',
            'prestamos/documentos/formulario-de-solicitud.pdf',
            $forzar
        );
        if ($formularioDescargado) {
            DocumentoPrestamo::where('doc_etiqueta', 'Formulario de Solicitud')
                ->update(['doc_pdf_archivo' => 'prestamos/documentos/formulario-de-solicitud.pdf']);
        }

        foreach ($mapa as $clave => [$origen, $destino]) {
            [$etiqueta] = explode('|', $clave);
            if ($this->descargar($origen, $destino, $forzar)) {
                DocumentoPrestamo::where('doc_etiqueta', $etiqueta)->update(['doc_pdf_archivo' => $destino]);
            }
        }
    }

    private function importarFiliales(bool $forzar): void
    {
        $this->comment('Filiales (fotos)...');

        $mapa = [
            'La Paz - Oficina Central'    => ['imagenes/ASCINALSS.png', 'filiales/la-paz.png'],
            'Filial Camiri'               => ['imagenes/camiri.jpg', 'filiales/camiri.jpg'],
            'Filial Cobija'               => ['imagenes/COBIJA.jpg', 'filiales/cobija.jpg'],
            'Filial Cochabamba'           => ['imagenes/COCHABAMBA.jpg', 'filiales/cochabamba.jpg'],
            'Filial Guayaramerín'         => ['imagenes/GUAYAMERIN.jpg', 'filiales/guayaramerin.jpg'],
            'Filial Oruro'                => ['imagenes/ORURO.jpg', 'filiales/oruro.jpg'],
            'Filial Potosí'               => ['imagenes/POTOSI.jpg', 'filiales/potosi.jpg'],
            'Filial Puerto Quijarro'      => ['imagenes/Pto Quijarro.jpg', 'filiales/puerto-quijarro.jpg'],
            'Filial Puerto Suárez'        => ['imagenes/Pot Suarez.jpg', 'filiales/puerto-suarez.jpg'],
            'Filial Roboré'               => ['imagenes/Robore.jpg', 'filiales/robore.jpg'],
            'Filial Rurrenabaque'         => ['imagenes/Rurrenabaque.jpg', 'filiales/rurrenabaque.jpg'],
            'Filial Santa Cruz'           => ['imagenes/Santa Cruz.jpg', 'filiales/santa-cruz.jpg'],
            'Filial Sucre'                => ['imagenes/Sucre.jpg', 'filiales/sucre.jpg'],
            'Filial Tarija'               => ['imagenes/Tarija.jpg', 'filiales/tarija.jpg'],
            'Filial Trinidad'             => ['imagenes/Trinidad.jpg', 'filiales/trinidad.jpg'],
            'Filial Tupiza'               => ['imagenes/Tupiza.jpg', 'filiales/tupiza.jpg'],
            'Filial Villamontes'          => ['imagenes/Villamontes.jpg', 'filiales/villamontes.jpg'],
            'Filial Yacuiba'              => ['imagenes/Yacuiba.jpg', 'filiales/yacuiba.jpg'],
            'Filial Riberalta'             => ['imagenes/Trinidad.jpg', 'filiales/trinidad.jpg'],
        ];    
        foreach ($mapa as $nombre => [$origen, $destino]) {
            if ($this->descargar($origen, $destino, $forzar)) {
                $actualizado = Filial::where('fil_nombre', $nombre)->update(['fil_imagen' => $destino]);
                if ($actualizado === 0) {
                    $this->warn("  ⚠ No se encontró la filial \"{$nombre}\" en la base de datos (revisa que el nombre coincida exacto con el seeder).");
                }
            }
        }
    }
    
    private function importarComunicados(bool $forzar): void
    {
        $this->comment('Comunicados (PDFs e imágenes)...');

        // DBC / Estatuto
        $this->descargar(
            'imagenes_doc/DBCYTRESTATUTO_ASCINALSSV2.pdf',
            'comunicados/pdfs/dbc-estatuto.pdf',
            $forzar
        );
        Comunicado::where('com_titulo', 'Estatuto y Documento Base de Contratación (DBC)')
            ->update(['com_pdf_archivo' => 'comunicados/pdfs/dbc-estatuto.pdf']);

        // Reunión Extraordinaria: 7 resoluciones
        for ($i = 1; $i <= 7; $i++) {
            $n = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $this->descargar(
                "reunionextra/RESOLUCION REUNION EXTRAORDINARIA_{$n}.pdf",
                "comunicados/reunion-extraordinaria/resolucion-{$n}.pdf",
                $forzar
            );
        }

        // Comité Electoral 2022: varios documentos con nombres irregulares
        $comite = [
            'comite/convocatoria elecciones2022.pdf' => 'comunicados/comite-electoral/convocatoria-elecciones-2022.pdf',
            'comite/cronograma elecciones 2022.pdf' => 'comunicados/comite-electoral/cronograma-elecciones-2022.pdf',
            'comite/nomina.pdf' => 'comunicados/comite-electoral/nomina-candidatos.pdf',
            'comite/resolucion13.pdf' => 'comunicados/comite-electoral/resolucion-013-2022.pdf',
            'comite/cronogramaupdate.pdf' => 'comunicados/comite-electoral/cronograma-actualizado.pdf',
            'comite/OBSERVACIONES.pdf' => 'comunicados/comite-electoral/candidatos-observados.pdf',
            'comite/FRENTES HABILITADOS.pdf' => 'comunicados/comite-electoral/candidatos-habilitados.pdf',
            'comite/resolucion14.pdf' => 'comunicados/comite-electoral/resolucion-014-2023.pdf',
            'comite/mesas.pdf' => 'comunicados/comite-electoral/designacion-mesas.pdf',
            'comite/resolucion15.pdf' => 'comunicados/comite-electoral/resolucion-015-2023.pdf',
            'comite/resolucion17.pdf' => 'comunicados/comite-electoral/resolucion-017-2023.pdf',
            'comite/resolucion18.pdf' => 'comunicados/comite-electoral/resolucion-018-2023.pdf',
            'comite/resolucion19.pdf' => 'comunicados/comite-electoral/resolucion-019-2023.pdf',
        ];
        foreach ($comite as $origen => $destino) {
            $this->descargar($origen, $destino, $forzar);
        }

        // Convenio de colaboración + Convocatoria campeonato
        $this->descargar('tools/convenio.pdf', 'comunicados/convenio-colaboracion-empresarial.pdf', $forzar);
        $this->descargar('tools/campeonatolxv.pdf', 'comunicados/convocatoria-campeonato-lxv.pdf', $forzar);

        // Imágenes sueltas de comunicados
        $imagenes = [
            'comunicados/consultoria.jpg' => 'comunicados/imagenes/consultoria.jpg',
            'comunicados/comvida2.jpg' => 'comunicados/imagenes/comvida2.jpg',
            'comunicados/comuofi.jpeg' => 'comunicados/imagenes/comuofi.jpeg',
            'comunicados/com_03_2023.jpg' => 'comunicados/imagenes/com_03_2023.jpg',
        ];
        foreach ($imagenes as $origen => $destino) {
            $this->descargar($origen, $destino, $forzar);
        }
    }
}