{{-- resources/views/pago.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Formato de pago | PAGA IMT</title>

  <!-- CDN gob.mx -->
  <link rel="stylesheet" href="https://framework-gb.cdn.gob.mx/assets/styles/main.css">
  <!-- Montserrat -->
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      /* --gob-primary: var(--color-primario); */
      --gob-primary:#611232; /* fallback actual */
    }

    /* Tipografía unificada */
    body, h1,h2,h3,h4,h5,h6,p,a,li,label,input,select,button,th,td{
      font-family:"Montserrat",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif !important;
    }

    /* Breadcrumb */
    .breadcrumb{ background:transparent; padding-left:0; margin:12px 0 6px; }
    .breadcrumb > li + li:before{ color:#bbb; }
    .breadcrumb a{ text-decoration:underline; }

    /* Título */
    .titulo-pagina{ color:#111; font-weight:700; margin:8px 0 14px; line-height:1.15; }

    /* Stepper */
    .stepper{ display:flex; justify-content:center; gap:10px; margin:10px 0 22px; flex-wrap:wrap; }
    .step{
      display:flex; flex-direction:column; justify-content:center;
      min-width:210px; max-width:260px;
      padding:12px 16px; border:1px solid #e5e5e5; border-radius:6px;
      background:#fff; color:#111; text-align:center;
    }
    .step strong{ display:block; font-weight:700; }
    .step small{ display:block; color:#666; font-weight:500; margin-top:2px; }
    .step.active{
      background:var(--gob-primary);
      border-color:var(--gob-primary);
      color:#fff;
    }
    .step.active small{ color:#fff; opacity:.95; }

    /* Subtítulo + leyenda */
    h3.subtitulo{ margin:4px 0 0 !important; line-height:1.2; font-weight:700; color:#222; }
    h3.subtitulo + .lbl-muted{ margin-top:0 !important; }
    .lbl-muted{ color:#666; margin:0 0 12px !important; line-height:1.45; }

    /* ===== Tabla pago ===== */
    .tabla-wrap{ overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .tabla-pago{ width:100%; border-collapse:collapse; min-width:480px; }
    .tabla-pago th, .tabla-pago td{ padding:10px 8px; border-top:1px solid #e5e5e5; vertical-align:middle; }
    .tabla-pago thead th{ border-top:none; color:#444; font-weight:600; }
    .importe{ width:220px; text-align:right; white-space:nowrap; }
    .input-mini{ width:140px; min-height:36px; border:1px solid #cfcfcf; padding:6px 10px; border-radius:3px; text-align:right; }

    /* === Botones (comportamiento) === */
    .btn, .btn:hover, .btn:focus, .btn:active{ text-decoration:none !important; }
    .btn{ min-height:40px; }
    .btn:focus-visible{ outline:2px solid #444; outline-offset:2px; box-shadow:none; }
    .btn.btn-primary{ background-color:var(--gob-primary); color:#fff !important; border:1px solid var(--gob-primary); }
    .btn.btn-primary:hover, .btn.btn-primary:focus, .btn.btn-primary:active{
      background-color:#4d0e29; border-color:#4d0e29; color:#fff !important;
    }
    .btn.btn-primary:focus-visible{ outline-color:#fff; }
    .btn.btn-default{ font-weight:400; background:#fff; color:#444; border:1px solid #ccc; }
    .btn.btn-default:hover, .btn.btn-default:focus, .btn.btn-default:active{
      background:#fff; color:#444; border:1px solid #ccc; box-shadow:none;
    }

    /* === FIX menú hamburguesa gris en móvil === */
    .navbar-toggle .icon-bar{ background-color:#fff !important; }
    .navbar-toggle,
    .navbar-toggle:hover,
    .navbar-toggle:focus,
    .navbar-toggle:active{
      background:transparent !important;
      box-shadow:none !important;
      border-color:transparent !important;
    }
    .navbar-toggle:focus-visible{ outline:2px solid #fff; outline-offset:2px; }

    .totales{ text-align:right; }

    /* Responsivo */
    @media (max-width:768px){
      .step{ min-width:180px; }
      .btn{ width:100%; }
    }
  </style>
</head>
<body>
  <main class="page" role="main" style="margin-top:30px">
    <div class="container">

      <ol class="breadcrumb" aria-label="miga de pan">
        <li><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="active">Instituto Mexicano del Transporte</li>
      </ol>

      <center><h1 class="titulo-pagina">Instituto Mexicano del Transporte</h1></center>

      <div class="stepper" role="group" aria-label="Progreso del trámite">
        <div class="step"><strong>Paso 1</strong><small>Selección del trámite</small></div>
        <div class="step"><strong>Paso 2</strong><small>Información de la persona</small></div>
        <div class="step active" aria-current="step"><strong>Paso 3</strong><small>Formato de pago</small></div>
      </div>

      <h3 class="subtitulo">Formato de pago</h3>
      <p class="lbl-muted">Verifique los datos para la generación de su línea de captura.</p>

      <div class="tabla-wrap">
        <table class="tabla-pago">
          <thead>
            <tr>
              <th>Descripción del concepto</th>
              <th class="importe">Importe en pesos M.N.</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                ASESORÍA (COSTO POR HORA DE INVESTIGADOR TITULAR) - Entrega de informe de servicio.<br>
                ASESORÍA (COSTO POR HORA DE INVESTIGADOR TITULAR) - IVA Actos Accidentales
              </td>
              <td class="importe">
                <input class="input-mini" type="text" value="400" disabled>
              </td>
            </tr>
            <tr>
              <td>Cantidad de trámites/servicios:</td>
              <td class="importe">
                <input class="input-mini" type="number" value="1" min="1">
              </td>
            </tr>
            <tr>
              <td class="totales">IVA:</td>
              <td class="importe">$64.00</td>
            </tr>
            <tr>
              <td class="totales"><strong>Total:</strong></td>
              <td class="importe"><strong>$464.00</strong></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="row" style="margin-top:18px;">
        <div class="col-xs-6">
          <a href="{{ route('informacion') }}" class="btn btn-default">Atrás</a>
        </div>
        <div class="col-xs-6 text-right">
          <a href="#" class="btn btn-primary">Generar línea de captura</a>
        </div>
      </div>

      <br>
      <p class="lbl-muted" style="margin-top:10px;"><strong>*</strong> Campos obligatorios</p>

    </div>
  </main>

  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>
