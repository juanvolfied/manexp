

<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="form-group" style="padding:5px;">
            <label for="codbarras" class="form-label"><b>C&oacute;digo de Barras (25 caracteres)</b></label>
            <input type="text" id="codbarras" name="codbarras" class="form-control form-control-sm" maxlength="30" autofocus onkeydown="verificarEnter(event)" onkeyup="muestranroexpediente(event)" value="{{ old('codbarras', $expediente->codbarras ?? '') }}" {{ isset($expediente) ? 'readonly' : '' }}>
            @error('codbarras') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-9 col-lg-9">
        <label id="nexpe" class="form-label" style="{{ isset($expediente) ? '' : 'display:none;' }}"><b>Nro Expediente</b></label>
        <div class="form-group" style="padding:5px;font-size:20px; color:red;" id="nroexpediente">
            {{ isset($expediente) ? $expediente->id_dependencia."-".$expediente->ano_expediente."-".$expediente->nro_expediente."-".$expediente->id_tipo : '' }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="imputado" class="form-label"><b>Imputado</b></label>
            <input type="text" id="imputado" name="imputado" class="form-control form-control-sm" maxlength="100" value="{{ old('imputado', $expediente->imputado ?? '') }}">
            @error('imputado') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="agraviado" class="form-label"><b>Agraviado</b></label>
            <input type="text" name="agraviado" class="form-control form-control-sm" maxlength="100" value="{{ old('agraviado', $expediente->agraviado ?? '') }}">
            @error('agraviado') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="delito" class="form-label"><b>Delito</b></label>

			  <select name="delito" id="delito" class="">
			          <option value="">-- Seleccione --</option>
			          @foreach($delitos as $p)
			              <option value="{{ $p->id_delito }}" {{ old('delito', $expediente->delito ?? '') == $p->id_delito ? 'selected' : '' }}>
			                  {{ $p->desc_delito }} 
			              </option>
			          @endforeach
                          </select>

            @error('delito') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="nro_oficio" class="form-label"><b>Nro Oficio</b></label>
            <input type="text" name="nro_oficio" class="form-control form-control-sm" maxlength="30" value="{{ old('nro_oficio', $expediente->nro_oficio ?? '') }}">
            @error('nro_oficio') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-2 col-lg-2">
        <div class="form-group" style="padding:5px;">
            <label for="nro_folios" class="form-label"><b>Folios</b></label>
            <input type="text" name="nro_folios" class="form-control form-control-sm" maxlength="5" value="{{ old('nro_folios', $expediente->nro_folios ?? '') }}">
            @error('nro_folios') <div class="text-danger">{{ $message }}</div> @enderror
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
    $('#delito').selectize();
</script>

<script>
function muestranroexpediente(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
    }
    let valor = document.getElementById("codbarras").value;
    valor = valor.replace(/^[^0-9]+|[^0-9]+$/g, '');  // Remueve caracteres no alfanum�ricos del inicio y final
    valor = valor.trim();
    if (valor.length == 25) {
        const codbarras = valor;
        const dependencia = parseInt(valor.substring(0, 11)); 
        const ano = valor.substring(11, 15); 
        const nroexpediente = parseInt(valor.substring(15, 21)); 
        const tipo = parseInt(valor.substring(21, 25)); 
        document.getElementById("nroexpediente").innerHTML="<b>"+dependencia+"-"+ano+"-"+nroexpediente+"-"+tipo+"</b>";
        document.getElementById("nexpe").style.display = "block";
    } else {
        document.getElementById("nroexpediente").innerHTML="";
        document.getElementById("nexpe").style.display = "none";
    }
}    
function verificarEnter(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
        limpiarCodigoBarra();
    }
}

function limpiarCodigoBarra() {
    let valor = document.getElementById("codbarras").value;
    valor = valor.replace(/^[^0-9]+|[^0-9]+$/g, '');  // Remueve caracteres no alfanum�ricos del inicio y final

    valor = valor.trim();
    document.getElementById("codbarras").value = valor;
    if (valor.length !== 25) {
//        alert("El c\u00F3digo de barras " + valor + " no es v\u00E1lido. Tiene "+ valor.length +" caracteres.");

                document.getElementById("codbarras").value='';
                document.getElementById('codbarras').focus();        

                document.getElementById('messageErr').innerHTML = "<b>El c\u00F3digo de barras " + valor + " no es v\u00E1lido. Tiene "+ valor.length +" caracteres.</b>";
                var messageErr = document.getElementById('messageErr');
                messageErr.style.opacity = '1';
                messageErr.style.display = 'block';
                setTimeout(function() {
                    messageErr.style.opacity = '0';
                    setTimeout(() => {
                        messageErr.style.display = 'none';
                    }, 500);
                
                }, 3000); 
        

        return false;
    }

    const codbarras = valor;
    const dependencia = parseInt(valor.substring(0, 11)); 
    const ano = valor.substring(11, 15); 
    const nroexpediente = parseInt(valor.substring(15, 21)); 
    const tipo = parseInt(valor.substring(21, 25)); 

    var formData = $('#miFormulario').serialize();
    $.ajax({
        url: '{{ route("expediente.busca") }}',
        method: 'POST',
        data: formData,
        success: function(response) {
            let mensaje = response.message || 'Respuesta sin mensaje';
            if (response.success) {
                document.getElementById("imputado").focus();
            } else {
                document.getElementById("nroexpediente").innerHTML="";
                document.getElementById("nexpe").style.display = "none";
                document.getElementById("codbarras").value='';
                document.getElementById('codbarras').focus();        

                document.getElementById('messageErr').innerHTML = '<b>'+ mensaje +'</b>';
                var messageErr = document.getElementById('messageErr');
                messageErr.style.opacity = '1';
                messageErr.style.display = 'block';
                setTimeout(function() {
                    messageErr.style.opacity = '0';
                    setTimeout(() => {
                        messageErr.style.display = 'none';
                    }, 500);
                
                }, 3000); 
            }
        },
        error: function(xhr) {
            //$('#mensaje-guardar').html('<div style="color: red;">Error inesperado</div>');
            //console.error(xhr.responseText);
        }
    });
}
</script>

@if ($errors->any())
    <script>
    const fakeEvent = new KeyboardEvent('keydown', { key: 'Enter' });
    muestranroexpediente(fakeEvent); // esto ejecuta la función como si se hubiera presionado una tecla
    </script>
@endif

@endsection