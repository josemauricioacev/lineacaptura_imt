<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // POST /inicio/next -> recibe 155/001, los guarda y pasa a /seleccion
    public function inicioNext(Request $request)
    {
        $cve = $request->input('cve_dependencia', '155');
        $uad = $request->input('unidad_administrativa', '001');

        $request->session()->put('wizard.data.dependencia', [
            'cve_dependencia'       => $cve,
            'unidad_administrativa' => $uad,
        ]);

        $request->session()->put('wizard.current_step', 1);
        return redirect()->route('seleccion');
    }

    // GET /seleccion  -> carga conceptos desde BD y muestra la vista
    public function seleccion(Request $request)
    {
        $dep = $request->session()->get('wizard.data.dependencia', [
            'cve_dependencia'       => '155',
            'unidad_administrativa' => '001',
        ]);

        $conceptos = DB::table('cat_conceptos_imt')
            ->select('id', 'homoclave', 'descripcion', 'importe')
            ->where('cve_dependencia', $dep['cve_dependencia'])
            ->where('unidad_administrativa', $dep['unidad_administrativa'])
            ->where('activo', 1)
            ->orderBy('descripcion')
            ->get();

        return view('seleccion', compact('conceptos'));
    }

    // POST /seleccion/next -> valida concepto elegido y pasa a /informacion
    public function seleccionNext(Request $request)
    {
        $data = $request->validate([
            'concepto_id' => 'required|exists:cat_conceptos_imt,id',
        ], [
            'concepto_id.required' => 'Seleccione un concepto para continuar.',
            'concepto_id.exists'   => 'El concepto seleccionado no existe.',
        ]);

        $row = DB::table('cat_conceptos_imt')
            ->select('id','homoclave','descripcion','importe')
            ->where('id', $data['concepto_id'])
            ->first();

        $request->session()->put('wizard.data.concepto', [
            'id'          => $row->id,
            'homoclave'   => $row->homoclave,
            'descripcion' => $row->descripcion,
            'importe'     => (int)$row->importe, // centavos
        ]);

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

        $base = ['tipoPersona' => 'required|in:Persona Física,Persona Moral'];

        if ($tipo === 'Persona Moral') {
            $rules = $base + [
                'razon' => 'required|string|max:255',
                'rfc'   => ['required','string','regex:/^[A-ZÑ&]{3}\d{6}[A-Z0-9]{3}$/i'],
                'curp'    => 'nullable|string|max:18',
                'nombres' => 'nullable|string|max:120',
                'ap1'     => 'nullable|string|max:120',
                'ap2'     => 'nullable|string|max:120',
            ];
        } else {
            $rules = $base + [
                'curp'    => ['required','string','max:18','regex:/^([A-Z][AEIOUX][A-Z]{2})(\d{2})(\d{2})(\d{2})([HM])(AS|BC|BS|CC|CL|CM|CS|CH|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TL|TS|VZ|YN|ZS|NE)([B-DF-HJ-NP-TV-Z]{3})([A-Z0-9])(\d)$/i'],
                'rfc'     => ['required','string','regex:/^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$/i'],
                'nombres' => 'required|string|max:120',
                'ap1'     => 'required|string|max:120',
                'ap2'     => 'nullable|string|max:120',
                'razon'   => 'nullable|string|max:255',
            ];
        }

        $data = $request->validate($rules, $messages);

        foreach (['rfc','curp','razon'] as $k) {
            if (!empty($data[$k])) $data[$k] = mb_strtoupper(trim($data[$k]), 'UTF-8');
        }

        if (!empty($data['rfc'])) {
            $letras = strspn($data['rfc'], 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ&');
            if ($tipo === 'Persona Física' && $letras !== 4) {
                return back()->withErrors(['rfc' => 'El RFC no corresponde a una Persona Física'])->withInput();
            }
            if ($tipo === 'Persona Moral' && $letras !== 3) {
                return back()->withErrors(['rfc' => 'El RFC no corresponde a una Persona Moral'])->withInput();
            }
        }

        if ($tipo === 'Persona Moral') {
            $request->session()->put('wizard.data.persona', [
                'tipoPersona' => 'Persona Moral',
                'rfc'         => $data['rfc'] ?? null,
                'razon'       => $data['razon'] ?? null,
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
                'razon'       => null,
            ]);
        }

        $request->session()->put('wizard.current_step', 3);
        return redirect()->route('pago');
    }

    // POST /informacion/back -> regresa a selección
    public function informacionBack(Request $request)
    {
        $request->session()->forget(['errors']);
        $request->session()->put('wizard.current_step', 1);
        return redirect()->route('seleccion');
    }

    // GET /pago
    public function pago(Request $request)
    {
        $wizard   = $request->session()->get('wizard.data', []);
        $concepto = $wizard['concepto'] ?? null;
        return view('pago', compact('wizard','concepto'));
    }

    // POST /pago/back -> regresa a información
    public function pagoBack(Request $request)
    {
        $request->session()->put('wizard.current_step', 2);
        return redirect()->route('informacion');
    }

    // POST /pago/generar -> calcula totales y muestra RESUMEN
    public function generar(Request $request)
    {
        $wizard   = $request->session()->get('wizard.data', []);
        $dep      = $wizard['dependencia'] ?? null;
        $persona  = $wizard['persona'] ?? null;
        $concepto = $wizard['concepto'] ?? null;

        // Validaciones mínimas
        if (!$dep || !$concepto) {
            return back()->with('wizard_error', 'Faltan datos para generar la línea de captura.');
        }

        $cantidad = max(1, (int)$request->input('cantidad', 1));
        $unit     = (int)($concepto['importe'] ?? 0); // centavos

        $subtotal = $unit * $cantidad;
        $iva      = (int) round($subtotal * 0.16);
        $total    = $subtotal + $iva;

        // Fechas (ejemplo): hoy y +15 días de vigencia
        $now     = now();
        $vigencia= now()->addDays(15);

        // (Opcional) ID de solicitud “amigable”: DDD UAD AA + consecutivo ficticio
        $idSolicitud = sprintf(
            '%03d%03d%s%08d',
            (int)($dep['cve_dependencia'] ?? 155),
            (int)($dep['unidad_administrativa'] ?? 1),
            $now->format('y'),
            random_int(1, 99999999)
        );

        $resumen = [
            'dependencia' => [
                'CveDependencia'       => $dep['cve_dependencia'] ?? '155',
                'UnidadAdministrativa' => $dep['unidad_administrativa'] ?? '001',
            ],
            'persona' => $persona, // tal cual lo guardaste (PF o PM)
            'concepto' => [
                'homoclave'   => $concepto['homoclave'] ?? '',
                'descripcion' => $concepto['descripcion'] ?? '',
                'importe'     => $unit,     // centavos
                'cantidad'    => $cantidad,
                'subtotal'    => $subtotal, // centavos
                'iva'         => $iva,      // centavos
                'total'       => $total,    // centavos
            ],
            'fechas' => [
                'solicitud' => $now,
                'vigencia'  => $vigencia,
            ],
            'control' => [
                'id_solicitud' => $idSolicitud,
                'moneda'       => 'MXN',
            ],
        ];

        // En este punto podrías persistir en tus tablas (lineas_captura, etc.)
        // y generar la LC real. Por ahora mostramos el RESUMEN.
        return view('linea_captura', compact('resumen'));
    }
}
