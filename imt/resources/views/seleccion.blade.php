{{-- resources/views/seleccion.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Selección de trámite | PAGA IMT</title>

  {{-- CDN gob.mx --}}
  <link rel="stylesheet" href="https://framework-gb.cdn.gob.mx/assets/styles/main.css">
  {{-- Montserrat --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --vino:#611232;
      --negro:#111111;
      --grisTexto:#666666;
      --borde:#E5E5E5;
    }
    body, h1,h2,h3,h4,h5,h6,p,a,li,label,input,select,button,th,td{
      font-family:"Montserrat",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif !important;
    }

    /* ===== Breadcrumb ===== */
    .breadcrumb{ background:transparent; padding-left:0; margin:12px 0 6px; }
    .breadcrumb > li + li:before{ color:#bbb; }
    .breadcrumb a{ text-decoration:underline; }

    /* ===== Título ===== */
    .titulo-pagina{
      color:var(--negro);
      font-weight:700;
      margin:8px 0 14px;
      line-height:1.15;
    }

    /* ===== STEPPER (centrado, sin chevrons, radio mínimo) ===== */
    .stepper{
      display:flex;
      justify-content:center;
      gap:10px;
      margin:10px 0 22px;
      flex-wrap:wrap;
    }
    .step{
      display:flex; flex-direction:column; justify-content:center;
      min-width:210px;
      padding:12px 16px;
      border:1px solid var(--borde);
      border-radius:6px;
      background:#fff; color:var(--negro);
      text-align:left;
    }
    .step strong{ display:block; font-weight:700; }
    .step small{ display:block; color:var(--grisTexto); font-weight:500; margin-top:2px; }

    .step.active{
      background:var(--vino);
      color:#fff;
      border-color:var(--vino);
    }
    .step.active small{ color:#fff; opacity:.95; }

    /* ===== Subtítulo + leyenda (quitar hueco a la fuerza) ===== */
    h3.subtitulo{
      margin:4px 0 0 !important;
      line-height:1.2;
      font-weight:700; color:#222;
    }
    h3.subtitulo + .lbl-muted{ margin-top:0 !important; }
    .lbl-muted{
      color:#666;
      margin:0 0 12px !important;
      line-height:1.45;
    }

    /* ===== Formulario y tabla ===== */
    .form-inline-imt{ display:flex; gap:10px; align-items:center; margin:10px 0 16px; }

    /* Ajuste estético de la flecha del <select> */
    .form-inline-imt select{
      flex:1;
      min-height:36px;
      border:1px solid #cfcfcf;
      padding:6px 10px;
      border-radius:3px;

      /* ocultar flecha nativa y colocar chevron con espacio a la derecha */
      -webkit-appearance:none;
      -moz-appearance:none;
      appearance:none;
      padding-right:2.25rem; /* espacio para la flecha */
      background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
      background-repeat:no-repeat;
      background-position: right 12px center; /* separa la flecha del borde */
      background-size:14px;
    }
    /* ocultar el expand de IE/Edge heredado */
    select::-ms-expand{ display:none; }

    .tabla-imt{ width:100%; border-collapse:collapse; margin-top:8px; margin-bottom:14px; }
    .tabla-imt thead th{ color:#444; font-weight:600; border-bottom:2px solid var(--borde); padding:10px 8px; }
    .tabla-imt tbody td{ border-top:1px solid var(--borde); padding:10px 8px; vertical-align:middle; }

    /* ===== Botones ===== */
    .btn-vino{
      background:var(--vino); color:#fff; border:1px solid #5a0f2f; border-radius:4px;
      padding:8px 16px; font-weight:600;
    }
    .btn-vino:hover{ background:#6d173a; color:#fff; }
    .btn-outline-vino{
      background:#fff; color:var(--vino); border:1px solid #d99; border-radius:4px; padding:8px 16px;
    }
    .btn-outline-vino:hover{ background:#fce7ec; }

    @media (max-width:768px){
      .step{ min-width:180px; }
      .form-inline-imt{ flex-direction:column; align-items:stretch; }
      .btn-outline-vino,.btn-vino{ width:100%; }
    }
  </style>
</head>
<body>

  <main class="page" role="main" style="margin-top:30px">
    <div class="container">

      {{-- Breadcrumb --}}
      <ol class="breadcrumb" aria-label="miga de pan">
        <li><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="active">Instituto Mexicano del Transporte</li>
      </ol>

      {{-- Título --}}
      <center><h1 class="titulo-pagina">Instituto Mexicano del Transporte</h1></center>

      {{-- STEPPER (en esta vista Paso 1 activo) --}}
      <div class="stepper" role="group" aria-label="Progreso del trámite">
        <div class="step active" aria-current="step">
          <center><strong>Paso 1</strong>
          <small>Selección del trámite</small></center>
        </div>
        <div class="step">
          <center><strong>Paso 2</strong>
          <small>Información de la persona</small></center>
        </div>
        <div class="step">
          <center><strong>Paso 3</strong>
          <small>Formato de pago</small></center>
        </div>
      </div>

      {{-- Sección principal --}}
      <h3 class="subtitulo">Selección del trámite:</h3>
      <p class="lbl-muted">Identifique y seleccione el trámite y/o servicio.</p>

      <div class="form-inline-imt" role="form" aria-label="Formulario de selección de trámite">
        <label for="tramite" class="sr-only">Selecciona un trámite</label>
        <select id="tramite" name="tramite" aria-label="Selecciona un trámite">
          <option selected disabled>Selecciona un trámite</option>
          <option value="1">Constancia de capacitación</option>
          <option value="2">Renovación de licencia de operador</option>
          <option value="3">Inscripción a curso de seguridad vial</option>
        </select>
        <a class="btn btn-outline-vino" href="{{ route('informacion') }}">Seleccionar</a>
      </div>

      <table class="tabla-imt">
        <thead>
          <tr>
            <th>Descripción del concepto</th>
            <th style="width:220px;">Importe en pesos M.N.</th>
            <th style="width:160px;">Opciones</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>—</td><td>—</td><td>—</td></tr>
        </tbody>
      </table>

      <div class="text-right" style="margin:12px 0 26px;">
        <a class="btn btn-vino" href="{{ route('informacion') }}">Siguiente</a>
      </div>

    </div>
  </main>

  {{-- Script gob.mx (header/footer, colapsables, etc.) --}}
  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>
