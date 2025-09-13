<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes – PAGA IMT
|--------------------------------------------------------------------------
| Grupo con middleware 'web' (sesión, CSRF, cookies). Si después
| habilitas login, solo agrega 'auth' al arreglo del middleware.
|--------------------------------------------------------------------------
*/

Route::middleware(['web'])->group(function () {
    // Inicio
    Route::get('/', fn () => view('inicio'))->name('inicio');

    // Paso 1: Selección del trámite
    Route::get('/seleccion', fn () => view('seleccion'))->name('seleccion');

    // Paso 2: Información de la persona
    Route::get('/informacion', fn () => view('informacion'))->name('informacion');

    // Paso 3: Formato de pago
    Route::get('/pago', fn () => view('pago'))->name('pago');
});
