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
            <label for="codbarras" class="form-label"><b>Carpeta Fiscal</b></label>
            <input type="text" id="codbarras" name="codbarras" class="form-control form-control-sm" maxlength="25" style="width:250px;" onkeydown="verificarEnter(event)" value="{{ old('codbarras', $carpetassgf->carpetafiscal ?? '') }}">
            @error('codbarras') <div class="text-danger"><b>{{ $message }}</b></div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="dependencia"><b>Dependencia:</b></label>
            <select name="dependencia" id="dependencia" class="" data-live-search="true">
                <option value=""></option>
                @foreach ($dependencias as $datos)
                <option value="{{ $datos->id_dependencia }}" 
                    {{ old('dependencia', $carpetassgf->id_dependencia ?? '') == $datos->id_dependencia ? 'selected' : '' }}>
                    {{ $datos->descripcion }}
                </option>
                @endforeach
            </select>
            @error('dependencia') <div class="text-danger"><b>{{ $message }}</b></div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-2 col-lg-2">
        <div class="form-group" style="padding:5px;">
            <label for="despacho"><b>Despacho:</b></label>
            <select name="despacho" id="despacho" class="form-select form-control-sm" >
                <option value="" {{ old('despacho', $carpetassgf->despacho ?? null) === null ? 'selected' : '' }}></option>
                <option value="0" {{ (string) old('despacho', $carpetassgf->despacho ?? null) === '0' ? 'selected' : '' }}>DESPACHO</option>
                <option value="1" {{ old('despacho', $carpetassgf->despacho ?? '') == 1 ? 'selected' : '' }}>1er. DESPACHO</option>
                <option value="2" {{ old('despacho', $carpetassgf->despacho ?? '') == 2 ? 'selected' : '' }}>2do. DESPACHO</option>
                <option value="3" {{ old('despacho', $carpetassgf->despacho ?? '') == 3 ? 'selected' : '' }}>3er. DESPACHO</option>
                <option value="4" {{ old('despacho', $carpetassgf->despacho ?? '') == 4 ? 'selected' : '' }}>4to. DESPACHO</option>
                <option value="5" {{ old('despacho', $carpetassgf->despacho ?? '') == 5 ? 'selected' : '' }}>5to. DESPACHO</option>
                <option value="6" {{ old('despacho', $carpetassgf->despacho ?? '') == 6 ? 'selected' : '' }}>6to. DESPACHO</option>
                <option value="7" {{ old('despacho', $carpetassgf->despacho ?? '') == 7 ? 'selected' : '' }}>7mo. DESPACHO</option>
                <option value="8" {{ old('despacho', $carpetassgf->despacho ?? '') == 8 ? 'selected' : '' }}>8vo. DESPACHO</option>
                <option value="9" {{ old('despacho', $carpetassgf->despacho ?? '') == 9 ? 'selected' : '' }}>9no. DESPACHO</option>
                <option value="10" {{ old('despacho', $carpetassgf->despacho ?? '') == 10 ? 'selected' : '' }}>10mo. DESPACHO</option>
                <option value="11" {{ old('despacho', $carpetassgf->despacho ?? '') == 11 ? 'selected' : '' }}>11er. DESPACHO</option>
            </select>
            @error('despacho') <div class="text-danger"><b>{{ $message }}</b></div> @enderror
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
    $('#dependencia').selectize();
    let ultimaLectura = '';
    let tiempoUltimaLectura = 0;    
    function verificarEnter(event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter

            const ahora = Date.now();
            let codigo = document.getElementById("codbarras").value;
            // Si el mismo código fue enviado hace menos de 1000ms, ignorar
            if (codigo === ultimaLectura && (ahora - tiempoUltimaLectura) < 1000) {
            document.getElementById("codbarras").value='';
            document.getElementById('codbarras').focus();   
            return;
            }
            ultimaLectura = codigo;
            tiempoUltimaLectura = ahora;

            limpiarCodigoBarra();
        }
    }


    function limpiarCodigoBarra() {
        let valor = document.getElementById("codbarras").value;
        valor = valor.replace(/^[^0-9]+|[^0-9]+$/g, '');  // Remueve caracteres no alfanum�ricos del inicio y final

        valor = valor.trim();
        document.getElementById("codbarras").value = valor;
        //if (valor.length !== 25) {
        //    alert("El c\u00F3digo de barras " + valor + " no es v\u00E1lido. Solo tiene "+ valor.length +" caracteres.");
        //    return false;
        //}
	const codbarras = valor;

        $.ajax({
            url: '{{ route("carpetassgf.buscacarpeta") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                codbarras: codbarras
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {

                } else {

                    document.getElementById('messageErr').innerHTML ="<b>"+ mensaje + "</b>";
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


@endsection