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
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" class="form-select" style="width:150px;">
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
            <input type="text" name="descripcion" class="form-control form-control-sm" maxlength="30" style="width:280px;" value="{{ old('descripcion', $libroescritos->descripcion ?? '') }}">
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

    

var element = document.getElementById('carpetafiscal');
var maskOptions = {
  mask: '000-0000-00000'
};
var mask = IMask(element, maskOptions);


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


@endsection