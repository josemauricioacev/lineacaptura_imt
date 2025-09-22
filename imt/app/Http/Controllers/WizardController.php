<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WizardController extends Controller
{
    /**
     * GET /
     * Pantalla inicial. Limpia la sesión del asistente y prepara el flujo.
     */
    public function inicio(Request $request)
    {
        // Reset seguro
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->put('wizard.current_step', 0);
        $request->session()->forget(['wizard.data']);

        // Lee la dependencia desde BD (si existe) o usa defaults
        $depRow = DB::table('dependencia')->first();
        $dependencia = [
            'clave_dependencia'       => (string)($depRow->clave_dependencia ?? '155'),
            'unidad_administrativa'   => (string)($depRow->unidad_administrativa ?? '001'),
            'nombre'                  => mb_strtoupper($depRow->nombre ?? 'INSTITUTO MEXICANO DEL TRANSPORTE', 'UTF-8'),
        ];

        return view('inicio', compact('dependencia'));
    }

    /**
     * POST /inicio/next
     * Guarda en sesión la dependencia seleccionada y avanza a Selección.
     */
    public function inicioNext(Request $request)
    {
        // Consolidar con lo que esté en BD (si hubiera un único registro)
        $depRow = DB::table('dependencia')->first();

        $cve = $request->input('cve_dependencia', $depRow->clave_dependencia ?? '155');
        $uad = $request->input('unidad_administrativa', $depRow->unidad_administrativa ?? '001');
        $nom = $depRow->nombre ?? 'INSTITUTO MEXICANO DEL TRANSPORTE';

        $request->session()->put('wizard.data.dependencia', [
            'cve_dependencia'       => (string) $cve,
            'unidad_administrativa' => (string) $uad,
            'nombre'                => mb_strtoupper($nom, 'UTF-8'),
        ]);

        $request->session()->put('wizard.current_step', 1);
        return redirect()->route('seleccion');
    }

    /**
     * GET /seleccion
     * Lista de conceptos (tabla 'conceptos') para elegir el trámite.
     */
    public function seleccion(Request $request)
    {
        $dep = $request->session()->get('wizard.data.dependencia', [
            'cve_dependencia'       => '155',
            'unidad_administrativa' => '001',
            'nombre'                => 'INSTITUTO MEXICANO DEL TRANSPORTE',
        ]);

        $conceptos = DB::table('conceptos')
            ->select('id_concepto as id', 'homoclave', 'descripcion', 'importe')
            ->orderBy('descripcion')
            ->get();

        return view('seleccion', compact('conceptos'));
    }

    /**
     * POST /seleccion/next
     * Valida el concepto elegido y lo guarda en sesión.
     */
    public function seleccionNext(Request $request)
    {
        $data = $request->validate([
            'concepto_id' => 'required|exists:conceptos,id_concepto',
        ], [
            'concepto_id.required' => 'Seleccione un concepto para continuar.',
            'concepto_id.exists'   => 'El concepto seleccionado no existe.',
        ]);

        $row = DB::table('conceptos')
            ->select('id_concepto as id', 'homoclave', 'descripcion', 'importe')
            ->where('id_concepto', $data['concepto_id'])
            ->first();

        $homoclave   = mb_strtoupper($row->homoclave ?? '', 'UTF-8');
        $descripcion = mb_strtoupper($row->descripcion ?? '', 'UTF-8');

        $request->session()->put('wizard.data.concepto', [
            'id'          => (int) $row->id,
            'homoclave'   => $homoclave,
            'descripcion' => $descripcion,
            'importe'     => (int) $row->importe, // centavos
        ]);

        $request->session()->put('wizard.current_step', 2);
        return redirect()->route('informacion');
    }

    /**
     * POST /seleccion/back
     * Regresa a la pantalla de inicio.
     */
    public function seleccionBack(Request $request)
    {
        $request->session()->put('wizard.current_step', 0);
        return redirect()->route('inicio');
    }

    /**
     * GET /informacion
     * Muestra el formulario para capturar datos de la persona (PF/PM).
     */
    public function informacion(Request $request)
    {
        return view('informacion');
    }

    /**
     * POST /informacion/next
     * Valida y persiste/actualiza información en 'informacion_personal' (ENUM FISICA/MORAL).
     */
    public function informacionNext(Request $request)
    {
        // Ahora la UI manda FISICA | MORAL para que coincida con el ENUM de la BD
        $tipo = $request->input('tipoPersona'); // FISICA | MORAL

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

        $base = ['tipoPersona' => 'required|in:FISICA,MORAL'];

        if ($tipo === 'MORAL') {
            $rules = $base + [
                'razon'   => 'required|string|max:255',
                'rfc'     => ['required','string','regex:/^[A-ZÑ&]{3}\d{6}[A-Z0-9]{3}$/i'],
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

        foreach (['rfc','curp','razon','nombres','ap1','ap2'] as $k) {
            if (!empty($data[$k])) {
                $data[$k] = mb_strtoupper(trim($data[$k]), 'UTF-8');
            }
        }

        if (!empty($data['rfc'])) {
            $soloLetras = strspn($data['rfc'], 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ&');
            if ($tipo === 'FISICA' && $soloLetras !== 4) {
                return back()->withErrors(['rfc' => 'El RFC no corresponde a una Persona Física'])->withInput();
            }
            if ($tipo === 'MORAL' && $soloLetras !== 3) {
                return back()->withErrors(['rfc' => 'El RFC no corresponde a una Persona Moral'])->withInput();
            }
        }

        $now = now();

        DB::beginTransaction();
        try {
            $personaId = null;

            // 1) Busca por RFC
            if (!empty($data['rfc'])) {
                $found = DB::table('informacion_personal')->where('rfc', $data['rfc'])->first();
                if ($found) {
                    $update = [
                        'tipo_persona'   => $tipo, // FISICA | MORAL
                        'updated_at'     => $now,
                    ];

                    if ($tipo === 'MORAL') {
                        $update += [
                            'curp'            => null,
                            'nombres'         => null,
                            'apellido_paterno'=> null,
                            'apellido_materno'=> null,
                            'razon_social'    => $data['razon'] ?? null,
                        ];
                    } else {
                        $update += [
                            'curp'            => $data['curp'] ?? null,
                            'nombres'         => $data['nombres'] ?? null,
                            'apellido_paterno'=> $data['ap1'] ?? null,
                            'apellido_materno'=> $data['ap2'] ?? null,
                            'razon_social'    => null,
                        ];
                    }

                    DB::table('informacion_personal')->where('id_informacion', $found->id_informacion)->update($update);
                    $personaId = (int) $found->id_informacion;
                }
            }

            // 2) Si es FÍSICA y no hubo coincidencia por RFC, intenta por CURP
            if (!$personaId && $tipo === 'FISICA' && !empty($data['curp'])) {
                $foundByCurp = DB::table('informacion_personal')->where('curp', $data['curp'])->first();
                if ($foundByCurp) {
                    $update = [
                        'tipo_persona'     => $tipo,
                        'rfc'              => $data['rfc'],
                        'nombres'          => $data['nombres'] ?? null,
                        'apellido_paterno' => $data['ap1'] ?? null,
                        'apellido_materno' => $data['ap2'] ?? null,
                        'razon_social'     => null,
                        'updated_at'       => $now,
                    ];
                    DB::table('informacion_personal')->where('id_informacion', $foundByCurp->id_informacion)->update($update);
                    $personaId = (int) $foundByCurp->id_informacion;
                }
            }

            // 3) Inserta si no existe
            if (!$personaId) {
                if ($tipo === 'MORAL') {
                    $personaId = DB::table('informacion_personal')->insertGetId([
                        'tipo_persona'      => 'MORAL',
                        'curp'              => null,
                        'rfc'               => $data['rfc'],
                        'nombres'           => null,
                        'apellido_paterno'  => null,
                        'apellido_materno'  => null,
                        'razon_social'      => $data['razon'] ?? null,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ]);
                } else {
                    $personaId = DB::table('informacion_personal')->insertGetId([
                        'tipo_persona'      => 'FISICA',
                        'curp'              => $data['curp'],
                        'rfc'               => $data['rfc'],
                        'nombres'           => $data['nombres'] ?? null,
                        'apellido_paterno'  => $data['ap1'] ?? null,
                        'apellido_materno'  => $data['ap2'] ?? null,
                        'razon_social'      => null,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ]);
                }
            }

            DB::commit();

            // Guarda en sesión un snapshot de persona
            if ($tipo === 'MORAL') {
                $request->session()->put('wizard.data.persona', [
                    'id'          => $personaId,
                    'tipoPersona' => 'MORAL',
                    'rfc'         => $data['rfc'] ?? null,
                    'razon'       => $data['razon'] ?? null,
                    'curp'        => null,
                    'nombres'     => null,
                    'ap1'         => null,
                    'ap2'         => null,
                ]);
            } else {
                $request->session()->put('wizard.data.persona', [
                    'id'          => $personaId,
                    'tipoPersona' => 'FISICA',
                    'rfc'         => $data['rfc'] ?? null,
                    'curp'        => $data['curp'] ?? null,
                    'nombres'     => $data['nombres'] ?? null,
                    'ap1'         => $data['ap1'] ?? null,
                    'ap2'         => $data['ap2'] ?? null,
                    'razon'       => null,
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['general' => 'No fue posible guardar la información.'])->withInput();
        }

        $request->session()->put('wizard.current_step', 3);
        return redirect()->route('pago');
    }

    /**
     * POST /informacion/back
     * Regresa a Selección.
     */
    public function informacionBack(Request $request)
    {
        $request->session()->forget(['errors']);
        $request->session()->put('wizard.current_step', 1);
        return redirect()->route('seleccion');
    }

    /**
     * GET /pago
     * Muestra el formato de pago. Lee de sesión el concepto y la persona.
     */
    public function pago(Request $request)
    {
        $wizard   = $request->session()->get('wizard.data', []);
        $concepto = $wizard['concepto'] ?? null;

        return view('pago', compact('wizard', 'concepto'));
    }

    /**
     * POST /pago/back
     * Regresa a Información.
     */
    public function pagoBack(Request $request)
    {
        $request->session()->put('wizard.current_step', 2);
        return redirect()->route('informacion');
    }

    /**
     * POST /pago/generar
     * Calcula totales, PERSISTE la línea de captura en BD y arma el resumen.
     */
    public function generar(Request $request)
    {
        $wizard   = $request->session()->get('wizard.data', []);
        $dep      = $wizard['dependencia'] ?? null;
        $persona  = $wizard['persona'] ?? null;
        $concepto = $wizard['concepto'] ?? null;

        if (!$dep || !$concepto) {
            return back()->with('wizard_error', 'Faltan datos para generar la línea de captura.');
        }

        $cantidad = max(1, (int) $request->input('cantidad', 1));
        $unit     = (int) ($concepto['importe'] ?? 0); // centavos

        $subtotal = $unit * $cantidad;
        $iva      = (int) round($subtotal * 0.16);
        $total    = $subtotal + $iva;

        $now      = now();
        $vigencia = now()->addDays(30);

        // Folio amigable/id_solicitud
        $idSolicitud = sprintf(
            '%03d%03d%s%08d',
            (int)($dep['cve_dependencia'] ?? 155),
            (int)($dep['unidad_administrativa'] ?? 1),
            $now->format('y'),
            random_int(1, 99999999)
        );

        // ===== PERSISTENCIA EN BD: linea_captura =====
        $lineaId = null;
        try {
            $payload = [
                'id_informacion'            => $persona['id'] ?? null,
                'id_concepto'               => $concepto['id'] ?? null,
                'clave_dependencia'         => (string)($dep['cve_dependencia'] ?? '155'),
                'unidad_administrativa'     => (string)($dep['unidad_administrativa'] ?? '001'),
                // NUEVO: guardamos también el nombre de la dependencia
                'dep_nombre'                => mb_strtoupper((string)($dep['nombre'] ?? 'INSTITUTO MEXICANO DEL TRANSPORTE'), 'UTF-8'),
                'estado'                    => 'PENDIENTE', // PENDIENTE | PAGADO | NO_PAGADO
                'homoclave'                 => $concepto['homoclave'] ?? null,
                'descripcion'               => $concepto['descripcion'] ?? null,
                'importe_unitario_centavos' => $unit,
                'cantidad'                  => $cantidad,
                'subtotal_centavos'         => $subtotal,
                'iva_centavos'              => $iva,
                'total_centavos'            => $total,
                'fecha_generacion'          => $now,
                'fecha_vencimiento'         => $vigencia,
                'id_solicitud'              => $idSolicitud,
                'moneda'                    => 'MXN',
                'created_at'                => $now,
                'updated_at'                => $now,
            ];

            $lineaId = DB::table('linea_captura')->insertGetId($payload);
        } catch (\Throwable $e) {
            session()->flash('wizard_error', 'La línea se generó, pero no se pudo guardar en la BD. Revisa las columnas de la tabla linea_captura.');
        }

        // Resumen para la vista final (incluye id_linea si se guardó)
        $resumen = [
            'dependencia' => [
                'CveDependencia'       => $dep['cve_dependencia'] ?? '155',
                'UnidadAdministrativa' => $dep['unidad_administrativa'] ?? '001',
                'Nombre'               => $dep['nombre'] ?? 'INSTITUTO MEXICANO DEL TRANSPORTE',
            ],
            'persona' => $persona,
            'concepto' => [
                'homoclave'   => $concepto['homoclave'] ?? '',
                'descripcion' => $concepto['descripcion'] ?? '',
                'importe'     => $unit,      // centavos
                'cantidad'    => $cantidad,
                'subtotal'    => $subtotal,  // centavos
                'iva'         => $iva,       // centavos
                'total'       => $total,     // centavos
            ],
            'fechas' => [
                'solicitud' => $now,
                'vigencia'  => $vigencia,
            ],
            'control' => [
                'id_solicitud'    => $idSolicitud,
                'id_linea_captura'=> $lineaId,
                'moneda'          => 'MXN',
                'estado'          => 'PENDIENTE',
            ],
        ];

        return view('linea_captura', compact('resumen'));
    }
}
