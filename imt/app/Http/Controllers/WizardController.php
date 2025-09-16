<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WizardController extends Controller
{
    // GET /
    public function inicio(Request $request)
    {
        // Reset seguro
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $request->session()->put('wizard.current_step', 0);
        $request->session()->forget(['wizard.data']);

        return view('inicio');
    }

    // POST /inicio/next -> pasa a /seleccion
    public function inicioNext(Request $request)
    {
        $request->session()->put('wizard.current_step', 1);
        return redirect()->route('seleccion');
    }

    // GET /seleccion
    public function seleccion(Request $request)
    {
        return view('seleccion');
    }

    // POST /seleccion/next -> valida y pasa a /informacion
    public function seleccionNext(Request $request)
    {
        $data = $request->validate([
            'tramite' => 'required|in:1,2,3',
        ], [
            'tramite.required' => 'Selecciona un trámite.',
            'tramite.in'       => 'El trámite seleccionado no es válido.',
        ]);

        $request->session()->put('wizard.data.tramite', (int)$data['tramite']);
        $request->session()->put('wizard.current_step', 2);

        return redirect()->route('informacion');
    }

    // POST /seleccion/back -> vuelve a inicio
    public function seleccionBack(Request $request)
    {
        $request->session()->put('wizard.current_step', 0);
        return redirect()->route('inicio');
    }

    // GET /informacion
    public function informacion(Request $request)
    {
        return view('informacion');
    }

    // POST /informacion/next -> valida y pasa a /pago
    public function informacionNext(Request $request)
    {
        $data = $request->validate([
            'tipoPersona' => 'required|in:Persona Física,Persona Moral',
            'curp'        => ['nullable','string','max:18','regex:/^[A-Z][AEIOUX][A-Z]{2}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/i'],
            'rfc'         => ['required','string','max:13','regex:/^([A-Z&Ñ]{3,4})\d{6}[A-Z0-9]{3}$/i'],
            'nombres'     => 'required|string|max:120',
            'ap1'         => 'required|string|max:120',
            'ap2'         => 'nullable|string|max:120',
        ], [
            'tipoPersona.required' => 'Selecciona un tipo de persona.',
            'tipoPersona.in'       => 'El tipo de persona no es válido.',
            'curp.regex'           => 'La CURP no tiene un formato válido.',
            'rfc.required'         => 'El RFC es obligatorio.',
            'rfc.regex'            => 'El RFC no tiene un formato válido.',
            'nombres.required'     => 'El nombre es obligatorio.',
            'ap1.required'         => 'El primer apellido es obligatorio.',
        ]);

        // Normaliza RFC y CURP
        $data['rfc'] = mb_strtoupper(trim($data['rfc']), 'UTF-8');
        if (!empty($data['curp'])) {
            $data['curp'] = mb_strtoupper(trim($data['curp']), 'UTF-8');
        }

        // Valida coherencia RFC con tipoPersona
        $letras = strspn($data['rfc'], 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ&');
        if ($data['tipoPersona'] === 'Persona Física' && $letras !== 4) {
            return back()->withErrors(['rfc' => 'El RFC no corresponde a una Persona Física'])->withInput();
        }
        if ($data['tipoPersona'] === 'Persona Moral' && $letras !== 3) {
            return back()->withErrors(['rfc' => 'El RFC no corresponde a una Persona Moral'])->withInput();
        }

        // Guarda en sesión
        $request->session()->put('wizard.data.persona', [
            'tipoPersona' => $data['tipoPersona'],
            'rfc'         => $data['rfc'],
            'curp'        => $data['curp'] ?? null,
            'nombres'     => $data['nombres'],
            'ap1'         => $data['ap1'],
            'ap2'         => $data['ap2'] ?? null,
        ]);

        $request->session()->put('wizard.current_step', 3);

        return redirect()->route('pago');
    }

    // POST /informacion/back -> regresa a selección
    public function informacionBack(Request $request)
    {
        // ✅ Limpia errores de validación para permitir regresar
        $request->session()->forget(['errors']);
        $request->session()->put('wizard.current_step', 1);
        return redirect()->route('seleccion');
    }

    // GET /pago
    public function pago(Request $request)
    {
        $wizard = $request->session()->get('wizard.data', []);
        return view('pago', compact('wizard'));
    }

    // POST /pago/back -> regresa a información
    public function pagoBack(Request $request)
    {
        $request->session()->put('wizard.current_step', 2);
        return redirect()->route('informacion');
    }

    // POST /pago/generar
    public function generar(Request $request)
    {
        $wizard = $request->session()->get('wizard.data', []);
        $tramite = (int)($wizard['tramite'] ?? 0);
        abort_unless($tramite > 0, 400);

        $tarifas = [1=>100.00, 2=>250.00, 3=>500.00];
        $monto = $tarifas[$tramite] ?? null;
        abort_unless($monto, 422);

        // Aquí deberías implementar la generación real de la línea de captura

        return back()->with('success', 'Línea de captura generada correctamente.');
    }
}
