<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WizardController;

Route::middleware(['web'])->group(function () {

    // INICIO
    Route::get('/', [WizardController::class, 'inicio'])
        ->name('inicio')
        ->middleware('wizard.nav:inicio,view');

    Route::post('/inicio/next', [WizardController::class, 'inicioNext'])
        ->name('inicio.next')
        ->middleware(['wizard.nav:seleccion,next','throttle:30,1']);

    // SELECCIÓN
    Route::get('/seleccion', [WizardController::class, 'seleccion'])
        ->name('seleccion')
        ->middleware('wizard.nav:seleccion,view');

    Route::post('/seleccion/next', [WizardController::class, 'seleccionNext'])
        ->name('seleccion.next')
        ->middleware(['wizard.nav:informacion,next','throttle:30,1']);

    Route::post('/seleccion/back', [WizardController::class, 'seleccionBack'])
        ->name('seleccion.back')
        ->middleware('wizard.nav:inicio,back');

    // INFORMACIÓN
    Route::get('/informacion', [WizardController::class, 'informacion'])
        ->name('informacion')
        ->middleware('wizard.nav:informacion,view');

    Route::post('/informacion/next', [WizardController::class, 'informacionNext'])
        ->name('informacion.next')
        ->middleware(['wizard.nav:pago,next','throttle:30,1']);

    Route::post('/informacion/back', [WizardController::class, 'informacionBack'])
        ->name('informacion.back')
        ->middleware('wizard.nav:seleccion,back');

    // PAGO
    Route::get('/pago', [WizardController::class, 'pago'])
        ->name('pago')
        ->middleware('wizard.nav:pago,view');

    Route::post('/pago/generar', [WizardController::class, 'generar'])
        ->name('pago.generar')
        ->middleware(['wizard.nav:pago,stay','throttle:30,1']);

    Route::post('/pago/back', [WizardController::class, 'pagoBack'])
        ->name('pago.back')
        ->middleware('wizard.nav:informacion,back');
});
