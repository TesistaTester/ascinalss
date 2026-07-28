<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\FilialController;
use App\Http\Controllers\InformeAnualController;
use App\Http\Controllers\CategoriaPrestamoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DocumentoPrestamoController;
use App\Http\Controllers\PublicoController;

/*
----------------------------------------
* RUTAS: PÚBLICAS (sitio institucional)
----------------------------------------
*/
Route::get('/', [PublicoController::class, 'index'])->name('publico.inicio');
Route::get('/web/filiales', [PublicoController::class, 'filiales'])->name('publico.filiales');
Route::get('/web/comunicados', [PublicoController::class, 'comunicados'])->name('publico.comunicados');
Route::get('/web/informe-anual', [PublicoController::class, 'informeAnual'])->name('publico.informe-anual');
/*
----------------------------------------
* RUTAS: AUTENTICACIÓN
----------------------------------------
*/
Route::get('/acceso', function () {
    return view('publico.login', ['titulo' => 'Acceso al sistema']);
})->name('login');

Route::get('/logout', [AuthController::class, 'logout']);
Route::post('/auth', [AuthController::class, 'autenticar']);

/*
----------------------------------------
* RUTAS: DASHBOARD (protegidas)
----------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Editor (2) y Admin (1) pueden gestionar contenido público
    Route::middleware('rol:1,2')->group(function () {
        Route::resource('/comunicados', ComunicadoController::class);
        Route::resource('/servicios', ServicioController::class);
        Route::resource('/convenios', ConvenioController::class);
        Route::resource('/filiales', FilialController::class);
        Route::resource('/informes-anuales', InformeAnualController::class)
            ->parameters(['informes-anuales' => 'informe']);
        Route::resource('/categorias-prestamo', CategoriaPrestamoController::class)
            ->parameters(['categorias-prestamo' => 'categoriasPrestamo']);

        Route::post('/categorias-prestamo/{categoriasPrestamo}/documentos', [DocumentoPrestamoController::class, 'store'])
            ->name('documentos-prestamo.store');
        Route::delete('/documentos-prestamo/{documento}', [DocumentoPrestamoController::class, 'destroy'])
            ->name('documentos-prestamo.destroy');
    });

    // Solo Admin (1) gestiona usuarios
    Route::middleware('rol:1')->group(function () {
        Route::resource('/usuarios', UsuarioController::class);
    });

    // Directorio (3) y Admin (1) ven el dashboard con reportes/resumen
    // (ya cubierto arriba con el 'auth' general, sin restricción extra,
    // ya que el dashboard es de solo lectura)
});