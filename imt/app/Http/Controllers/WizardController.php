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
        $tipo = $request->input('tipoPersona');

        $messages = [
            'tipoPersona.required' => 'Selecciona un tipo de persona.',
            'tipoPersona.in'       => 'El tipo de persona no es válido.',

            'curp.required'        => 'La CURP es obligatoria.',
            'curp.regex'           => 'La CURP no tiene un formato válido.',

            'rfc.required'         => 'El RFC es obligatorio.',
            'rfc.regex'            => 'El RFC no tiene un formato válido para el tipo seleccionado.',

            'nombres.required'     => 'El nombre es obligatorio.',
            'ap1.required'         => 'El primer apellido es obligatorio.',

            'razon.required'       => 'La razón social es obligatoria.',
        ];

        // Base
        $base = ['tipoPersona' => 'required|in:Persona Física,Persona Moral'];

        // Reglas condicionales según el tipo
        if ($tipo === 'Persona Moral') {
            $rules = $base + [
                // PM
                'razon' => 'required|string|max:255',
                'rfc'   => ['required','string','regex:/^[A-ZÑ&]{3}\d{6}[A-Z0-9]{3}$/i'], // 3 letras + YYMMDD + 3

                // Campos PF como no obligatorios para no bloquear si llegan
                'curp'    => 'nullable|string|max:18',
                'nombres' => 'nullable|string|max:120',
                'ap1'     => 'nullable|string|max:120',
                'ap2'     => 'nullable|string|max:120',
            ];
        } else {
            // Default: Persona Física
            $rules = $base + [
                // PF
                'curp'    => ['required','string','max:18','regex:/^([A-Z][AEIOUX][A-Z]{2})(\d{2})(\d{2})(\d{2})([HM])(AS|BC|BS|CC|CL|CM|CS|CH|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TL|TS|VZ|YN|ZS|NE)([B-DF-HJ-NP-TV-Z]{3})([A-Z0-9])(\d)$/i'],
                'rfc'     => ['required','string','regex:/^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$/i'], // 4 letras + YYMMDD + 3
                'nombres' => 'required|string|max:120',
                'ap1'     => 'required|string|max:120',
                'ap2'     => 'nullable|string|max:120',

                // Campo PM no requerido
                'razon'   => 'nullable|string|max:255',
            ];
        }

        $data = $request->validate($rules, $messages);

        // Normaliza RFC y CURP
        if (!empty($data['rfc'])) {
            $data['rfc'] = mb_strtoupper(trim($data['rfc']), 'UTF-8');
        }
        if (!empty($data['curp'])) {
            $data['curp'] = mb_strtoupper(trim($data['curp']), 'UTF-8');
        }
        if (!empty($data['razon'])) {
            $data['razon'] = mb_strtoupper(trim($data['razon']), 'UTF-8');
        }

        // Chequeo adicional (coherencia de letras iniciales). No es estrictamente necesario
        // si los regex de arriba están, pero mantiene el mensaje específico.
        if (!empty($data['rfc'])) {
            $letras = strspn($data['rfc'], 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ&');
            if ($tipo === 'Persona Física' && $letras !== 4) {
                return back()
                    ->withErrors(['rfc' => 'El RFC no corresponde a una Persona Física'])
                    ->withInput();
            }
            if ($tipo === 'Persona Moral' && $letras !== 3) {
                return back()
                    ->withErrors(['rfc' => 'El RFC no corresponde a una Persona Moral'])
                    ->withInput();
            }
        }

        // Guarda en sesión solo lo que corresponde a cada tipo
        if ($tipo === 'Persona Moral') {
            $request->session()->put('wizard.data.persona', [
                'tipoPersona' => 'Persona Moral',
                'rfc'         => $data['rfc'] ?? null,
                'razon'       => $data['razon'] ?? null,
                // Campos PF en null
                'curp'        => null,
                'nombres'     => null,
                'ap1'         => null,
                'ap2'         => null,
            ]);
        } else {
            $request->session()->put('wizard.data.persona', [
                'tipoPersona' => 'Persona Física',
                'rfc'         => $data['rfc'] ?? null,
                'curp'        => $data['curp'] ?? null,
                'nombres'     => $data['nombres'] ?? null,
                'ap1'         => $data['ap1'] ?? null,
                'ap2'         => $data['ap2'] ?? null,
                // Campo PM en null
                'razon'       => null,
            ]);
        }

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
