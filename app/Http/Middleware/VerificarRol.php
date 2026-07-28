<?php
// app/Http/Middleware/VerificarRol.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarRol
{
    /**
     * Verifica que el usuario autenticado tenga uno de los roles permitidos.
     *
     * Uso en rutas: middleware('rol:1') o middleware('rol:1,2') para varios roles.
     */
    public function handle(Request $request, Closure $next, ...$rolesPermitidos)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect('/');
        }

        // Convierte ['1','2'] (strings desde la ruta) a comparación segura
        if (!in_array((string) $usuario->usu_rol, $rolesPermitidos)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}