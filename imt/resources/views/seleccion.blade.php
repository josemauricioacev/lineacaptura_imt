{{-- resources/views/seleccion.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Selección de trámite</title>

  <link rel="stylesheet" href="https://framework-gb.cdn.gob.mx/assets/styles/main.css">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{ --gob-primary:#611232; }
    body, h1,h2,h3,h4,h5,h6,p,a,li,label,input,select,button,th,td{
      font-family:"Montserrat",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif!important;
    }
    .breadcrumb{ background:transparent; padding-left:0; margin:12px 0 6px; }
    .breadcrumb > li + li:before{ color:#bbb; }
    .breadcrumb a{ text-decoration:underline; }

    .titulo-pagina{ color:#111; font-weight:700; margin:8px 0 14px; line-height:1.15; }

    .stepper{ display:flex; justify-content:center; gap:10px; margin:10px 0 22px; flex-wrap:wrap; }
    .step{
      display:flex; flex-direction:column; justify-content:center;
      min-width:210px; max-width:260px; padding:12px 16px;
      border:1px solid #e5e5e5; border-radius:6px; background:#fff; color:#111; text-align:center;
    }
    .step strong{ display:block; font-weight:700; }
    .step small{ display:block; color:#666; font-weight:500; margin-top:2px; }
    .step.active{ background:var(--gob-primary); border-color:var(--gob-primary); color:#fff; }
    .step.active small{ color:#fff; opacity:.95; }

    h3.subtitulo{ margin:4px 0 0!important; line-height:1.2; font-weight:700; color:#222; }
    .lbl-muted{ color:#666; margin:0 0 12px!important; line-height:1.45; }

    /* Campo select a 100% del ancho de la columna */
    .form-inline-imt{ display:flex; gap:10px; align-items:center; margin:10px 0 16px; }
    .form-inline-imt select{
      width:100%; min-height:36px; border:1px solid #cfcfcf; padding:6px 10px; border-radius:3px;
      -webkit-appearance:none; -moz-appearance:none; appearance:none;
      padding-right:2.25rem;
      background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
      background-repeat:no-repeat; background-position:right 12px center; background-size:14px;

      /* Evita que el valor mostrado rompa la línea del control */
      overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
    }
    /* En el menú desplegable, permite salto de línea para textos largos */
    #concepto_id option{ white-space:normal; word-break:break-word; }

    .form-help{ display:none; margin-top:6px; font-size:12px; color:#a94442; }
    .is-invalid{ border-color:#a94442!important; }

    .precio-preview{ margin-top:8px; color:#444; }

    @media (max-width:768px){
      .step{ min-width:180px; }
      .form-inline-imt{ flex-direction:column; align-items:stretch; }
      .btn{ width:100%; }
    }

    .btn,.btn:hover,.btn:focus,.btn:active{ text-decoration:none!important; }
    .btn{ min-height:40px; }
    .btn:focus-visible{ outline:2px solid #444; outline-offset:2px; box-shadow:none; }
    .btn.btn-primary{ background-color:var(--gob-primary); color:#fff!important; border:1px solid var(--gob-primary); }
    .btn.btn-primary:hover,.btn.btn-primary:focus,.btn.btn-primary:active{ background-color:#4d0e29; border-color:#4d0e29; color:#fff!important; }
    .btn.btn-default{ font-weight:400; background:#fff; color:#444!important; border:1px solid #ccc; }
    .btn.btn-default:hover,.btn.btn-default:focus,.btn.btn-default:active{ background:#fff; color:#444!important; border:1px solid #ccc; box-shadow:none; }

    .link-like{ background:none; border:none; padding:0; color:#2a5d2f; text-decoration:underline; cursor:pointer; font:inherit; }

    /* Por si tu hoja base no la trae */
    .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;}
  </style>
</head>
<body>

<main class="page" role="main" style="margin-top:30px">
  <div class="container">

    <ol class="breadcrumb">
      <li>
        <form id="form-back" action="{{ route('seleccion.back') }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" class="link-like">Inicio</button>
        </form>
      </li>
      <li class="active">Instituto Mexicano del Transporte</li>
    </ol>

    <center><h1 class="titulo-pagina">Instituto Mexicano del Transporte</h1></center>

    <div class="stepper">
      <div class="step active" aria-current="step"><strong>Paso 1</strong><small>Selección del trámite</small></div>
      <div class="step"><strong>Paso 2</strong><small>Información de la persona</small></div>
      <div class="step"><strong>Paso 3</strong><small>Formato de pago</small></div>
    </div>

    <h3 class="subtitulo">Selección del trámite:</h3>
    <p class="lbl-muted">Seleccione un concepto. El costo se mostrará automáticamente.</p>

    {{-- Form principal SOLO con el campo y el CSRF --}}
    <form id="form-next" action="{{ route('seleccion.next') }}" method="POST" novalidate>
      @csrf
      <div class="row">
        <div class="col-xs-12">
          <div class="form-inline-imt">
            <label for="concepto_id" class="sr-only">Selecciona un concepto</label>
            <select id="concepto_id" name="concepto_id" required aria-describedby="tramiteHelp">
              <option value="" disabled selected>Seleccione un concepto</option>
              @foreach($conceptos as $c)
                @php
                  $precio = number_format($c->importe/100, 2, '.', ',');
                  $texto  = "{$c->homoclave} — {$c->descripcion} — \${$precio} M.N.";
                @endphp
                <option value="{{ $c->id }}"
                        data-importe="{{ $c->importe }}"
                        data-homoclave="{{ $c->homoclave }}"
                        data-descripcion="{{ $c->descripcion }}">
                  {{ $texto }}
                </option>
              @endforeach
            </select>
          </div>
          <small id="tramiteHelp" class="form-help">Seleccione un concepto para continuar.</small>
          @error('concepto_id') <small class="form-help" style="display:block" role="alert">{{ $message }}</small> @enderror
        </div>
      </div>
    </form>

    <div class="row">
      <div class="col-xs-12">
        <div id="preview" class="precio-preview" style="display:none" aria-live="polite">
          <strong>Homoclave:</strong> <span id="pv-homo"></span><br>
          <strong>Concepto:</strong> <span id="pv-desc"></span><br>
          <strong>Importe:</strong> $<span id="pv-imp"></span> M.N.
        </div>
      </div>
    </div>

    {{-- Fila de botones usando el mismo grid, sin formularios anidados --}}
    <div class="row" style="margin-top:16px;">
      <div class="col-xs-6">
        <form id="form-back" action="{{ route('seleccion.back') }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" class="btn btn-default">Atrás</button>
        </form>
      </div>
      <div class="col-xs-6 text-right">
        <!-- Este botón envía el form principal por su id -->
        <button id="btnNext" type="submit" class="btn btn-primary" form="form-next">Siguiente</button>
      </div>
    </div>

    <br>
    <p class="lbl-muted"><strong>*</strong> Campos obligatorios</p>

  </div>
</main>

<script>
  const form    = document.getElementById('form-next');
  const sel     = document.getElementById('concepto_id');
  const help    = document.getElementById('tramiteHelp');

  const preview = document.getElementById('preview');
  const pvHomo  = document.getElementById('pv-homo');
  const pvDesc  = document.getElementById('pv-desc');
  const pvImp   = document.getElementById('pv-imp');

  function setError(show, msg){
    sel.classList.toggle('is-invalid', !!show);
    sel.setAttribute('aria-invalid', !!show);
    if(msg) help.textContent = msg;
    help.style.display = show ? 'block' : 'none';
  }
  function fmt(n){ return n.toLocaleString('es-MX',{minimumFractionDigits:2, maximumFractionDigits:2}); }

  sel.addEventListener('change', function(){
    if(!sel.value){
      setError(true, 'Seleccione un concepto para continuar.');
      preview.style.display='none';
      return;
    }
    setError(false, '');
    const opt = sel.options[sel.selectedIndex];
    const imp = Number(opt.dataset.importe || 0)/100;
    pvHomo.textContent = opt.dataset.homoclave || '';
    pvDesc.textContent = opt.dataset.descripcion || '';
    pvImp.textContent  = fmt(imp);
    preview.style.display = 'block';
  });

  // Validación al enviar (botón "Siguiente" apunta a este form)
  form.addEventListener('submit', function(e){
    if(!sel.value){
      e.preventDefault();
      setError(true, 'Seleccione un concepto para continuar.');
      sel.focus();
    }
  });
</script>

<script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>