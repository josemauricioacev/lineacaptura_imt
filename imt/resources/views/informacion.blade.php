<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Información de la persona | PAGA IMT</title>

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

    /* Formulario */
    .form-grid{ display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
    .form-grid label{ font-weight:600; color:#444; margin-bottom:6px; display:block; }
    .form-grid input, .form-grid select{ width:100%; min-height:36px; border:1px solid #cfcfcf; padding:6px 10px; border-radius:3px; }

    /* Flecha estética de select */
    .form-grid select, #tipoPersona{
      -webkit-appearance:none;
      -moz-appearance:none;
      appearance:none;
      padding-right:2.25rem;
      background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
      background-repeat:no-repeat;
      background-position: right 12px center;
      background-size:14px;
    }
    select::-ms-expand{ display:none; }

    /* Botones */
    .btn-vino{
      background:var(--vino);
      color:#fff;
      border:1px solid #5a0f2f;
      border-radius:4px;
      padding:8px 16px;
      font-weight:600;
    }
    .btn-outline{
      background:#fff;
      color:#444;
      border:1px solid #ccc;
      border-radius:4px;
      padding:8px 16px;
    }

    /* 🔧 Fix para mantener texto blanco en hover del botón "Siguiente" */
    .btn-vino,
    .btn-vino:link,
    .btn-vino:visited,
    .btn-vino:hover,
    .btn-vino:focus,
    .btn-vino:active {
      color: #fff !important;
      text-decoration: none;
    }
    .btn-vino:hover,
    .btn-vino:focus {
      background:#71203f;
      border-color:#631a37;
    }

    @media (max-width:992px){ .form-grid{ grid-template-columns:1fr 1fr; } }
    @media (max-width:640px){ .form-grid{ grid-template-columns:1fr; } }
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
        <div class="step active" aria-current="step"><center><strong>Paso 2</strong><small>Información de la persona</small></center></div>
        <div class="step"><center><strong>Paso 3</strong><small>Formato de pago</small></center></div>
      </div>

      <h3 class="subtitulo">Información de la persona</h3>
      <p class="lbl-muted">Proporcione los datos solicitados para la generación de su línea de captura.</p>

      <div class="form-grid" role="form" aria-label="Formulario de datos personales">
        <div style="grid-column:1 / span 3;">
          <label for="tipoPersona">Seleccione:*</label>
          <select id="tipoPersona" name="tipoPersona">
            <option value="" selected disabled>Seleccione una opción</option>
            <option>Persona Física</option>
            <option>Persona Moral</option>
          </select>
        </div>

        <div>
          <label for="curp">CURP:*</label>
          <input id="curp" name="curp" placeholder="">
        </div>

        <div>
          <label for="rfc">RFC:*</label>
          <input id="rfc" name="rfc" placeholder="">
        </div>

        <div>
          <label for="nombres">Nombre(s):*</label>
          <input id="nombres" name="nombres" placeholder="">
        </div>

        <div>
          <label for="ap1">Primer apellido:*</label>
          <input id="ap1" name="ap1" placeholder="">
        </div>

        <div>
          <label for="ap2">Segundo apellido:</label>
          <input id="ap2" name="ap2" placeholder="">
        </div>
      </div>

      <div class="row" style="margin-top:16px;">
        <div class="col-xs-6">
          <a href="{{ route('seleccion') }}" class="btn btn-outline">Atrás</a>
        </div>
        <div class="col-xs-6 text-right">
          <a href="{{ route('pago') }}" class="btn btn-vino">Siguiente</a>
        </div>
      </div>

      <br>
      <p class="lbl-muted" style="margin-top:10px;"><strong>*</strong> Campos obligatorios</p>

    </div>
  </main>

  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>
