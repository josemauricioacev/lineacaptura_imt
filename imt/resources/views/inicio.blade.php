{{-- resources/views/inicio.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Inicio | PAGA IMT</title>

  {{-- Estilos oficiales gob.mx (cambia solo este link por sexenio) --}}
  <link rel="stylesheet" href="https://framework-gb.cdn.gob.mx/assets/styles/main.css">

  {{-- Fuente Montserrat --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --vino:#611232;
      --gris:#98989A;
      --dorado:#a57f2c;
      --negro:#111111;
      --blanco:#ffffff;
    }
    body, h1, h2, h3, h4, h5, h6, p, a, li {
      font-family: "Montserrat", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif !important;
    }

    /* ====== Encabezado visual del contenido ====== */
    .banner-logos{
      display:flex; align-items:center; justify-content:space-between;
      gap:16px; margin:10px 0 0;
    }
    .banner-logos img{ max-height:74px; height:auto; width:auto; }
    @media (max-width: 768px){
      .banner-logos{ justify-content:center; }
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

    /* ====== Caja de mensaje (si más adelante la activas) ====== */
    .panel-gold{
      border: 1px solid #e5e5e5;
      border-top: 4px solid var(--dorado);
      background: #fff;
      border-radius: 4px;
      padding: 14px 16px;
      margin-bottom: 22px;
    }
    .panel-gold .lead{ color: var(--negro); margin:0 0 6px 0; font-weight:500; }
    .panel-gold .muted{ color: var(--gris); margin:0; }

    /* ====== Acordeón compacto, centrado y con icono + / − ====== */
    .acordeon .panel{
      border:1px solid #e5e5e5; border-radius:4px; box-shadow:none;
    }
    /* Quitamos padding al header y lo controlamos con el enlace-flex */
    .acordeon .panel-heading{
      background:#f5f5f5; border-bottom:1px solid #e5e5e5; padding:0;
    }
    /* Enlace que actúa como botón: centra vertical y elimina “aire” */
    .acordeon .heading-toggle{
      display:flex; align-items:center; justify-content:space-between;
      gap:12px; padding:10px 14px; text-decoration:none;
      color:var(--negro); font-weight:600; line-height:1.2;
    }
    .acordeon .heading-toggle:focus { outline: none !important; box-shadow: none !important; }

    .acordeon .panel-body{
      background:#fff; padding:14px 18px;
    }
    .acordeon .panel-body a{
      color:#2a5d2f; text-decoration:underline;
    }

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
    /* Cuando está abierto (expanded=true) ocultamos la barra vertical ⇒ “−” */
    .heading-toggle[aria-expanded="true"] .toggle-indicator::after{ opacity:0; }
  </style>
</head>
<body>

  {{-- Header y footer los inyecta gobmx.js --}}
  <main class="page" role="main" style="margin-top:30px">
    <div class="container">

      {{-- LOGOS: uno a la izquierda y otro a la derecha en la parte superior del contenido --}}
      <div class="banner-logos" aria-label="Encabezado visual">
        <img class="left" src="{{ asset('images/comunicaciones.png') }}" alt="Logotipo de Comunicaciones (SICT)" loading="lazy">
        <img class="right" src="{{ asset('images/imt.png') }}" alt="Logotipo del Instituto Mexicano del Transporte" loading="lazy">
      </div>

      {{-- TÍTULO + subrayado dorado (texto negro) --}}
      <div class="subrayado-dorado">
        <center><h1 class="titulo-bienvenida">Bienvenido a PAGA IMT</h1></center>
      </div>

      {{-- ACORDEÓN: “Secretaría…” con enlace al IMT --}}
      <div class="panel-group acordeon" id="acordeon-dependencia" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading-sict">
            <!-- Enlace-flex que controla el collapse. Texto centrado vertical + icono -->
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
              <a href="{{ url('/seleccion') }}" aria-label="Ir a Instituto Mexicano del Transporte">Instituto Mexicano del Transporte</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>

  {{-- Script oficial para inyectar header/footer y habilitar componentes (collapse, etc.) --}}
  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>
