@extends('menu.index')

@section('content')
<div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    

@if(session('success'))
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('success') }}</b></div>
@else
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
@endif
<!--<div class="container mt-4">-->
    <!--<h2>Registrar Nuevo Expediente</h2>-->
    <form id="miFormulario" action="{{ route('mesapartes.grabavoucher') }}" method="POST" autocomplete="off">
        @csrf
            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">Registrar Nuevo Voucher</div>
                  </div>
                  <div class="card-body">





<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="form-group" style="padding:5px;">
            <label for="tipovoucher" class="form-label"><b>Tipo de Voucher</b></label>
            <select name="tipovoucher" id="tipovoucher" class="form-select" style="width:150px;">
                <option value="" ></option>
                <option value="BN" {{ old('tipovoucher') == 'BN' ? 'selected' : '' }}>VENTANILLA BN</option>
                <option value="AG" {{ old('tipovoucher') == 'AG' ? 'selected' : '' }}>AGENTE BN</option>
                <option value="PA" {{ old('tipovoucher') == 'PA' ? 'selected' : '' }}>PAGALO PE</option>
            </select>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label id="labelVoucher" for="nrovoucher" class="form-label"><b>Nro Voucher</b></label>
            <input type="text" name="nrovoucher" id="nrovoucher" class="form-control" maxlength="12" style="width:200px;" value="{{ old('nrovoucher') }}">
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="fecoperacion" class="form-label"><b>Fecha Operación</b></label>
            <input type="date" name="fecoperacion" id="fecoperacion" class="form-control" style="width:200px;" value="{{ old('fecoperacion') }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="form-group" style="padding:5px;">
            <label for="monto" class="form-label"><b>Monto</b></label>
            <input type="text" name="monto" id="monto" class="form-control text-end" maxlength="7" style="width:150px;" value="{{ old('monto') }}">
        </div>
    </div>
    <div class="col-md-5 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="carpetafiscal" class="form-label"><b>Carpeta Fiscal</b></label>
            <input type="text" name="carpetafiscal" id="carpetafiscal" class="form-control" maxlength="25" style="width:250px;" value="{{ old('carpetafiscal') }}">
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="dni" class="form-label"><b>DNI</b></label>
            <input type="text" name="dni" id="dni" class="form-control" maxlength="8" style="width:150px;" value="{{ old('dni') }}">
        </div>
    </div>
</div>



        <!--<button type="submit" class="btn btn-success mt-3">Guardar</button>-->
        <button type="button" class="btn btn-success mt-3" onclick="validarYEnviar()">
            Guardar
        </button>        
        <a href="{{ route('mesapartes.consultavouchers') }}" class="btn btn-secondary mt-3">Ir a consulta</a>

    

                  </div>
                </div>
              </div>
            </div>    
    </form>
<!--</div>-->

@if ($errors->any())
<!-- Modal -->
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">ERROR AL GRABAR</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">{!! $errors->first() !!}
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>
    
    </div>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var myModal = new bootstrap.Modal(document.getElementById('textoModal'));
    myModal.show();
});
</script>

    <script>
//        alert("{{ $errors->first() }}");
    </script>
@endif



@endsection


@section('scripts')
<script>
function validarYEnviar() {
    let tpvoucher = document.getElementById('tipovoucher').value;
    let nrvoucher = document.getElementById('nrovoucher').value;
    let fecoperac = document.getElementById('fecoperacion').value;
    let monto = document.getElementById('monto').value;
    let carfiscal = document.getElementById('carpetafiscal').value;
    let dni = document.getElementById('dni').value;

    if (tpvoucher === '') {
        alert('SELECCIONA EL TP DE VOUCHER');
        document.getElementById('tipovoucher').focus();
        return; 
    }
    if (nrvoucher === '') {
        alert('INGRESA EL NRO DE VOUCHER');
        document.getElementById('nrovoucher').focus();
        return; 
    }
    if (fecoperac === '') {
        alert('INGRESA LA FECHA DE OPERACION');
        document.getElementById('fecoperacion').focus();
        return; 
    }
    if (monto === '') {
        alert('INGRESA EL MONTO');
        document.getElementById('monto').focus();
        return; 
    }
    if (carfiscal === '') {
        alert('INGRESA LA CARPETA FISCAL');
        document.getElementById('carpetafiscal').focus();
        return; 
    }
    if (dni === '') {
        alert('INGRESA DNI DEL SOLICITANTE');
        document.getElementById('dni').focus();
        return; 
    }

    document.getElementById('miFormulario').submit();
}

</script>

<script>
var element = document.getElementById('monto');
var mask = IMask(element, {
  mask: Number,
  scale: 2,              // 2 decimales
  signed: false,
  thousandsSeparator: '', 
  padFractionalZeros: true,
  normalizeZeros: true,
  radix: '.',            // separador decimal
  mapToRadix: [',']      // por si escriben coma
});
var dni = document.getElementById('dni');
var maskDNI = IMask(dni, {
  mask: '00000000'
});


const tipo = document.getElementById('tipovoucher');
const nro = document.getElementById('nrovoucher');
const label = document.getElementById('labelVoucher');

tipo.addEventListener('change', function () {
    if (this.value === 'BN') {
        nro.maxLength = 7;
        label.innerHTML = '<b>Nro Voucher (máx. 7 dígitos)</b>';
    } else if (this.value === 'AG') {
        nro.maxLength = 12;
        label.innerHTML = '<b>Nro Voucher (máx. 12 dígitos)</b>';
    } else if (this.value === 'PA') {
        nro.maxLength = 12;
        label.innerHTML = '<b>Nro Voucher (máx. 12 dígitos)</b>';
    } else {
        nro.maxLength = 12;
        label.innerHTML = '<b>Nro Voucher</b>';
    }

    nro.value = "";
    if (nro.value.length > nro.maxLength) {
        //nro.value = nro.value.slice(0, nro.maxLength);
    }
});


window.onload = function() {
    var messageErr = document.getElementById('messageErr');
    var messageOK = document.getElementById('messageOK');
    if (messageErr) {
        setTimeout(function() {
            messageErr.style.opacity = '0';
            setTimeout(() => {
                messageErr.style.display = 'none';
            }, 500);
        }, 3000); 
    }
    if (messageOK) {
        setTimeout(function() {
            messageOK.style.opacity = '0';
            setTimeout(() => {
                messageOK.style.display = 'none';
            }, 500);
        }, 3000); 
    }
};
</script>

@endsection
