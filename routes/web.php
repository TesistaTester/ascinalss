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
use App\Http\Controllers\SimuladorPrestamoController;
use App\Http\Controllers\ConfiguracionSimuladorController;
use App\Http\Controllers\AporteAfiliadoController;

/*
----------------------------------------
* RUTAS: PÚBLICAS (sitio institucional)
----------------------------------------
*/
Route::get('/', [PublicoController::class, 'index'])->name('publico.inicio');
Route::get('/web/filiales', [PublicoController::class, 'filiales'])->name('publico.filiales');
Route::get('/web/comunicados', [PublicoController::class, 'comunicados'])->name('publico.comunicados');
Route::get('/web/informe-anual', [PublicoController::class, 'informeAnual'])->name('publico.informe-anual');
Route::post('/prestamos/simular', [SimuladorPrestamoController::class, 'simular'])->middleware('throttle:30,1')->name('prestamos.simular');
Route::get('/web/prestamos/plan/{simulacion}', [SimuladorPrestamoController::class, 'plan'])->whereUuid('simulacion')->name('prestamos.plan');

/*
----------------------------------------
* RUTAS: CONSULTA DE APORTES (Afiliados)
----------------------------------------
*/
Route::prefix('aportes')->name('aportes.')->group(function () {
    Route::get('/consulta', [AporteAfiliadoController::class, 'formularioLogin'])->name('login');
    Route::post('/consulta', [AporteAfiliadoController::class, 'autenticar'])->middleware('throttle:15,1')->name('autenticar');
    Route::get('/estado-cuenta', [AporteAfiliadoController::class, 'estadoCuenta'])->name('estado-cuenta');
    Route::post('/salir', [AporteAfiliadoController::class, 'cerrarSesion'])->name('salir');
});
/*
----------------------------------------
* RUTAS: AUTENTICACIÓN
----------------------------------------
*/
Route::get('/acceso', function () {
    return view('publico.login', ['titulo' => 'Acceso al sistema']);
})->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth', [AuthController::class, 'autenticar'])->middleware('throttle:5,1')->name('auth.login');

/*
----------------------------------------
* RUTAS: DASHBOARD (protegidas)
----------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('rol:1,2,3')->name('dashboard');

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
        Route::get('/configuracion/simulador-prestamos', [ConfiguracionSimuladorController::class, 'index'])->name('simulador-prestamos.index');
        Route::put('/configuracion/simulador-prestamos/{producto}', [ConfiguracionSimuladorController::class, 'update'])->name('simulador-prestamos.update');
    });

    // Directorio (3) y Admin (1) ven el dashboard con reportes/resumen
    // (ya cubierto arriba con el 'auth' general, sin restricción extra,
    // ya que el dashboard es de solo lectura)
});
