<?php
function numeroAOrdinal($numero) {
    $ordinales = [
        0 => '',
        1 => '1er',
        2 => '2do',
        3 => '3er',
        4 => '4to',
        5 => '5to',
        6 => '6to',
        7 => '7mo',
        8 => '8vo',
        9 => '9no',
        10 => '10mo',
        11 => '11er',
    ];    
    return $ordinales[$numero] ?? $numero . 'º';
}
?>

<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="codescrito" class="form-label"><b>C&oacute;digo</b></label>
            <input type="text" id="codescrito" name="codescrito" class="form-control form-control-sm" maxlength="11" style="width:280px;" value="{{ old('codescrito', $libroescritos->codescrito ?? '') }}" @if(isset($libroescritos)) disabled @endif  onkeydown="verificacodbar(event)" onblur="this.value = this.value.toUpperCase()">
            @error('codescrito') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="fiscal" class="form-label"><b>Fiscal</b></label>

			  <select name="fiscal" id="fiscal" class="">
			          <option value="">-- Seleccione --</option>
			          @foreach($fiscales as $p)
			              <option value="{{ $p->id_personal }}" {{ old('fiscal', $libroescritos->id_fiscal ?? '') == $p->id_personal ? 'selected' : '' }}>
			                  {{ $p->apellido_paterno ." ". $p->apellido_materno ." ". $p->nombres }} 
			              </option>
			          @endforeach
                          </select>

            @error('fiscal') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="form-group" style="padding:5px;font-size:12px; color:blue;" id="descdependencia">
            {{ isset($libroescritos) ? (numeroAOrdinal($libroescritos->despacho) . " DESPACHO - " . $libroescritos->descridependencia) : '' }}
        </div>
        <input type="hidden" id="id_dependencia" name="id_dependencia" value="{{ old('id_dependencia', $libroescritos->id_dependencia ?? '') }}">
        <input type="hidden" id="despacho" name="despacho" value="{{ old('despacho', $libroescritos->despacho ?? '') }}">
        <!--<div class="form-group" style="padding:5px;font-size:12px; color:blue;" id="despacho">
            {{ isset($libroescritos) ? $libroescritos->despacho : '' }}
        </div>-->
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="tipo" class="form-label"><b>Tipo</b></label>
            <select name="tipo" id="tipo" class="form-select" style="width:150px;">
                <option value="" {{ (old('tipo', $libroescritos->tipo ?? '') == '') ? 'selected' : '' }}></option>
                <option value="E" {{ (old('tipo', $libroescritos->tipo ?? '') == 'E') ? 'selected' : '' }}>Escrito</option>
                <option value="O" {{ (old('tipo', $libroescritos->tipo ?? '') == 'O') ? 'selected' : '' }}>Oficio</option>
                <option value="S" {{ (old('tipo', $libroescritos->tipo ?? '') == 'S') ? 'selected' : '' }}>Solicitud</option>
                <option value="C" {{ (old('tipo', $libroescritos->tipo ?? '') == 'C') ? 'selected' : '' }}>Carta</option>
                <option value="I" {{ (old('tipo', $libroescritos->tipo ?? '') == 'I') ? 'selected' : '' }}>Invitaci&oacute;n</option>
                <option value="F" {{ (old('tipo', $libroescritos->tipo ?? '') == 'F') ? 'selected' : '' }}>Informe</option>
                <option value="Z" {{ (old('tipo', $libroescritos->tipo ?? '') == 'Z') ? 'selected' : '' }}>OTROS</option>
            </select>
            @error('tipo') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="descripcion" class="form-label"><b>Descripci&oacute;n</b></label>
            <input type="text" name="descripcion" class="form-control form-control-sm" maxlength="40" style="width:280px;" value="{{ old('descripcion', $libroescritos->descripcion ?? '') }}">
            @error('descripcion') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="iddeppolicial" class="form-label"><b>Dependencia Origen</b></label>

			  <select name="iddeppolicial" id="iddeppolicial" class="" style="width:280px;">
			          <option value=""></option>
			          @foreach($deppoli as $p)
			              <option value="{{ $p->id_deppolicial }}" 
                            @if(
                                old('iddeppolicial') == $p->id_deppolicial || 
                                (isset($libroescritos) && $libroescritos->dependenciapolicial == $p->descripciondep)
                            ) selected @endif>
                            {{ $p->descripciondep }}                          
			              </option>
			          @endforeach
                          </select>

            @error('deppolicial') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <input type="hidden" id="deppolicial" name="deppolicial" value="{{ old('deppolicial', $libroescritos->dependenciapolicial ?? '') }}">

