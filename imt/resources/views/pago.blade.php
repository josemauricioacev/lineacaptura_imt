{{-- resources/views/pago.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Formato de pago</title>

  <link rel="stylesheet" href="https://framework-gb.cdn.gob.mx/assets/styles/main.css">
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

    .link-like{ background:none; border:none; padding:0; color:#2a5d2f; text-decoration:underline; cursor:pointer; font:inherit; }
    .link-like:focus-visible{ outline:2px solid #444; outline-offset:2px; }

    .titulo-pagina{ color:#111; font-weight:700; margin:8px 0 14px; line-height:1.15; }

    .stepper{ display:flex; justify-content:center; gap:10px; margin:10px 0 22px; flex-wrap:wrap; }
    .step{ display:flex; flex-direction:column; justify-content:center; min-width:210px; max-width:260px;
      padding:12px 16px; border:1px solid #e5e5e5; border-radius:6px; background:#fff; color:#111; text-align:center; }
    .step strong{ display:block; font-weight:700; }
    .step small{ display:block; color:#666; font-weight:500; margin-top:2px; }
    .step.active{ background:var(--gob-primary); border-color:var(--gob-primary); color:#fff; }
    .step.active small{ color:#fff; opacity:.95; }

    h3.subtitulo{ margin:4px 0 0 !important; line-height:1.2; font-weight:700; color:#222; }
    .lbl-muted{ color:#666; margin:0 0 12px !important; line-height:1.45; }

    .tabla-wrap{ overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .tabla-pago{ width:100%; border-collapse:collapse; min-width:640px; }
    .tabla-pago th, .tabla-pago td{ padding:10px 8px; border-top:1px solid #e5e5e5; vertical-align:middle; }
    .tabla-pago thead th{ border-top:none; color:#444; font-weight:600; }
    .importe{ width:220px; text-align:right; white-space:nowrap; }
    .input-mini{ width:140px; min-height:36px; border:1px solid #cfcfcf; padding:6px 10px; border-radius:3px; text-align:right; }
    .totales{ text-align:right; }

    .btn, .btn:hover, .btn:focus, .btn:active{ text-decoration:none !important; }
    .btn{ min-height:40px; }
    .btn:focus-visible{ outline:2px solid #444; outline-offset:2px; box-shadow:none; }
    .btn.btn-primary{ background-color:var(--gob-primary); color:#fff !important; border:1px solid var(--gob-primary); }
    .btn.btn-primary:hover, .btn.btn-primary:focus, .btn.btn-primary:active{ background-color:#4d0e29; border-color:#4d0e29; color:#fff !important; }
    .btn.btn-default{ font-weight:400; background:#fff; color:#444; border:1px solid #ccc; }
    .btn.btn-default:hover, .btn.btn-default:focus, .btn.btn-default:active{ background:#fff; color:#444; border:1px solid #ccc; box-shadow:none; }

    @media (max-width:768px){
      .step{ min-width:180px; }
      .btn{ width:100%; }
    }
  </style>
</head>
<body>
  <main class="page" role="main" style="margin-top:30px">
    <div class="container">

      @if (session('wizard_error'))
        <div class="alert alert-danger" role="alert">{{ session('wizard_error') }}</div>
      @endif
      @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
      @endif

      <ol class="breadcrumb" aria-label="miga de pan">
        <li>
          <form action="{{ route('pago.back') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="link-like">Información de la persona</button>
          </form>
        </li>
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

      @php
        $c = $concepto ?? null;
        $unitCents = $c['importe'] ?? 40000; // 400.00 default
        $unitPesos = number_format($unitCents / 100, 2, '.', ',');
        $desc      = $c['descripcion'] ?? 'CONCEPTO SELECCIONADO';
        $homoclave = $c['homoclave'] ?? '—';
      @endphp

      <div class="tabla-wrap">
        <table class="tabla-pago">
          <thead>
            <tr>
              <th>Descripción del concepto</th>
              <th class="importe">Importe unitario (M.N.)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <strong>Homoclave:</strong> {{ $homoclave }}<br>
                <strong>Concepto:</strong> {{ $desc }}
              </td>
              <td class="importe">
                <input id="precioUnit" class="input-mini" type="text" value="${{ $unitPesos }}" disabled data-cents="{{ $unitCents }}">
              </td>
            </tr>
            <tr>
              <td>Cantidad de trámites/servicios:</td>
              <td class="importe">
                <input id="cantidadPublica" class="input-mini" type="number" value="1" min="1" step="1">
              </td>
            </tr>
            <tr>
              <td class="totales">IVA (16%):</td>
              <td id="ivaTxt" class="importe">$0.00</td>
            </tr>
            <tr>
              <td class="totales"><strong>Total:</strong></td>
              <td id="totalTxt" class="importe"><strong>$0.00</strong></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="row" style="margin-top:18px;">
        <div class="col-xs-6">
          <form action="{{ route('pago.back') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-default">Atrás</button>
          </form>
        </div>
        <div class="col-xs-6 text-right">
          <form id="formGenerar" action="{{ route('pago.generar') }}" method="POST" style="display:inline">
            @csrf
            <input type="hidden" name="cantidad" id="cantidadHidden" value="1">
            <input type="hidden" name="subtotal_cents" id="subtotalHidden" value="0">
            <input type="hidden" name="iva_cents"       id="ivaHidden"       value="0">
            <input type="hidden" name="total_cents"     id="totalHidden"     value="0">
            <button type="submit" class="btn btn-primary">Generar línea de captura</button>
          </form>
        </div>
      </div>

      <br>
      <p class="lbl-muted" style="margin-top:10px;"><strong>*</strong> El importe final considera IVA al 16%.</p>

    </div>
  </main>

  <script>
    (function(){
      const precioUnit = document.getElementById('precioUnit');
      const cantidad   = document.getElementById('cantidadPublica');
      const ivaTxt     = document.getElementById('ivaTxt');
      const totalTxt   = document.getElementById('totalTxt');

      const cantidadHidden = document.getElementById('cantidadHidden');
      const subtotalHidden = document.getElementById('subtotalHidden');
      const ivaHidden      = document.getElementById('ivaHidden');
      const totalHidden    = document.getElementById('totalHidden');

      function fmt(n){ return n.toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2}); }

      function recalc(){
        const unit = parseInt(precioUnit.dataset.cents || '0', 10);
        const qty  = Math.max(1, parseInt(cantidad.value || '1', 10));

        const subtotal = unit * qty;
        const iva      = Math.round(subtotal * 0.16);
        const total    = subtotal + iva;

        ivaTxt.textContent   = '$' + fmt(iva/100);
        totalTxt.innerHTML   = '<strong>$'+ fmt(total/100) +'</strong>';

        cantidadHidden.value = qty;
        subtotalHidden.value = subtotal;
        ivaHidden.value      = iva;
        totalHidden.value    = total;
      }

      cantidad.addEventListener('input', recalc);
      recalc();

      document.getElementById('formGenerar').addEventListener('submit', function(){
        cantidadHidden.value = Math.max(1, parseInt(cantidad.value || '1', 10));
        recalc();
      });
    })();
  </script>

  <script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>
