<?php
// app/Http/Controllers/FilialController.php

namespace App\Http\Controllers;

use App\Models\Filial;
use Illuminate\Http\Request;

class FilialController extends Controller
{
    public function index()
    {
        $filiales = Filial::orderBy('fil_orden')->paginate(15);
        return view('admin.filiales.index', compact('filiales'));
    }

    public function create()
    {
        return view('admin.filiales.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'fil_nombre' => 'required|string|max:255',
            'fil_ciudad' => 'nullable|string|max:100',
            'fil_direccion' => 'nullable|string|max:255',
            'fil_telefono' => 'nullable|string|max:20',
            'fil_orden' => 'nullable|integer',
            'fil_estado' => 'nullable|boolean',
        ]);

        $datos['fil_estado'] = $request->boolean('fil_estado', true);
        $datos['fil_orden'] = $datos['fil_orden'] ?? 0;

        Filial::create($datos);

        return redirect()->route('filiales.index')->with('exito', 'Filial creada correctamente.');
    }

    public function edit(Filial $filial)
    {
        return view('admin.filiales.edit', compact('filial'));
    }

    public function update(Request $request, Filial $filial)
    {
        $datos = $request->validate([
            'fil_nombre' => 'required|string|max:255',
            'fil_ciudad' => 'nullable|string|max:100',
            'fil_direccion' => 'nullable|string|max:255',
            'fil_telefono' => 'nullable|string|max:20',
            'fil_orden' => 'nullable|integer',
            'fil_estado' => 'nullable|boolean',
        ]);

        $datos['fil_estado'] = $request->boolean('fil_estado', true);

        $filial->update($datos);

        return redirect()->route('filiales.index')->with('exito', 'Filial actualizada correctamente.');
    }

    public function destroy(Filial $filial)
    {
        $filial->delete();

        return redirect()->route('filiales.index')->with('exito', 'Filial eliminada correctamente.');
    }
}