</div>
<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="remitente" class="form-label"><b>Remitente</b></label>
            <input type="text" name="remitente" class="form-control form-control-sm" maxlength="30" style="width:280px;" value="{{ old('remitente', $libroescritos->remitente ?? '') }}">
            @error('remitente') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="carpetafiscal" class="form-label"><b>Carpeta Fiscal (Ejm. 501-2020-12345)</b></label>
            <input type="text" id="carpetafiscal" name="carpetafiscal" class="form-control form-control-sm" maxlength="25" style="width:250px;" placeholder="000-0000-00000" value="{{ old('carpetafiscal', $libroescritos->carpetafiscal ?? '') }}">
            @error('carpetafiscal') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="folios" class="form-label"><b>Folios</b></label>
            <input type="text" name="folios" class="form-control form-control-sm" maxlength="15" style="width:150px;" value="{{ old('folios', $libroescritos->folios ?? '') }}">
            @error('folios') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div style="background-color:#E6E6FA; padding:15px; border-radius:8px;"> <!-- AQUÍ EL FONDO -->

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
            <label for="carpetafiscal2" class="form-label"><b>Carpeta Fiscal</b></label>
            <input type="text" name="carpetafiscal2" id="carpetafiscal2" class="form-control" maxlength="25" style="width:250px; background: #ffffff !important; opacity: 1 !important;" value="{{ old('carpetafiscal2') }}" disabled >
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="dni" class="form-label"><b>DNI</b></label>
            <input type="text" name="dni" id="dni" class="form-control" maxlength="8" style="width:150px;" value="{{ old('dni') }}">
        </div>
    </div>
</div>

</div>

@if ($errors->has('error'))
<!-- Modal -->
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">ERROR AL GRABAR</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">{!! $errors->first('error') !!}
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


@endif

<style>
    .selectize-dropdown, .selectize-input, .selectize-input input {
        font-size: 11px!important;  /* O cualquier valor que desees */
    }
    .selectize-input {
        padding: 4px 4px!important;  
    }    
</style>


@section('scripts')
<script>
document.getElementById('carpetafiscal').addEventListener('input', function() {
    document.getElementById('carpetafiscal2').value = this.value;
});
</script>

