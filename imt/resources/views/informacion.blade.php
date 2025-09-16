{{-- resources/views/informacion.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Información de la persona | PAGA IMT</title>

  <!-- CDN gob.mx -->
  <link rel="stylesheet" href="https://framework-gb.cdn.gob.mx/assets/styles/main.css">
  <!-- Montserrat -->
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{ --gob-primary:#611232; }

    body, h1,h2,h3,h4,h5,h6,p,a,li,label,input,select,button,th,td{
      font-family:"Montserrat",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif !important;
    }

    .breadcrumb{ background:transparent; padding-left:0; margin:12px 0 6px; }
    .breadcrumb > li + li:before{ color:#bbb; }
    .breadcrumb a{ text-decoration:underline; }

    .link-like{
      background:none; border:none; padding:0;
      color:#2a5d2f; text-decoration:underline; cursor:pointer;
      font:inherit;
    }
    .link-like:focus-visible{ outline:2px solid #444; outline-offset:2px; }

    .titulo-pagina{ color:#111; font-weight:700; margin:8px 0 14px; line-height:1.15; }

    .stepper{ display:flex; justify-content:center; gap:10px; margin:10px 0 22px; flex-wrap:wrap; }
    .step{
      display:flex; flex-direction:column; justify-content:center;
      min-width:210px; max-width:260px;
      padding:12px 16px; border:1px solid #e5e5e5; border-radius:6px;
      background:#fff; color:#111; text-align:center;
    }
    .step.active{ background:var(--gob-primary); border-color:var(--gob-primary); color:#fff; }

    h3.subtitulo{ margin:4px 0 0 !important; line-height:1.2; font-weight:700; color:#222; }
    .lbl-muted{ color:#666; margin:0 0 12px !important; line-height:1.45; }

    .form-grid{ display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
    .form-grid label{ font-weight:600; color:#444; margin-bottom:6px; display:block; }
    .form-grid input, .form-grid select{
      width:100%; min-height:36px; border:1px solid #cfcfcf; padding:6px 10px; border-radius:3px;
    }
    /* SOLO inputs en mayúsculas */
    .form-grid input[type="text"]{ text-transform:uppercase; }

    .form-help{
      display:block; margin-top:6px; font-size:12px; color:#a94442; /* rojo suave */
    }

    .btn, .btn:hover, .btn:focus, .btn:active{ text-decoration:none !important; }
    .btn{ min-height:40px; }
    .btn:focus-visible{ outline:2px solid #444; outline-offset:2px; box-shadow:none; }
    .btn.btn-primary{ background-color:var(--gob-primary); color:#fff !important; border:1px solid var(--gob-primary); }
    .btn.btn-primary:hover{ background-color:#4d0e29; }
    .btn.btn-default{ background:#fff; color:#444; border:1px solid #ccc; }
    .btn.btn-default:hover{ background:#f7f7f7; }

    @media (max-width:992px){ .form-grid{ grid-template-columns:1fr 1fr; } }
    @media (max-width:640px){ .form-grid{ grid-template-columns:1fr; } }
    @media (max-width:768px){
      .step{ min-width:180px; }
      .btn{ width:100%; }
    }
  </style>
</head>
<body>
  <main class="page" role="main" style="margin-top:30px">
    <div class="container">

      {{-- Sin banner general: mostramos errores pequeños por campo --}}

      <ol class="breadcrumb" aria-label="miga de pan">
        <li>
          <form id="form-back" action="{{ route('informacion.back') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="link-like">Selección del trámite</button>
          </form>
        </li>
        <li class="active">Instituto Mexicano del Transporte</li>
      </ol>

      <center><h1 class="titulo-pagina">Instituto Mexicano del Transporte</h1></center>

      <div class="stepper" role="group" aria-label="Progreso del trámite">
        <div class="step"><strong>Paso 1</strong><small>Selección del trámite</small></div>
        <div class="step active" aria-current="step"><strong>Paso 2</strong><small>Información de la persona</small></div>
        <div class="step"><strong>Paso 3</strong><small>Formato de pago</small></div>
      </div>

      <h3 class="subtitulo">Información de la persona</h3>
      <p class="lbl-muted">Proporcione los datos solicitados para la generación de su línea de captura.</p>

      <form id="form-info" action="{{ route('informacion.next') }}" method="POST">
        @csrf
        <div class="form-grid" role="form" aria-label="Formulario de datos personales">
          {{-- Tipo de persona --}}
          <div style="grid-column:1 / span 3;">
            <label for="tipoPersona">Seleccione:</label>
            <select id="tipoPersona" name="tipoPersona" required aria-describedby="tipoPersonaHelp">
              <option value="" disabled {{ old('tipoPersona') ? '' : 'selected' }}>Seleccione una opción</option>
              <option value="Persona Física" {{ old('tipoPersona')==='Persona Física' ? 'selected' : '' }}>Persona Física</option>
              <option value="Persona Moral"  {{ old('tipoPersona')==='Persona Moral'  ? 'selected' : '' }}>Persona Moral</option>
            </select>
            @error('tipoPersona')
              <small id="tipoPersonaHelp" class="form-help">{{ $message }}</small>
            @enderror
          </div>

          {{-- CURP --}}
          <div>
            <label for="curp">CURP:</label>
            <input id="curp" name="curp" type="text" maxlength="18"
                   value="{{ old('curp') }}"
                   pattern="^[A-Z][AEIOUX][A-Z]{2}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$"
                   title="CURP de 18 caracteres (formato oficial)">
            @error('curp')
              <small class="form-help">{{ $message }}</small>
            @enderror
          </div>

          {{-- RFC --}}
          <div>
            <label for="rfc">RFC:*</label>
            <input id="rfc" name="rfc" type="text" required maxlength="13"
                   value="{{ old('rfc') }}"
                   pattern="^[A-Z&Ñ]{3,4}\d{6}[A-Z0-9]{3}$"
                   title="RFC con formato válido (12 o 13 caracteres, con fecha y homoclave)">
            @error('rfc')
              <small class="form-help">{{ $message }}</small>
            @enderror
          </div>

          {{-- Nombres --}}
          <div>
            <label for="nombres">Nombre(s):*</label>
            <input id="nombres" name="nombres" type="text" required
                   value="{{ old('nombres') }}" title="Ingrese su nombre">
            @error('nombres')
              <small class="form-help">{{ $message }}</small>
            @enderror
          </div>

          {{-- Apellido 1 --}}
          <div>
            <label for="ap1">Primer apellido:*</label>
            <input id="ap1" name="ap1" type="text" required
                   value="{{ old('ap1') }}" title="Ingrese su primer apellido">
            @error('ap1')
              <small class="form-help">{{ $message }}</small>
            @enderror
          </div>

          {{-- Apellido 2 --}}
          <div>
            <label for="ap2">Segundo apellido:</label>
            <input id="ap2" name="ap2" type="text" value="{{ old('ap2') }}">
            @error('ap2')
              <small class="form-help">{{ $message }}</small>
            @enderror
          </div>
        </div>

        <div class="row" style="margin-top:16px;">
          <div class="col-xs-6">
            {{-- Botón que envía el form de atrás (no anidar forms) --}}
            <button type="submit" class="btn btn-default" form="form-back" formmethod="POST">← Atrás</button>
          </div>
          <div class="col-xs-6 text-right">
            <button type="submit" class="btn btn-primary">Siguiente →</button>
          </div>
        </div>
      </form>

      <br>
      <p class="lbl-muted" style="margin-top:10px;"><strong>*</strong> Campos obligatorios</p>

    </div>
  </main>

  <script>
    // Mayúsculas SOLO en inputs de texto
    document.querySelectorAll('input[type="text"]').forEach(el => {
      el.addEventListener('input', () => { el.value = el.value.toUpperCase(); });
    });
  </script>

  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>
