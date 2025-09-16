<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureWizardStep
{
    /**
     * Valida progreso y "pases" (por POST) por paso.
     * Usa wizard_error (flash) para el banner y un "pase de cortesía"
     * para que /informacion muestre el aviso cuando alguien teclea /pago.
     *
     * Uso: ->middleware('wizard.step:seleccion|informacion|pago')
     */
    public function handle(Request $request, Closure $next, string $step)
    {
        // Pase de cortesía (se consume en esta petición si existe)
        $allowOnce = $request->session()->pull('wizard.allow_once');

        // Progreso mínimo (se marcan en el controlador)
        $progress = [
            'seleccion'   => $request->session()->has('wizard.started'),
            'informacion' => $request->session()->has('wizard.step1_done'),
            'pago'        => $request->session()->has('wizard.step2_done'),
        ];

        // Pases por POST (se otorgan al oprimir tus botones)
        $permit = [
            'seleccion'   => $request->session()->has('wizard.can.seleccion'),
            'informacion' => $request->session()->has('wizard.can.informacion'),
            'pago'        => $request->session()->has('wizard.can.pago'),
        ];

        // Paso 1: /seleccion
        if ($step === 'seleccion') {
            if (!$progress['seleccion'] || !$permit['seleccion']) {
                return redirect()->route('inicio')
                    ->with('wizard_error', 'Primero inicia el trámite desde la página principal.');
            }
        }

        // Paso 2: /informacion
        if ($step === 'informacion') {
            if (!$progress['informacion'] || !$permit['informacion']) {
                // Pase de cortesía: deja cargar /informacion una sola vez para mostrar banner
                if ($allowOnce === 'informacion') {
                    return $next($request);
                }

                $msg = $request->session()->get('wizard_error', 'Completa la selección usando el botón Siguiente.');
                return redirect()->route('seleccion')->with('wizard_error', $msg);
            }
        }

        // Paso 3: /pago
        if ($step === 'pago') {
            if (!$progress['pago'] || !$permit['pago']) {
                // Permite que /informacion renderice UNA vez y muestre el banner correcto
                $request->session()->flash('wizard.allow_once', 'informacion');
                return redirect()->route('informacion')
                    ->with('wizard_error', 'Completa la información usando el botón Siguiente.');
            }
        }

        return $next($request);
    }
}