<script>
  const iddependencia = @json($fiscales->pluck('id_dependencia', 'id_personal'));
  const descdependencia = @json($fiscales->pluck('descripcion', 'id_personal'));
  const despacho = @json($fiscales->pluck('despacho', 'id_personal'));

    $('#fiscal').selectize({
        onChange: function(value) {
            // Solo ejecuta la función si hay un valor seleccionado
            if (value) {
                muestradato(value);
            }
        },
        onInitialize: function() {
            let input = this.$control_input;
            input.on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Evita submit
                }
            });
        }
    });

    $('#iddeppolicial').selectize({
        create: false, // Permite ingresar nuevos valores
        sortField: 'text',
        persist: false, // Evita que los nuevos valores se guarden para futuras sesiones

        onInitialize: function() {

            // Aplica maxlength al input generado por selectize
            let input = this.$control_input;
            input.attr('maxlength', 35); // Cambia 20 por el máximo que quieras
            input.on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // 🚫 Evita submit
                }
            });
        },
        onChange: function(value) {
            if (value) {
                const selectize = this;
                const selectedOption = selectize.options[value];

                if (selectedOption && selectedOption.text) {
                    $('#deppolicial').val(selectedOption.text); // Pasa el texto visible al input
                }
            }
        }

    });


    
    function verificacodbar(event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
            limpiarCodigoBarra();
        }
    }
    function limpiarCodigoBarra() {
        let valor = document.getElementById("codescrito").value;
    // Buscar patrón: MP + dígitos + letra opcional + espacio opcional + más dígitos
    let match = valor.match(/MP\d+\s*[A-Z]?\s*\d+/i);
        if (match) {
            document.getElementById("codescrito").value = match[0].trim().toUpperCase();
        } else {
            document.getElementById("codescrito").value = '';
        }
    }
        
    
    document.addEventListener('DOMContentLoaded', function () {
        const codescritoInputs = document.querySelectorAll('input[name="codescrito"]');

        codescritoInputs.forEach(function (input) {
            // Al perder foco, convierte a mayúsculas
            input.addEventListener('blur', function () {
                this.value = this.value.toUpperCase();
            });

            // Encuentra el formulario más cercano y añade validación local
            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    const fiscalSelect = form.querySelector('select[name="fiscal"]');
                    const fiscalValue = fiscalSelect ? fiscalSelect.value.trim() : '';

                    if (!input.value.trim()) {
                        alert('INGRESE UN CÓDIGO.');
                        input.focus();
                        e.preventDefault(); // Evita envío
                        return;
                    }

                    if (!fiscalValue) {
                        alert('SELECCIONE UN FISCAL.');
                        if (fiscalSelect.selectize) {
                            fiscalSelect.selectize.focus(); // Enfoca el selectize
                        } else {
                            fiscalSelect.focus();
                        }
                        e.preventDefault();
                        return; 
                    }
                    let tipo = document.getElementById('tipo').value;
                    if (tipo === '') {
                        alert('INGRESA EL TIPO DE ESCRITO');
                        document.getElementById('tipo').focus();
                        e.preventDefault(); // Evita envío
                        return; 
                    }

                    let carfiscal = document.getElementById('carpetafiscal').value;
                    if (carfiscal === '') {
                        alert('INGRESA LA CARPETA FISCAL');
                        document.getElementById('carpetafiscal').focus();
                        e.preventDefault(); // Evita envío
                        return; 
                    }

                    let tpvoucher = document.getElementById('tipovoucher').value;
                    let nrvoucher = document.getElementById('nrovoucher').value;
                    let fecoperac = document.getElementById('fecoperacion').value;
                    let monto = document.getElementById('monto').value;
                    let dni = document.getElementById('dni').value;

                    if (tpvoucher === '') {
                        alert('SELECCIONA EL TP DE VOUCHER');
                        document.getElementById('tipovoucher').focus();
                        e.preventDefault(); // Evita envío
                        return; 
                    }
                    if (nrvoucher === '') {
                        alert('INGRESA EL NRO DE VOUCHER');
                        document.getElementById('nrovoucher').focus();
                        e.preventDefault(); // Evita envío
                        return; 
                    }
                    if (fecoperac === '') {
                        alert('INGRESA LA FECHA DE OPERACION');
                        document.getElementById('fecoperacion').focus();
                        e.preventDefault(); // Evita envío
                        return; 
                    }
                    if (monto === '') {
                        alert('INGRESA EL MONTO');
                        document.getElementById('monto').focus();
                        e.preventDefault(); // Evita envío
                        return; 
                    }
                    if (dni === '') {
                        alert('INGRESA DNI DEL SOLICITANTE');
                        document.getElementById('dni').focus();
                        e.preventDefault(); // Evita envío
                        return; 
                    }




                });
            }
        });
    });




//var element = document.getElementById('carpetafiscal');
//var maskOptions = {
//  mask: '000-0000-00000'
//};
//var mask = IMask(element, maskOptions);


    function muestradato(id) {
        $('#descdependencia').text(numeroAOrdinal(despacho[id]) + " DESPACHO - " + descdependencia[id]);
        //$('#despacho').text(numeroAOrdinal(despacho[id]) + " DESPACHO");
        $('#id_dependencia').val(iddependencia[id]);
        $('#despacho').val(despacho[id]);
    }
    function numeroAOrdinal(numero) {
        const ordinales = {
            1: '1er',
            2: '2do',
            3: '3er',
            4: '4to',
            5: '5to',
            6: '6to',
            7: '7mo',
            8: '8vo',
            9: '9no',
            10: '10mo',
            11: '11er'
        };
        if (numero==0){
            return ' ';
        } else {
            return ordinales[numero] || numero + ' ';
        }
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
</script>

@endsection