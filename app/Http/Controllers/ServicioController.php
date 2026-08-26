<?php
// app/Http/Controllers/ServicioController.php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::orderBy('ser_orden')->paginate(15);
        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('admin.servicios.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'ser_titulo' => 'required|string|max:255',
            'ser_descripcion' => 'nullable|string',
            'ser_imagen' => 'nullable|image|max:2048',
            'ser_direccion' => 'nullable|string|max:255',
            'ser_telefono_whatsapp' => 'nullable|string|max:20',
            'ser_capacidad' => 'nullable|integer',
            'ser_orden' => 'nullable|integer',
            'ser_estado' => 'nullable|boolean',
        ]);

        if ($request->hasFile('ser_imagen')) {
            $datos['ser_imagen'] = $request->file('ser_imagen')->store('servicios', config('filesystems.default'));
        }

        $datos['ser_estado'] = $request->boolean('ser_estado', true);
        $datos['ser_orden'] = $datos['ser_orden'] ?? 0;

        Servicio::create($datos);

        return redirect()->route('servicios.index')->with('exito', 'Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $datos = $request->validate([
            'ser_titulo' => 'required|string|max:255',
            'ser_descripcion' => 'nullable|string',
            'ser_imagen' => 'nullable|image|max:2048',
            'ser_direccion' => 'nullable|string|max:255',
            'ser_telefono_whatsapp' => 'nullable|string|max:20',
            'ser_capacidad' => 'nullable|integer',
            'ser_orden' => 'nullable|integer',
            'ser_estado' => 'nullable|boolean',
        ]);

        if ($request->hasFile('ser_imagen')) {
            if ($servicio->ser_imagen) {
                Storage::disk(config('filesystems.default'))->delete($servicio->ser_imagen);
            }
            $datos['ser_imagen'] = $request->file('ser_imagen')->store('servicios', config('filesystems.default'));
        }

        $datos['ser_estado'] = $request->boolean('ser_estado', true);

        $servicio->update($datos);

        return redirect()->route('servicios.index')->with('exito', 'Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        if ($servicio->ser_imagen) {
            Storage::disk(config('filesystems.default'))->delete($servicio->ser_imagen);
        }

        $servicio->delete();

        return redirect()->route('servicios.index')->with('exito', 'Servicio eliminado correctamente.');
    }
}
