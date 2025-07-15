

<div class="row">
    <div class="col-md-6 col-lg-6">
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
            {{ isset($libroescritos) ? $libroescritos->descripcion : '' }}
        </div>
        <input type="hidden" id="id_dependencia" name="id_dependencia">
        <input type="hidden" id="despacho" name="despacho">
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
            <input type="text" name="descripcion" class="form-control form-control-sm" maxlength="20" value="{{ old('descripcion', $libroescritos->descripcion ?? '') }}">
            @error('descripcion') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="remitente" class="form-label"><b>Remitente</b></label>
            <input type="text" name="remitente" class="form-control form-control-sm" maxlength="20" value="{{ old('remitente', $libroescritos->remitente ?? '') }}">
            @error('remitente') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="carpetafiscal" class="form-label"><b>Carpeta Fiscal</b></label>
            <input type="text" name="carpetafiscal" class="form-control form-control-sm" maxlength="25" value="{{ old('carpetafiscal', $libroescritos->carpetafiscal ?? '') }}">
            @error('carpetafiscal') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="folios" class="form-label"><b>Folios</b></label>
            <input type="text" name="folios" class="form-control form-control-sm" maxlength="15" value="{{ old('folios', $libroescritos->folios ?? '') }}">
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
        }
    });
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
            10: '10mo'
        };
        if (numero==0){
            return ' ';
        } else {
            return ordinales[numero] || numero + ' ';
        }
    }        
</script>


@endsection