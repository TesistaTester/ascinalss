<?php
// app/Http/Controllers/PublicoController.php

namespace App\Http\Controllers;

use App\Models\CategoriaPrestamo;
use App\Models\Comunicado;
use App\Models\Configuracion;
use App\Models\Convenio;
use App\Models\Filial;
use App\Models\InformeAnual;
use App\Models\Servicio;
use App\Models\ProductoSimulador;

class PublicoController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('ser_estado', true)->orderBy('ser_orden')->get();
        $convenios = Convenio::where('conv_estado', true)->orderBy('conv_orden')->get();
        $categoriasPrestamo = CategoriaPrestamo::where('cat_estado', true)->orderBy('cat_orden')->with('documentos')->get();
        $productosSimulador = ProductoSimulador::where('pro_activo', true)
            ->where(function ($query) { $query->whereNull('pro_vigente_desde')->orWhere('pro_vigente_desde', '<=', now()); })
            ->where(function ($query) { $query->whereNull('pro_vigente_hasta')->orWhere('pro_vigente_hasta', '>=', now()); })
            ->orderBy('pro_id')->get();

        $comunicadosModales = Comunicado::where('com_estado', true)
            ->where('com_tipo', 'modal')
            ->get()
            ->filter->vigente();

        $heroSlogan = Configuracion::obtener('hero_slogan', 'Trabajamos por un futuro mejor');
        $contacto = $this->datosContacto();

        return view('publico.index', compact(
            'servicios', 'convenios', 'categoriasPrestamo', 'productosSimulador',
            'comunicadosModales', 'heroSlogan', 'contacto'
        ));
    }

    public function filiales()
    {
        $filiales = Filial::where('fil_estado', true)->orderBy('fil_orden')->get();
        $contacto = $this->datosContacto();

        return view('publico.filiales', compact('filiales', 'contacto'));
    }

    public function comunicados()
    {
        $comunicados = Comunicado::where('com_estado', true)
            ->whereIn('com_tipo', ['normal', 'destacado', 'novedad'])
            ->orderByDesc('com_fijado')
            ->orderByDesc('com_fecha_publicacion')
            ->paginate(9);

        $contacto = $this->datosContacto();

        return view('publico.comunicados', compact('comunicados', 'contacto'));
    }

    public function informeAnual()
    {
        $informes = InformeAnual::where('inf_estado', true)->orderByDesc('inf_anio')->get();
        $contacto = $this->datosContacto();

        return view('publico.informe-anual', compact('informes', 'contacto'));
    }

    private function datosContacto(): array
    {
        return [
            'direccion' => Configuracion::obtener('direccion_principal'),
            'telefono_central' => Configuracion::obtener('telefono_central'),
            'telefono_prestamos' => Configuracion::obtener('telefono_prestamos'),
            'telefono_cobranza' => Configuracion::obtener('telefono_cobranzas'),
            'telefono_daaro' => Configuracion::obtener('telefono_daaro'),
            'telefono_tesoreria' => Configuracion::obtener('telefono_tesoreria'),
            'whatsapp' => Configuracion::obtener('whatsapp_solicitudes'),
            'facebook' => Configuracion::obtener('facebook_url'),
        ];
    }


}
