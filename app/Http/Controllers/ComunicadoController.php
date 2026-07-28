<?php
// app/Http/Controllers/ComunicadoController.php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComunicadoController extends Controller
{
    public function index()
    {
        $comunicados = Comunicado::with('usuario')
            ->orderByDesc('com_fijado')
            ->orderByDesc('com_fecha_publicacion')
            ->paginate(15);

        return view('admin.comunicados.index', compact('comunicados'));
    }

    public function create()
    {
        return view('admin.comunicados.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'com_titulo' => 'required|string|max:255',
            'com_contenido' => 'required|string',
            'com_imagen' => 'nullable|image|max:2048',
            'com_pdf_archivo' => 'nullable|file|mimes:pdf|max:5120',
            'com_video_url' => 'nullable|url|max:255',
            'com_pptx_archivo' => 'nullable|file|mimes:ppt,pptx|max:20480',
            'com_tipo' => 'required|in:normal,modal,destacado,novedad',
            'com_fecha_publicacion' => 'required|date',
            'com_fecha_expiracion' => 'nullable|date|after_or_equal:com_fecha_publicacion',
            'com_fijado' => 'nullable|boolean',
        ]);

        if ($request->hasFile('com_imagen')) {
            $datos['com_imagen'] = $request->file('com_imagen')->store('comunicados/imagenes', 'public');
        }

        if ($request->hasFile('com_pdf_archivo')) {
            $datos['com_pdf_archivo'] = $request->file('com_pdf_archivo')->store('comunicados/pdfs', 'public');
        }

        if ($request->hasFile('com_pptx_archivo')) {
            $datos['com_pptx_archivo'] = $request->file('com_pptx_archivo')->store('comunicados/presentaciones', 'public');
        }

        $datos['com_usuario_id'] = auth()->id();
        $datos['com_fijado'] = $request->boolean('com_fijado');

        Comunicado::create($datos);

        return redirect()->route('comunicados.index')->with('exito', 'Comunicado publicado correctamente.');
    }

    public function edit(Comunicado $comunicado)
    {
        return view('admin.comunicados.edit', compact('comunicado'));
    }

    public function update(Request $request, Comunicado $comunicado)
    {
        $datos = $request->validate([
            'com_titulo' => 'required|string|max:255',
            'com_contenido' => 'required|string',
            'com_imagen' => 'nullable|image|max:2048',
            'com_pdf_archivo' => 'nullable|file|mimes:pdf|max:5120',
            'com_video_url' => 'nullable|url|max:255',
            'com_pptx_archivo' => 'nullable|file|mimes:ppt,pptx|max:20480',
            'com_tipo' => 'required|in:normal,modal,destacado,novedad',
            'com_fecha_publicacion' => 'required|date',
            'com_fecha_expiracion' => 'nullable|date|after_or_equal:com_fecha_publicacion',
            'com_fijado' => 'nullable|boolean',
        ]);

        if ($request->hasFile('com_imagen')) {
            if ($comunicado->com_imagen) {
                Storage::disk('public')->delete($comunicado->com_imagen);
            }
            $datos['com_imagen'] = $request->file('com_imagen')->store('comunicados/imagenes', 'public');
        }

        if ($request->hasFile('com_pdf_archivo')) {
            if ($comunicado->com_pdf_archivo) {
                Storage::disk('public')->delete($comunicado->com_pdf_archivo);
            }
            $datos['com_pdf_archivo'] = $request->file('com_pdf_archivo')->store('comunicados/pdfs', 'public');
        }

        if ($request->hasFile('com_pptx_archivo')) {
            if ($comunicado->com_pptx_archivo) {
                Storage::disk('public')->delete($comunicado->com_pptx_archivo);
            }
            $datos['com_pptx_archivo'] = $request->file('com_pptx_archivo')->store('comunicados/presentaciones', 'public');
        }

        $datos['com_fijado'] = $request->boolean('com_fijado');

        $comunicado->update($datos);

        return redirect()->route('comunicados.index')->with('exito', 'Comunicado actualizado correctamente.');
    }

    public function destroy(Comunicado $comunicado)
    {
        foreach (['com_imagen', 'com_pdf_archivo', 'com_pptx_archivo'] as $campo) {
            if ($comunicado->{$campo}) {
                Storage::disk('public')->delete($comunicado->{$campo});
            }
        }

        $comunicado->delete();

        return redirect()->route('comunicados.index')->with('exito', 'Comunicado eliminado correctamente.');
    }
}