{{-- resources/views/linea_captura.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Resumen de línea de captura</title>

  <link rel="stylesheet" href="https://framework-gb.cdn.gob.mx/assets/styles/main.css">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{ --gob-primary:#611232; --borde:#e5e5e5; }
    body, h1,h2,h3,h4,h5,h6,p,a,li,label,th,td{
      font-family:"Montserrat",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif!important;
    }
    .titulo-pagina{ color:#111; font-weight:700; margin:8px 0 14px; line-height:1.15; }
    .card{
      border:1px solid var(--borde); border-radius:8px; background:#fff; margin-bottom:16px;
      box-shadow:0 1px 2px rgba(0,0,0,.04);
    }
    .card .card-header{
      padding:10px 14px; border-bottom:1px solid var(--borde); background:#f9f9f9; font-weight:700;
    }
    .card .card-body{ padding:14px; }
    .tabla{ width:100%; border-collapse:collapse; }
    .tabla th, .tabla td{ padding:8px 10px; border-top:1px solid var(--borde); vertical-align:top; }
    .tabla thead th{ border-top:none; color:#444; }
    .mono{
      font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }
    .totales td{ font-weight:600; }
    .cta{ display:flex; gap:10px; justify-content:flex-end; flex-wrap:wrap; }
    .btn, .btn:hover, .btn:focus, .btn:active{ text-decoration:none!important; }
    .btn.btn-primary{ background:#611232; color:#fff!important; border:1px solid #611232; }
    .btn.btn-primary:hover{ background:#4d0e29; border-color:#4d0e29; }
    .btn.btn-default{ background:#fff; border:1px solid #ccc; color:#444!important; }
    .kv{ display:flex; gap:8px; align-items:baseline; }
    .kv strong{ min-width:220px; }
    @media print{
      .no-print{ display:none!important; }
      body{ background:#fff; }
      .card{ page-break-inside:avoid; }
    }
  </style>
</head>
<body>
  <main class="page" role="main" style="margin-top:30px">
    <div class="container">

      <center><h1 class="titulo-pagina">Resumen de la línea de captura</h1></center>
      <p class="text-muted" style="text-align:center;margin-top:-6px;">Revise la información antes de continuar con el pago.</p>

      {{-- Identificación y Dependencia --}}
      <div class="card">
        <div class="card-header">Identificación</div>
        <div class="card-body">
          <table class="tabla">
            <tbody>
              <tr>
                <td style="width:35%"><strong>ID de Solicitud</strong></td>
                <td class="mono">{{ $resumen['control']['id_solicitud'] }}</td>
              </tr>

              {{-- Bloque de Dependencia con NOMBRE + CLAVE + U.A. --}}
              <tr>
                <td><strong>Dependencia</strong></td>
                <td>
                  <div class="kv">
                    <strong>Nombre:</strong>
                    <span>{{ $resumen['dependencia']['Nombre'] ?? 'INSTITUTO MEXICANO DEL TRANSPORTE' }}</span>
                  </div>
                  <div class="kv">
                    <strong>Clave de dependencia:</strong>
                    <span class="mono">{{ $resumen['dependencia']['CveDependencia'] ?? '155' }}</span>
                  </div>
                  <div class="kv">
                    <strong>Unidad administrativa:</strong>
                    <span class="mono">{{ $resumen['dependencia']['UnidadAdministrativa'] ?? '001' }}</span>
                  </div>
                </td>
              </tr>

              <tr>
                <td><strong>Fechas</strong></td>
                <td>
                  <div class="kv">
                    <strong>Fecha de solicitud:</strong>
                    <span>{{ $resumen['fechas']['solicitud']->format('d/m/Y H:i') }}</span>
                  </div>
                  <div class="kv">
                    <strong>Fecha de vigencia:</strong>
                    <span>{{ $resumen['fechas']['vigencia']->format('d/m/Y') }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>Moneda</strong></td>
                <td>{{ $resumen['control']['moneda'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      {{-- Datos de la Persona --}}
      <div class="card">
        <div class="card-header">Datos del contribuyente</div>
        <div class="card-body">
          @php $p = $resumen['persona'] ?? []; @endphp

          @if(($p['tipoPersona'] ?? '') === 'Persona Moral')
            <table class="tabla">
              <tbody>
                <tr>
                  <td style="width:35%"><strong>Tipo de persona</strong></td>
                  <td>Persona Moral</td>
                </tr>
                <tr>
                  <td><strong>RFC</strong></td>
                  <td class="mono">{{ $p['rfc'] }}</td>
                </tr>
                <tr>
                  <td><strong>Razón social</strong></td>
                  <td>{{ $p['razon'] }}</td>
                </tr>
              </tbody>
            </table>
          @else
            <table class="tabla">
              <tbody>
                <tr>
                  <td style="width:35%"><strong>Tipo de persona</strong></td>
                  <td>Persona Física</td>
                </tr>
                <tr>
                  <td><strong>RFC</strong></td>
                  <td class="mono">{{ $p['rfc'] ?? '' }}</td>
                </tr>
                <tr>
                  <td><strong>CURP</strong></td>
                  <td class="mono">{{ $p['curp'] ?? '' }}</td>
                </tr>
                <tr>
                  <td><strong>Nombre</strong></td>
                  <td>{{ trim(($p['nombres'] ?? '').' '.($p['ap1'] ?? '').' '.($p['ap2'] ?? '')) }}</td>
                </tr>
              </tbody>
            </table>
          @endif
        </div>
      </div>

      {{-- Concepto y Totales --}}
      <div class="card">
        <div class="card-header">Concepto seleccionado</div>
        <div class="card-body">
          @php
            $c = $resumen['concepto'];
            $fmt = fn($cent) => number_format($cent/100, 2, '.', ',');
          @endphp
          <table class="tabla">
            <thead>
              <tr>
                <th style="width:20%">Homoclave</th>
                <th>Descripción</th>
                <th style="width:14%; text-align:right;">Cantidad</th>
                <th style="width:18%; text-align:right;">Precio unitario</th>
                <th style="width:18%; text-align:right;">Importe</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="mono">{{ $c['homoclave'] }}</td>
                <td>{{ $c['descripcion'] }}</td>
                <td style="text-align:right;">{{ $c['cantidad'] }}</td>
                <td style="text-align:right;">$ {{ $fmt($c['importe']) }}</td>
                <td style="text-align:right;">$ {{ $fmt($c['subtotal']) }}</td>
              </tr>
              <tr class="totales">
                <td colspan="4" style="text-align:right;">IVA (16%)</td>
                <td style="text-align:right;">$ {{ $fmt($c['iva']) }}</td>
              </tr>
              <tr class="totales">
                <td colspan="4" style="text-align:right;"><strong>Total a pagar</strong></td>
                <td style="text-align:right;"><strong>$ {{ $fmt($c['total']) }}</strong></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      {{-- Acciones --}}
      <div class="no-print cta" style="margin-top:16px;">
        <a href="{{ route('pago') }}" class="btn btn-default">Regresar</a>
        <button class="btn btn-primary" onclick="window.print()">Imprimir / Guardar PDF</button>
      </div>

    </div>
  </main>

  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>
