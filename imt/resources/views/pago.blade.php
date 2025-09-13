<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Formato de pago | PAGA IMT</title>

  <link rel="stylesheet" href="https://framework-gb.cdn.gob.mx/assets/styles/main.css">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{ --vino:#611232; --negro:#111; --grisTexto:#666; --borde:#e5e5e5; }
    body, h1,h2,h3,h4,h5,h6,p,a,li,label,input,select,button,th,td{
      font-family:"Montserrat",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif !important;
    }

    .breadcrumb{ background:transparent; padding-left:0; margin:12px 0 6px; }
    .breadcrumb > li + li:before{ color:#bbb; }
    .breadcrumb a{ text-decoration:underline; }

    .titulo-pagina{ color:var(--negro); font-weight:700; margin:8px 0 14px; line-height:1.15; }

    /* Ocultar líneas doradas */
    .linea-dorada, .subrayado-dorado{ display:none !important; }

    /* ===== Stepper centrado ===== */
    .stepper{ display:flex; justify-content:center; gap:10px; margin:10px 0 22px; flex-wrap:wrap; }
    .step{
      display:flex; flex-direction:column; justify-content:center;
      min-width:210px; padding:12px 16px; border:1px solid var(--borde);
      border-radius:6px; background:#fff; color:var(--negro); text-align:left;
    }
    .step strong{ display:block; font-weight:700; }
    .step small{ display:block; color:var(--grisTexto); font-weight:500; margin-top:2px; }
    .step.active{ background:var(--vino); color:#fff; border-color:var(--vino); }
    .step.active small{ color:#fff; opacity:.95; }

    /* Subtítulo + leyenda */
    h3.subtitulo{ margin:4px 0 0 !important; line-height:1.2; font-weight:700; color:#222; }
    h3.subtitulo + .lbl-muted{ margin-top:0 !important; }
    .lbl-muted{ color:var(--grisTexto); margin:0 0 12px !important; line-height:1.45; }

    /* ===== Tabla pago ===== */
    .tabla-pago{ width:100%; border-collapse:collapse; }
    .tabla-pago th, .tabla-pago td{ padding:10px 8px; border-top:1px solid var(--borde); vertical-align:middle; }
    .tabla-pago thead th{ border-top:none; color:#444; font-weight:600; }
    .importe{ width:220px; text-align:right; white-space:nowrap; }
    .input-mini{ width:140px; min-height:36px; border:1px solid #cfcfcf; padding:6px 10px; border-radius:3px; text-align:right; }

    /* ===== Botones ===== */
    .btn-vino{
      background:var(--vino);
      color:#fff;
      border:1px solid #5a0f2f;
      border-radius:4px;
      padding:8px 16px;
      font-weight:600;
    }

    /* Botón Atrás limpio y estático */
    .btn-outline{
      background:#fff;
      color:#444;
      border:1px solid #ccc;
      border-radius:4px;
      padding:8px 16px;
      font-weight:400; /* texto normal */
    }
    /* Sin cambios en hover ni focus */
    .btn-outline:hover,
    .btn-outline:focus,
    .btn-outline:active{
      background:#fff;
      color:#444;
      border:1px solid #ccc; /* mantiene el mismo color siempre */
      text-decoration:none;
    }

    /* Mantener texto blanco en todos los estados del botón principal */
    .btn-vino,
    .btn-vino:link,
    .btn-vino:visited,
    .btn-vino:hover,
    .btn-vino:focus,
    .btn-vino:active{
      color:#fff !important;
      text-decoration:none;
    }
    .btn-vino:hover,
    .btn-vino:focus{
      background:#71203f;
      border-color:#631a37;
    }

    .totales{ text-align:right; }

    @media (max-width:768px){
      .step{ min-width:180px; }
      .btn-outline, .btn-vino{ width:100%; }
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
        <div class="step"><center><strong>Paso 1</strong><small>Selección del trámite</small></center></div>
        <div class="step"><center><strong>Paso 2</strong><small>Información de la persona</small></center></div>
        <div class="step active" aria-current="step"><center><strong>Paso 3</strong><small>Formato de pago</small></center></div>
      </div>

      <h3 class="subtitulo">Formato de pago</h3>
      <p class="lbl-muted">Verifique los datos para la generación de su línea de captura.</p>

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

      <div class="row" style="margin-top:18px;">
        <div class="col-xs-6">
          <a href="{{ route('informacion') }}" class="btn btn-outline">Atrás</a>
        </div>
        <div class="col-xs-6 text-right">
          <a href="#" class="btn btn-vino">Generar línea de captura</a>
        </div>
      </div>

      <br>
      <p class="lbl-muted" style="margin-top:10px;"><strong>*</strong> Campos obligatorios</p>

    </div>
  </main>

  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>
