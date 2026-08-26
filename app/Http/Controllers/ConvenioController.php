<?php
// app/Http/Controllers/ConvenioController.php

namespace App\Http\Controllers;

use App\Models\Convenio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConvenioController extends Controller
{
    public function index()
    {
        $convenios = Convenio::orderBy('conv_orden')->paginate(15);
        return view('admin.convenios.index', compact('convenios'));
    }

    public function create()
    {
        return view('admin.convenios.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'conv_titulo' => 'required|string|max:255',
            'conv_descripcion' => 'nullable|string',
            'conv_empresa' => 'nullable|string|max:255',
            'conv_logo' => 'nullable|image|max:2048',
            'conv_pdf_archivo' => 'nullable|file|mimes:pdf|max:5120',
            'conv_orden' => 'nullable|integer',
            'conv_estado' => 'nullable|boolean',
        ]);

        if ($request->hasFile('conv_logo')) {
            $datos['conv_logo'] = $request->file('conv_logo')->store('convenios/logos', config('filesystems.default'));
        }

        if ($request->hasFile('conv_pdf_archivo')) {
            $datos['conv_pdf_archivo'] = $request->file('conv_pdf_archivo')->store('convenios/pdfs', config('filesystems.default'));
        }

        $datos['conv_estado'] = $request->boolean('conv_estado', true);
        $datos['conv_orden'] = $datos['conv_orden'] ?? 0;

        Convenio::create($datos);

        return redirect()->route('convenios.index')->with('exito', 'Convenio creado correctamente.');
    }

    public function edit(Convenio $convenio)
    {
        return view('admin.convenios.edit', compact('convenio'));
    }

    public function update(Request $request, Convenio $convenio)
    {
        $datos = $request->validate([
            'conv_titulo' => 'required|string|max:255',
            'conv_descripcion' => 'nullable|string',
            'conv_empresa' => 'nullable|string|max:255',
            'conv_logo' => 'nullable|image|max:2048',
            'conv_pdf_archivo' => 'nullable|file|mimes:pdf|max:5120',
            'conv_orden' => 'nullable|integer',
            'conv_estado' => 'nullable|boolean',
        ]);

        if ($request->hasFile('conv_logo')) {
            if ($convenio->conv_logo) {
                Storage::disk(config('filesystems.default'))->delete($convenio->conv_logo);
            }
            $datos['conv_logo'] = $request->file('conv_logo')->store('convenios/logos', config('filesystems.default'));
        }

        if ($request->hasFile('conv_pdf_archivo')) {
            if ($convenio->conv_pdf_archivo) {
                Storage::disk(config('filesystems.default'))->delete($convenio->conv_pdf_archivo);
            }
            $datos['conv_pdf_archivo'] = $request->file('conv_pdf_archivo')->store('convenios/pdfs', config('filesystems.default'));
        }

        $datos['conv_estado'] = $request->boolean('conv_estado', true);

        $convenio->update($datos);

        return redirect()->route('convenios.index')->with('exito', 'Convenio actualizado correctamente.');
    }

    public function destroy(Convenio $convenio)
    {
        $convenio->delete();

        return redirect()->route('convenios.index')->with('exito', 'Convenio eliminado correctamente.');
    }
}
