<?php
// app/Http/Controllers/UsuarioController.php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::orderBy('usu_nombre_completo')->paginate(15);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'usu_rol' => 'required|integer|in:1,2,3',
            'usu_nombre' => 'required|string|max:255|unique:usuarios,usu_nombre',
            'password' => 'required|string|min:6',
            'usu_nombre_completo' => 'required|string|max:255',
            'usu_estado' => 'nullable|boolean',
        ]);

        $datos['password'] = Hash::make($datos['password']);
        $datos['usu_estado'] = $request->boolean('usu_estado', true);

        Usuario::create($datos);

        return redirect()->route('usuarios.index')->with('exito', 'Usuario creado correctamente.');
    }

    public function edit(Usuario $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $datos = $request->validate([
            'usu_rol' => 'required|integer|in:1,2,3',
            'usu_nombre' => 'required|string|max:255|unique:usuarios,usu_nombre,' . $usuario->usu_id . ',usu_id',
            'password' => 'nullable|string|min:6',
            'usu_nombre_completo' => 'required|string|max:255',
            'usu_estado' => 'nullable|boolean',
        ]);

        if (!empty($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
        } else {
            unset($datos['password']);
        }

        $datos['usu_estado'] = $request->boolean('usu_estado', true);

        $usuario->update($datos);

        return redirect()->route('usuarios.index')->with('exito', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $usuario)
    {
        // Evitar que el usuario se elimine a sí mismo
        if ($usuario->usu_id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('exito', 'Usuario eliminado correctamente.');
    }
}