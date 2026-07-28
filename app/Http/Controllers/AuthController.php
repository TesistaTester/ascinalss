<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function autenticar(Request $request)
    {
        $credenciales = $request->validate([
            'uuo' => 'required',
            'ovc' => 'required',
        ]);

        $usr = $request->input('uuo');
        $pwd = $request->input('ovc');

        if (Auth::attempt(['usu_nombre' => $usr, 'password' => $pwd])) {
            $request->session()->regenerate();
            $usuario = Auth::user();

            if ($usuario->usu_rol == '1') { // ADMIN
                return redirect('dashboard');
            }
            if ($usuario->usu_rol == '2') { // EDITOR
                return redirect('comunicados');
            }
            if ($usuario->usu_rol == '3') { // DIRECTORIO
                return redirect('dashboard');
            }
        }

        return redirect('/acceso');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/acceso');
    }
}