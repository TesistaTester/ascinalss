<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use App\Models\Convenio;
use App\Models\Filial;
use App\Models\Servicio;
use App\Models\Usuario;

class DashboardController extends Controller
{
    public function index()
    {
        $resumen = [
            'comunicados' => Comunicado::count(),
            'servicios' => Servicio::count(),
            'convenios' => Convenio::count(),
            'filiales' => Filial::count(),
            'usuarios' => Usuario::count(),
        ];

        $ultimosComunicados = Comunicado::orderByDesc('com_fecha_publicacion')->limit(5)->get();

        return view('admin.dashboard', compact('resumen', 'ultimosComunicados'));
    }
}