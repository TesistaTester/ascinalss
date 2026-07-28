<?php
// app/Http/Controllers/CategoriaPrestamoController.php

namespace App\Http\Controllers;

use App\Models\CategoriaPrestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriaPrestamoController extends Controller
{
    public function index()
    {
        $categorias = CategoriaPrestamo::withCount('documentos')->orderBy('cat_orden')->paginate(15);
        return view('admin.categorias-prestamo.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias-prestamo.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'cat_nombre' => 'required|string|max:255',
            'cat_descripcion' => 'nullable|string',
            'cat_icono' => 'nullable|string|max:100',
            'cat_orden' => 'nullable|integer',
            'cat_estado' => 'nullable|boolean',
        ]);

        $datos['cat_slug'] = Str::slug($datos['cat_nombre']);
        $datos['cat_estado'] = $request->boolean('cat_estado', true);
        $datos['cat_orden'] = $datos['cat_orden'] ?? 0;

        CategoriaPrestamo::create($datos);

        return redirect()->route('categorias-prestamo.index')->with('exito', 'Categoría creada correctamente.');
    }

    public function edit(CategoriaPrestamo $categoriasPrestamo)
    {
        $categoriasPrestamo->load('documentos');
        return view('admin.categorias-prestamo.edit', ['categoria' => $categoriasPrestamo]);
    }

    public function update(Request $request, CategoriaPrestamo $categoriasPrestamo)
    {
        $datos = $request->validate([
            'cat_nombre' => 'required|string|max:255',
            'cat_descripcion' => 'nullable|string',
            'cat_icono' => 'nullable|string|max:100',
            'cat_orden' => 'nullable|integer',
            'cat_estado' => 'nullable|boolean',
        ]);

        $datos['cat_slug'] = Str::slug($datos['cat_nombre']);
        $datos['cat_estado'] = $request->boolean('cat_estado', true);

        $categoriasPrestamo->update($datos);

        return redirect()->route('categorias-prestamo.index')->with('exito', 'Categoría actualizada correctamente.');
    }

    public function destroy(CategoriaPrestamo $categoriasPrestamo)
    {
        // Los documentos asociados se eliminan en cascada a nivel BD
        $categoriasPrestamo->delete();

        return redirect()->route('categorias-prestamo.index')->with('exito', 'Categoría eliminada correctamente.');
    }
}