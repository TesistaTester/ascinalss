<?php
// app/Http/Controllers/InformeAnualController.php

namespace App\Http\Controllers;

use App\Models\InformeAnual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformeAnualController extends Controller
{
    public function index()
    {
        $informes = InformeAnual::orderByDesc('inf_anio')->paginate(15);
        return view('admin.informes-anuales.index', compact('informes'));
    }

    public function create()
    {
        return view('admin.informes-anuales.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'inf_anio' => 'required|digits:4|integer',
            'inf_titulo' => 'required|string|max:255',
            'inf_pdf_archivo' => 'required|file|mimes:pdf|max:10240',
            'inf_fecha_publicacion' => 'nullable|date',
            'inf_estado' => 'nullable|boolean',
        ]);

        $datos['inf_pdf_archivo'] = $request->file('inf_pdf_archivo')->store('informes-anuales', config('filesystems.default'));
        $datos['inf_estado'] = $request->boolean('inf_estado', true);

        InformeAnual::create($datos);

        return redirect()->route('informes-anuales.index')->with('exito', 'Informe anual publicado correctamente.');
    }

    public function edit(InformeAnual $informesAnuale)
    {
        return view('admin.informes-anuales.edit', ['informe' => $informesAnuale]);
    }

    public function update(Request $request, InformeAnual $informesAnuale)
    {
        $datos = $request->validate([
            'inf_anio' => 'required|digits:4|integer',
            'inf_titulo' => 'required|string|max:255',
            'inf_pdf_archivo' => 'nullable|file|mimes:pdf|max:10240',
            'inf_fecha_publicacion' => 'nullable|date',
            'inf_estado' => 'nullable|boolean',
        ]);

        if ($request->hasFile('inf_pdf_archivo')) {
            if ($informesAnuale->inf_pdf_archivo) {
                Storage::disk(config('filesystems.default'))->delete($informesAnuale->inf_pdf_archivo);
            }
            $datos['inf_pdf_archivo'] = $request->file('inf_pdf_archivo')->store('informes-anuales', config('filesystems.default'));
        }

        $datos['inf_estado'] = $request->boolean('inf_estado', true);

        $informesAnuale->update($datos);

        return redirect()->route('informes-anuales.index')->with('exito', 'Informe actualizado correctamente.');
    }

    public function destroy(InformeAnual $informesAnuale)
    {
        if ($informesAnuale->inf_pdf_archivo) {
            Storage::disk(config('filesystems.default'))->delete($informesAnuale->inf_pdf_archivo);
        }

        $informesAnuale->delete();

        return redirect()->route('informes-anuales.index')->with('exito', 'Informe eliminado correctamente.');
    }
}
