<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Controla el flujo del wizard sin mostrar mensajes.
 * Firma: ->middleware('wizard.nav:step,intent')
 *  - step: inicio|seleccion|informacion|pago
 *  - intent:
 *      view = ver el paso (GET)
 *      next = avanzar a este paso (POST desde el paso anterior)
 *      back = retroceder a este paso (POST desde el paso siguiente)
 *      stay = acciones internas del mismo paso (POST dentro del paso)
 */
class EnsureWizardNav
{
    private array $order = ['inicio'=>0,'seleccion'=>1,'informacion'=>2,'pago'=>3];

    public function handle(Request $request, Closure $next, string $step, string $intent = 'view')
    {
        if (!array_key_exists($step, $this->order)) {
            abort(404);
        }

        $current = (int)$request->session()->get('wizard.current_step', 0);
        $target  = (int)$this->order[$step];

        // Reglas:
        // 1) GET view: solo puedes ver el paso actual. Cualquier otro -> redirige silencioso al actual.
        if ($intent === 'view') {
            if ($request->method() !== 'GET') abort(405);
            if ($target !== $current) {
                return redirect()->route($this->routeFor($current));
            }
            return $next($request);
        }

        // 2) POST next: solo válido si target == current + 1
        if ($intent === 'next') {
            if ($request->method() !== 'POST') abort(405);
            if ($target !== $current + 1) {
                return redirect()->route($this->routeFor($current));
            }
            return $next($request);
        }

        // 3) POST back: solo válido si target == current - 1
        if ($intent === 'back') {
            if ($request->method() !== 'POST') abort(405);
            if ($target !== $current - 1) {
                return redirect()->route($this->routeFor($current));
            }
            return $next($request);
        }

        // 4) POST stay: acciones internas del mismo paso (e.g., generar)
        if ($intent === 'stay') {
            if ($request->method() !== 'POST') abort(405);
            if ($target !== $current) {
                return redirect()->route($this->routeFor($current));
            }
            return $next($request);
        }

        // Intent no soportado
        abort(404);
    }

    private function routeFor(int $idx): string
    {
        return match ($idx) {
            0 => 'inicio',
            1 => 'seleccion',
            2 => 'informacion',
            3 => 'pago',
            default => 'inicio',
        };
    }
}
