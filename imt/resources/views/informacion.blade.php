{{-- resources/views/informacion.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Información de la persona</title>

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
    .link-like{ background:none; border:none; padding:0; color:#2a5d2f; text-decoration:underline; cursor:pointer; font:inherit; }
    .link-like:focus-visible{ outline:2px solid #444; outline-offset:2px; }
    .titulo-pagina{ color:#111; font-weight:700; margin:8px 0 14px; line-height:1.15; }
    .stepper{ display:flex; justify-content:center; gap:10px; margin:10px 0 22px; flex-wrap:wrap; }
    .step{ display:flex; flex-direction:column; justify-content:center; min-width:210px; max-width:260px; padding:12px 16px; border:1px solid #e5e5e5; border-radius:6px; background:#fff; color:#111; text-align:center; }
    .step.active{ background:var(--gob-primary); border-color:var(--gob-primary); color:#fff; }
    h3.subtitulo{ margin:4px 0 0!important; line-height:1.2; font-weight:700; color:#222; }
    .lbl-muted{ color:#666; margin:0 0 12px!important; line-height:1.45; }

    .form-grid{ display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
    .form-grid label{ font-weight:600; color:#444; margin-bottom:6px; display:block; }
    .form-grid input,.form-grid select{
      width:100%; min-height:36px; border:1px solid #cfcfcf; padding:6px 10px; border-radius:3px;
    }
    .form-grid input[type="text"]{ text-transform:uppercase; }

    .form-grid select{
      -webkit-appearance:none; -moz-appearance:none; appearance:none;
      padding-right:2.25rem;
      background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
      background-repeat:no-repeat; background-position:right 12px center; background-size:14px;
    }
    select::-ms-expand{ display:none; }

    .form-help{ display:none; margin-top:6px; font-size:12px; color:#a94442; }
    .is-invalid{ border-color:#a94442!important; }

    .btn,.btn:hover,.btn:focus,.btn:active{ text-decoration:none!important; }
    .btn{ min-height:40px; }
    .btn:focus-visible{ outline:2px solid #444; outline-offset:2px; box-shadow:none; }
    .btn.btn-primary{ background-color:var(--gob-primary); color:#fff!important; border:1px solid var(--gob-primary); }
    .btn.btn-primary:hover{ background-color:#4d0e29; }

    .btn.btn-default{ background:#fff; color:#444!important; border:1px solid #ccc; }
    .btn.btn-default:hover{ background:#f7f7f7; color:#444!important; }
    .btn.btn-default:focus,.btn.btn-default:active,.btn.btn-default:active:focus{
      background:#f2f2f2!important; color:#444!important; border-color:#bbb!important; box-shadow:none!important;
    }

    [data-hidden="true"]{ display:none !important; }

    @media (max-width:992px){ .form-grid{ grid-template-columns:1fr 1fr; } }
    @media (max-width:640px){ .form-grid{ grid-template-columns:1fr; } }
    @media (max-width:768px){ .step{ min-width:180px; } .btn{ width:100%; } }
  </style>
</head>
<body>
<main class="page" role="main" style="margin-top:30px">
  <div class="container">

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

    <form id="form-info" action="{{ route('informacion.next') }}" method="POST" novalidate>
      @csrf
      <div class="form-grid" role="form" aria-label="Formulario de datos personales">
        {{-- Tipo de persona --}}
        <div style="grid-column:1 / span 3;">
          <label for="tipoPersona">Seleccione tipo de persona:</label>
          <select id="tipoPersona" name="tipoPersona" required aria-describedby="tipoPersonaHelp">
            <option value="" disabled {{ old('tipoPersona') ? '' : 'selected' }}>Seleccione una opción</option>
            <option value="FISICA" {{ old('tipoPersona')==='FISICA' ? 'selected' : '' }}>Persona Física</option>
            <option value="MORAL"  {{ old('tipoPersona')==='MORAL'  ? 'selected' : '' }}>Persona Moral</option>
          </select>
          <small id="tipoPersonaHelp" class="form-help">Seleccione el tipo de persona.</small>
          @error('tipoPersona') <small class="form-help" style="display:block">{{ $message }}</small> @enderror
        </div>

        {{-- CURP (solo PF) --}}
        <div data-pf id="wrap-curp" data-hidden="true">
          <label for="curp">CURP:</label>
          <input id="curp" name="curp" type="text" maxlength="18"
                 value="{{ old('curp') }}"
                 title="CURP de 18 caracteres (estructura oficial)">
          <small id="curpHelp" class="form-help">Ingrese una CURP válida (18 caracteres).</small>
          @error('curp') <small class="form-help" style="display:block">{{ $message }}</small> @enderror
        </div>

        {{-- RFC (común PF/PM) --}}
        <div data-common id="wrap-rfc" data-hidden="true">
          <label for="rfc">RFC:</label>
          <input id="rfc" name="rfc" type="text" maxlength="13"
                 value="{{ old('rfc') }}"
                 title="RFC: PF 13 / PM 12">
          <small id="rfcHelp" class="form-help">Capture un RFC válido.</small>
          @error('rfc') <small class="form-help" style="display:block">{{ $message }}</small> @enderror
        </div>

        {{-- Nombre(s) (solo PF) --}}
        <div data-pf id="wrap-nombres" data-hidden="true">
          <label for="nombres">Nombre(s):</label>
          <input id="nombres" name="nombres" type="text" value="{{ old('nombres') }}" title="Ingrese su nombre">
          <small id="nombresHelp" class="form-help">Ingrese su(s) nombre(s).</small>
          @error('nombres') <small class="form-help" style="display:block">{{ $message }}</small> @enderror
        </div>

        {{-- Apellido paterno (solo PF) --}}
        <div data-pf id="wrap-ap1" data-hidden="true">
          <label for="ap1">Apellido paterno:</label>
          <input id="ap1" name="ap1" type="text" value="{{ old('ap1') }}" title="Ingrese su apellido paterno">
          <small id="ap1Help" class="form-help">Ingrese su apellido paterno.</small>
          @error('ap1') <small class="form-help" style="display:block">{{ $message }}</small> @enderror
        </div>

        {{-- Apellido materno (solo PF) --}}
        <div data-pf id="wrap-ap2" data-hidden="true">
          <label for="ap2">Apellido materno:</label>
          <input id="ap2" name="ap2" type="text" value="{{ old('ap2') }}" title="Ingrese su apellido materno">
          <small id="ap2Help" class="form-help">Ingrese su apellido materno.</small>
          @error('ap2') <small class="form-help" style="display:block">{{ $message }}</small> @enderror
        </div>

        {{-- Razón social (solo PM) --}}
        <div data-pm id="wrap-razon" data-hidden="true">
          <label for="razon">Razón social:</label>
          <input id="razon" name="razon" type="text" value="{{ old('razon') }}" title="Ingrese la denominación o razón social">
          <small id="razonHelp" class="form-help">Ingrese la razón social.</small>
          @error('razon') <small class="form-help" style="display:block">{{ $message }}</small> @enderror
        </div>
      </div>

      <div class="row" style="margin-top:16px;">
        <div class="col-xs-6">
          <button type="submit" class="btn btn-default" form="form-back" formmethod="POST">Atrás</button>
        </div>
        <div class="col-xs-6 text-right">
          <button id="btnNext" type="submit" class="btn btn-primary">Siguiente</button>
        </div>
      </div>
    </form>

    <br>
    <p class="lbl-muted" style="margin-top:10px;"><strong>*</strong> Campos obligatorios</p>
  </div>
</main>

<script>
  // Mayúsculas automáticas
  document.querySelectorAll('input[type="text"]').forEach(el=>{
    el.addEventListener('input',()=>{ el.value = el.value.toUpperCase(); });
  });

  const form = document.getElementById('form-info');
  const tipoPersona = document.getElementById('tipoPersona');
  const curp = document.getElementById('curp');
  const rfc = document.getElementById('rfc');
  const nombres = document.getElementById('nombres');
  const ap1 = document.getElementById('ap1');
  const ap2 = document.getElementById('ap2');
  const razon = document.getElementById('razon');

  const wrapPF = document.querySelectorAll('[data-pf]');
  const wrapPM = document.querySelectorAll('[data-pm]');
  const wrapCommon = document.querySelectorAll('[data-common]');

  const touched = { tipoPersona:false, curp:false, rfc:false, nombres:false, ap1:false, ap2:false, razon:false };
  let attemptedSubmit = false;
  [tipoPersona, curp, rfc, nombres, ap1, ap2, razon].forEach(el=>{
    if(!el) return;
    el.addEventListener('blur', e=>{
      const id = e.target.id; if(id in touched) touched[id]=true;
    });
  });

  function setError(input, helpEl, show, msg){
    if(!input || !helpEl) return;
    input.classList.toggle('is-invalid', !!show);
    input.setAttribute('aria-invalid', !!show);
    if(msg) helpEl.textContent = msg;
    helpEl.style.display = show ? 'block' : 'none';
  }
  function shouldShow(id){ return attemptedSubmit || touched[id]; }

  function validFechaYYMMDD(str){
    if(!/^\d{6}$/.test(str)) return false;
    const yy = parseInt(str.slice(0,2),10);
    const mm = parseInt(str.slice(2,4),10);
    const dd = parseInt(str.slice(4,6),10);
    if(mm<1||mm>12) return false;
    const fullYear = yy + 1900;
    const diasMes = [31,((fullYear%4===0&&fullYear%100!==0)||(fullYear%400===0))?29:28,31,30,31,30,31,31,30,31,30,31];
    return dd>=1 && dd<=diasMes[mm-1];
  }

  const ENTIDADES = new Set(['AS','BC','BS','CC','CL','CM','CS','CH','DF','DG','GT','GR','HG','JC','MC','MN','MS','NT','NL','OC','PL','QT','QR','SP','SL','SR','TC','TL','TS','VZ','YN','ZS','NE']);
  function curpValida(c){
    if(!c) return false;
    c = c.trim().toUpperCase();
    const re = /^([A-Z][AEIOUX][A-Z]{2})(\d{2})(\d{2})(\d{2})([HM])(AS|BC|BS|CC|CL|CM|CS|CH|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TL|TS|VZ|YN|ZS|NE)([B-DF-HJ-NP-TV-Z]{3})([A-Z0-9])(\d)$/;
    const m = c.match(re);
    if(!m) return false;
    const yymmdd = m[2]+m[3]+m[4];
    if(!validFechaYYMMDD(yymmdd)) return false;
    if(!ENTIDADES.has(m[6])) return false;
    return true;
  }

  function rfcValidoPorTipo(r, tipo){
    if(!r) return false;
    r = r.trim().toUpperCase();
    if(tipo === 'FISICA'){
      const m = r.match(/^([A-ZÑ&]{4})(\d{2})(\d{2})(\d{2})([A-Z0-9]{3})$/);
      if(!m) return false;
      return validFechaYYMMDD(m[2]+m[3]+m[4]);
    }else if(tipo === 'MORAL'){
      const m = r.match(/^([A-ZÑ&]{3})(\d{2})(\d{2})(\d{2})([A-Z0-9]{3})$/);
      if(!m) return false;
      return validFechaYYMMDD(m[2]+m[3]+m[4]);
    }
    return /^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/.test(r);
  }

  function hideGroup(nodeList, hide=true){
    nodeList.forEach(n=>{
      n.setAttribute('data-hidden', hide ? 'true' : 'false');
      const input = n.querySelector('input');
      const help = n.querySelector('.form-help');
      if(hide){
        if(input){ input.classList.remove('is-invalid'); input.removeAttribute('aria-invalid'); }
        if(help){ help.style.display = 'none'; }
      }
    });
  }

  function updateUIByTipo(){
    const value = tipoPersona.value;
    const pf = value === 'FISICA';
    const pm = value === 'MORAL';

    if(!pf && !pm){
      hideGroup(wrapPF, true);
      hideGroup(wrapPM, true);
      hideGroup(wrapCommon, true);
      if(curp) curp.required = false;
      if(nombres) nombres.required = false;
      if(ap1) ap1.required = false;
      if(ap2) ap2.required = false;
      if(razon) razon.required = false;
      rfc.required = false;
      rfc.setAttribute('maxlength','13');
      document.getElementById('rfcHelp').textContent = 'Capture un RFC válido.';
      return;
    }

    hideGroup(wrapCommon, false);
    if(pf){
      hideGroup(wrapPF, false);
      hideGroup(wrapPM, true);

      if(curp) curp.required = true;
      if(nombres) nombres.required = true;
      if(ap1) ap1.required = true;
      if(ap2) ap2.required = true;
      if(razon) razon.required = false;

      rfc.required = true;
      rfc.setAttribute('maxlength','13');
      rfc.setAttribute('title','RFC de persona física: 13 caracteres (LLLL + YYMMDD + 3)');
      document.getElementById('rfcHelp').textContent = 'RFC de persona física: 13 caracteres (LLLL + YYMMDD + 3).';
    }else{
      hideGroup(wrapPF, true);
      hideGroup(wrapPM, false);

      if(curp) curp.required = false;
      if(nombres) nombres.required = false;
      if(ap1) ap1.required = false;
      if(ap2) ap2.required = false;
      if(razon) razon.required = true;

      rfc.required = true;
      rfc.setAttribute('maxlength','12');
      rfc.setAttribute('title','RFC de persona moral: 12 caracteres (LLL + YYMMDD + 3)');
      document.getElementById('rfcHelp').textContent = 'RFC de persona moral: 12 caracteres (LLL + YYMMDD + 3).';
    }
  }

  function validateAll(){
    let ok = true;
    const tipoOk = !!tipoPersona.value;
    setError(tipoPersona, document.getElementById('tipoPersonaHelp'),
      shouldShow('tipoPersona') && !tipoOk, 'Seleccione el tipo de persona.');
    ok = ok && tipoOk;

    if(!tipoOk) return false;

    const pf = tipoPersona.value === 'FISICA';
    const pm = tipoPersona.value === 'MORAL';

    const rfcOk = rfcValidoPorTipo(rfc.value, tipoPersona.value);
    setError(rfc, document.getElementById('rfcHelp'),
      shouldShow('rfc') && !rfcOk,
      pm ? 'RFC de persona moral inválido (LLL + YYMMDD + 3).'
         : 'RFC de persona física inválido (LLLL + YYMMDD + 3).'
    );
    ok = ok && rfcOk;

    if(pf){
      const nomOk = (nombres?.value || '').trim().length>0;
      setError(nombres, document.getElementById('nombresHelp'),
        shouldShow('nombres') && !nomOk, 'Ingrese su(s) nombre(s).');
      ok = ok && nomOk;

      const ap1Ok = (ap1?.value || '').trim().length>0;
      setError(ap1, document.getElementById('ap1Help'),
        shouldShow('ap1') && !ap1Ok, 'Ingrese su apellido paterno.');
      ok = ok && ap1Ok;

      const ap2Ok = (ap2?.value || '').trim().length>0;
      setError(ap2, document.getElementById('ap2Help'),
        shouldShow('ap2') && !ap2Ok, 'Ingrese su apellido materno.');
      ok = ok && ap2Ok;

      const curpOk = curpValida(curp.value);
      setError(curp, document.getElementById('curpHelp'),
        shouldShow('curp') && !curpOk, 'CURP inválida (revise estructura y fecha).');
      ok = ok && curpOk;
    }else{
      const razonOk = (razon?.value || '').trim().length>0;
      setError(razon, document.getElementById('razonHelp'),
        shouldShow('razon') && !razonOk, 'Ingrese la razón social.');
      ok = ok && razonOk;

      setError(curp, document.getElementById('curpHelp'), false);
      setError(nombres, document.getElementById('nombresHelp'), false);
      setError(ap1, document.getElementById('ap1Help'), false);
      setError(ap2, document.getElementById('ap2Help'), false);
    }

    return ok;
  }

  form.addEventListener('submit', function(e){
    attemptedSubmit = true;
    if(!validateAll()){
      e.preventDefault();
      const firstInvalid = form.querySelector('.is-invalid') || tipoPersona;
      if(firstInvalid) firstInvalid.focus();
    }
  });

  tipoPersona.addEventListener('change', ()=>{
    updateUIByTipo();
    if(attemptedSubmit) validateAll();
  });
  [curp, rfc, nombres, ap1, ap2, razon].forEach(el=>{
    if(!el) return;
    el.addEventListener('input', ()=>{ if(attemptedSubmit) validateAll(); });
  });

  updateUIByTipo();
</script>

<script src="https://framework-gb.cdn.gob.mx/gobmx.js"></script>
</body>
</html>
