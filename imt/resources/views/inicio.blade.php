{{-- resources/views/inicio.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Instituto Mexicano del Transporte</title>

  <!-- Estilos oficiales gob.mx (cambia solo este link por sexenio) -->
  <link rel="stylesheet" href="https://framework-gb.cdn.gob.mx/assets/styles/main.css">

  <!-- Fuente Montserrat -->
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      /* --gob-primary: var(--color-primario); */ /* remapea si el CDN lo publica */
      --gob-primary:#611232; /* fallback actual */
      --gris:#98989A;
      --negro:#111111;
      --blanco:#ffffff;
      --borde:#e5e5e5;
      --dorado:#a57f2c;
    }

    /* Tipografía unificada */
    body, h1, h2, h3, h4, h5, h6, p, a, li, label, input, select, button, th, td{
      font-family:"Montserrat",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif !important;
    }

    /* ====== Encabezado visual del contenido ====== */
    .banner-logos{
      display:flex; align-items:center; justify-content:space-between;
      gap:16px; margin:10px 0 0;
    }
    .banner-logos img{ max-height:74px; height:auto; width:auto; }
    @media (max-width: 768px){
      .banner-logos{ justify-content:center; flex-wrap:wrap; }
      .banner-logos img{ max-height:56px; }
      .banner-logos .left{ order:1; }
      .banner-logos .right{ order:2; }
    }

    /* ====== Título principal ====== */
    .titulo-bienvenida{
      margin:22px 0 12px;
      color: var(--negro);
      font-weight:700;
      letter-spacing:.2px;
      line-height:1.15;
    }
    .subrayado-dorado{
      position:relative;
      padding-bottom:10px;
      margin-bottom:18px;
    }
    .subrayado-dorado::after{
      content:"";
      display:block;
      height:4px;
      background: linear-gradient(90deg, var(--dorado), var(--dorado));
      width:100%;
    }

    /* ====== Acordeón compacto y accesible ====== */
    .acordeon .panel{
      border:1px solid var(--borde); border-radius:4px; box-shadow:none;
    }
    .acordeon .panel-heading{
      background:#f5f5f5; border-bottom:1px solid var(--borde); padding:0;
    }
    .acordeon .heading-toggle{
      display:flex; align-items:center; justify-content:space-between;
      gap:12px; padding:10px 14px;
      text-decoration:none;
      color:var(--negro); font-weight:600; line-height:1.2;
    }
    .acordeon .heading-toggle:focus-visible{
      outline:2px solid #444; outline-offset:2px; box-shadow:none;
    }
    .acordeon .panel-body{ background:#fff; padding:14px 18px; }

    /* Botón circular + / − (solo CSS; cambia con aria-expanded) */
    .toggle-indicator{
      position:relative; width:28px; height:28px; border-radius:50%;
      border:2px solid #bbb; background:#fff; cursor:pointer; flex:0 0 28px;
    }
    .toggle-indicator::before, .toggle-indicator::after{
      content:""; position:absolute; left:50%; top:50%; transform:translate(-50%,-50%);
      background:#777;
    }
    .toggle-indicator::before{ width:14px; height:2px; }  /* “−” horizontal */
    .toggle-indicator::after { width:2px;  height:14px; } /* “|” vertical (para “+”) */
    .heading-toggle[aria-expanded="true"] .toggle-indicator::after{ opacity:0; }

    /* ====== Utilidades de botones ====== */
    .btn, .btn:hover, .btn:focus, .btn:active{ text-decoration:none !important; }
    .btn{ min-height:40px; }
    .btn:focus-visible{ outline:2px solid #444; outline-offset:2px; box-shadow:none; }
    .btn.btn-primary{ background-color:var(--gob-primary); color:#fff !important; border:1px solid var(--gob-primary); }
    .btn.btn-primary:hover, .btn.btn-primary:focus, .btn.btn-primary:active{
      background-color:#4d0e29; border-color:#4d0e29; color:#fff !important;
    }

    /* Botón que luce como enlace (para el POST) */
    .link-like{
      background:none; border:none; padding:0;
      color:#2a5d2f; text-decoration:underline; cursor:pointer;
      font:inherit;
    }
    .link-like:focus-visible{ outline:2px solid #444; outline-offset:2px; }
  </style>
</head>
<body>

  <main class="page" role="main" style="margin-top:30px">
    <div class="container">

      {{-- MENSAJES (sin duplicados) --}}
      @if (session('wizard_error'))
        <div class="alert alert-danger" role="alert">{{ session('wizard_error') }}</div>
      @endif
      @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
      @endif

      {{-- LOGOS --}}
      <div class="banner-logos" aria-label="Encabezado visual">
        <img class="left" src="{{ asset('images/comunicaciones.png') }}" alt="Logotipo de Comunicaciones (SICT)" loading="lazy">
        <img class="right" src="{{ asset('images/imt.png') }}" alt="Logotipo del Instituto Mexicano del Transporte" loading="lazy">
      </div>

      {{-- TÍTULO --}}
      <div class="subrayado-dorado">
        <center><h1 class="titulo-bienvenida">Bienvenido a PAGA IMT</h1></center>
      </div>

      {{-- ACORDEÓN con botón POST (no enlaces directos) --}}
      <div class="panel-group acordeon" id="acordeon-dependencia" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-sict">
            <a class="heading-toggle"
               role="button"
               data-toggle="collapse"
               data-parent="#acordeon-dependencia"
               href="#collapse-sict"
               aria-expanded="true"
               aria-controls="collapse-sict">
              <span>Secretaría de Infraestructura, Comunicaciones y Transportes</span>
              <span class="toggle-indicator" aria-hidden="true"></span>
            </a>
          </div>

          <div id="collapse-sict" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-sict">
            <div class="panel-body">
              {{-- Al hacer clic, enviamos los valores fijos 155 / 001 para guardarlos en sesión --}}
              <form action="{{ route('inicio.next') }}" method="POST" style="display:inline">
                @csrf
                <input type="hidden" name="cve_dependencia" value="155">
                <input type="hidden" name="unidad_administrativa" value="001">
                <button type="submit" class="link-like" aria-label="Ir a Instituto Mexicano del Transporte">
                  Instituto Mexicano del Transporte
                </button>
              </form>
            </div>
          </div>
        </div>

        {{-- Puedes agregar aquí más panels si se suman otras UAs o dependencias --}}
        {{-- <div class="panel panel-default"> ... </div> --}}
      </div>

      {{-- Pie/nota opcional --}}
      <p style="margin-top:20px; color:#666; font-size:13px;">
        Selecciona la dependencia para iniciar el trámite de pago y generación de línea de captura.
      </p>

    </div>
  </main>

  <!-- Script oficial para header/footer y componentes -->
  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>
