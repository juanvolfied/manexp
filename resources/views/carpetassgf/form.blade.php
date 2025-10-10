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
<!--
<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="codbarras" class="form-label"><b>Carpeta Fiscal</b></label>
            <input type="text" id="codbarras" name="codbarras" class="form-control form-control-sm" maxlength="25" style="width:250px;" onkeydown="verificarEnter(event)" value="{{ old('codbarras', $carpetassgf->carpetafiscal ?? '') }}">
            @error('codbarras') <div class="text-danger"><b>{{ $message }}</b></div> @enderror
        </div>
    </div>
</div>
-->
                    <div class="row" id="codigoverificar">
                      <div class="col-md-4 col-lg-4">
                        <div class="form-group border p-3 rounded shadow-sm bg-light">
                            <h5 class="text-primary"><i class="fas fa-barcode"></i> Por Código de Carpeta Fiscal</h5>
                            <p class="text-muted small"><b>Ingrese el código, y presione Enter o el botón.</b></p>

                          <!--<label for="codbarras"><b>C&oacute;digo de Carpeta Fiscal</b></label>-->
                          
                          <div class="input-group">

                          <input type="text" class="form-control" name="codbarras" id="codbarras" placeholder="C&oacute;d. Carpeta Fiscal" onkeydown="verificarEnter(event)" autofocus/>
                          <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="limpiarCodigoBarra()">
                          <i class="fas fa-check me-1"></i> Verificar
                          </button>

                          </div>
                          <small id="msgerr" class="form-text text-muted text-danger" style="display:none;">Escanee con lector o digite el codigo y presione enter.</small>

                        </div>
                        @error('codbarras') <div class="text-danger"><b>{{ $message }}</b></div> @enderror

                      </div>
                      <div class="col-md-8 col-lg-8">
                        <div class="form-group border p-3 rounded shadow-sm bg-light">
                        <h5 class="text-primary"><i class="fas fa-folder-open"></i> Por detalle de Carpeta</h5>
                        <p class="text-muted small"><b>Complete los campos (Ejm. 1506014501-2016-130-0) y presione el botón.</b></p>

                          <div class="card-title mt-0 text-danger text-center" id="cantexp" style="display:none;"></div>

        <div class="row">
        <div class="col-md-3">
          <input type="text" id="iddependencia" name="iddependencia" class="form-control text-center" maxlength="11" placeholder="id dependencia" >
        </div>
        <div class="col-md-2" style="width:100px;">
          <!--<label for="ano" class="form-label"><b>A&ntilde;o</b></label>-->
          <input type="text" id="ano" name="ano" class="form-control text-center" maxlength="4" placeholder="A&ntilde;o" style="width:70px;">
        </div>
        <div class="col-md-2">
          <!--<label for="nroexp" class="form-label"><b>Nro Expediente</b></label>-->
          <input type="text" id="nroexp" name="nroexp" class="form-control text-center" maxlength="6" placeholder="Expediente" style="width:100px;">
        </div>
        <div class="col-md-2">
          <!--<label for="nroexp" class="form-label"><b>Nro Expediente</b></label>-->
          <input type="text" id="tipo" name="tipo" class="form-control text-center" maxlength="4" placeholder="Tipo" style="width:70px;">
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <a href="#" onclick="buscapordetalle(event)" class="btn btn-primary w-100"><i class="fas fa-check me-1"></i> Verificar</a>
        </div>
        </div>
                          <small id="msgerr2" class="form-text text-muted text-danger" style="display:none;">Escanee con lector o digite el codigo y presione enter.</small>
                          

                        </div>
                      </div>

                    </div>


                    <div class="row" id="datosgrabar" style="display:none;">
                      <div class="col-md-12 col-lg-12">

<div class="row">
    <div class="col-md-6 col-lg-6">
        <div id="codcarpeta" class="form-group" style="padding:5px;">
            <b>Carpeta Fiscal:</b>
        </div>
        <input type="hidden" id="codbarrasgrabar" name="codbarrasgrabar">
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

                      </div>
                    </div>

        <div class="btns-container" style="display: flex; gap: 10px; align-items: center;">

        <!--<button type="submit" class="btn btn-success mt-3">Guardar</button>-->
        <button onclick="grabar()" class="btn btn-success mt-3" id="btngrabar" style="display:none;">Guardar</button>
        <a href="{{ route('carpetassgf.carpetassgfindex') }}" class="btn btn-secondary mt-3">Retornar al Listado</a>

        </div><br>


        

                    <div class="row" id="codigoverificar">
                      <div class="col-md-12 col-lg-12">
                        <div class="form-group border p-3 rounded shadow-sm bg-light">        
        <h5 class="text-primary">Mis Carpetas SGF registradas hoy {{ date('Y-m-d') }}</h5>
        <table id="tablacarpetassgf" class="table table-striped table-bordered" width=100%>
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Codigo Carpeta Fiscal</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Carpeta</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Dependencia</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Despacho</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Fecha</th>
                    <!--<th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Usuario</th>-->
                </tr>
            </thead>
            <tbody style="font-size:11px;">
                @foreach($carpetasregissgf as $p)
                @php
                    $esHoy = date('Y-m-d') == date('Y-m-d', strtotime($p->fechahora_registro));

                    $raw = $p->carpetafiscal;

                    // Cortar las partes según posiciones
                    $parte1 = ltrim(substr($raw, 0, 11), '0');
                    $parte2 = ltrim(substr($raw, 11, 4), '0');
                    $parte3 = ltrim(substr($raw, 15, 6), '0');
                    $parte4 = ltrim(substr($raw, 21, 4), '0') ?: '0';

                    $carpetaFormateada = $parte1 . '-' . $parte2 . '-' . $parte3 . '-' . $parte4;
                @endphp
                    <tr>
                        <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->carpetafiscal }}</td>
                        <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $carpetaFormateada }}</td>
                        <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->abreviado }}</td>
                        <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ numeroAOrdinal($p->despacho) . " DESPACHO" }}</td>
                        <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->fechahora_registro }}</td>
                        <!--<td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>-->
                    </tr>
                @endforeach
            </tbody>
        </table>

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
  $(document).ready(function() {
    $('#tablacarpetassgf').DataTable({
  "columnDefs": [
    { "orderable": false, "targets": [4] }  // Evitar orden en columnas de acción si no es necesario
  ],
      "pageLength": 10,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": true,  // Habilitar búsqueda
      "ordering": false,   // Habilitar ordenación
      "info": true,       // Mostrar información de la tabla
      "autoWidth": false,  // Ajustar automáticamente el ancho de las columnas
      "lengthChange": false,
      "language": {
            "search": "Buscar",                         // Cambia "Search" por "Buscar"
            "lengthMenu": "Mostrar _MENU_ paquetes",    // Cambia "Show entries" por "Mostrar entradas"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ paquetes", // Cambia el texto de la información
            "zeroRecords": "No se encontraron registros", // Mensaje cuando no hay resultados
            "infoEmpty": "Mostrando 0 a 0 de 0 paquetes", // Cuando la tabla está vacía
            "infoFiltered": "(filtrado de _MAX_ paquetes totales)", // Cuando hay filtros activos
      
            // Personaliza "Previous" y "Next" en la paginación
            "paginate": {
              "previous": "Anterior",   // Cambia "Previous" por "Anterior"
              "next": "Siguiente"       // Cambia "Next" por "Siguiente"
            },
      
            // Personaliza el texto de "Showing entries"
            "emptyTable": "No hay datos disponibles en la tabla", // Mensaje si no hay datos
      }      
    });


    

  });
</script>
<script>

var element = document.getElementById('codbarras');
var maskOptions = {
  mask: '0000000000000000000000000'
};
var mask = IMask(element, maskOptions);

var iddependenciaElement = document.getElementById('iddependencia');
var iddependenciaMask = new IMask(iddependenciaElement, {
  mask: '00000000000'
});

var anoElement = document.getElementById('ano');
var anoMask = new IMask(anoElement, {
  mask: '0000'
});

var nroexpElement = document.getElementById('nroexp');
var nroexpMask = new IMask(nroexpElement, {
  mask: '000000'
});

var tipoElement = document.getElementById('tipo');
var tipoMask = new IMask(tipoElement, {
  mask: '0000'
});




document.getElementById("miFormulario").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
    }
});

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
        if (valor.length !== 25) {
            alert("El c\u00F3digo de barras " + valor + " no es v\u00E1lido. Solo tiene "+ valor.length +" caracteres.");
            return false;
        }
        const codbarras = valor;

        const idde = parseInt(valor.substring(0, 11)); 
        const anio = valor.substring(11, 15); 
        const expe = parseInt(valor.substring(15, 21)); 
        const tipo = parseInt(valor.substring(21, 25)); 
        let carpeta = idde + "-" + anio + "-" + expe + "-" + tipo;

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
                    document.getElementById('codigoverificar').style.display = 'none';
                    document.getElementById('datosgrabar').style.display = 'block';
                    document.getElementById('btngrabar').style.display = 'block';
                    document.getElementById('codcarpeta').innerHTML = "<b>Carpeta Fiscal: <span class='text-primary'>" + codbarras + "</span> - <span class='text-danger'>" + carpeta + "</span></b>";
                    document.getElementById('codbarrasgrabar').value = codbarras;
                    if (response.dependencia == "") {

                    } else {
                        const selectize = $('#dependencia')[0].selectize;
                        if (selectize.options[response.dependencia]) {
                            selectize.setValue(response.dependencia);
                        }
                        document.getElementById('dependencia').value = response.dependencia;
                        document.getElementById('despacho').value = response.despacho;
                    }

                } else {
                    document.getElementById("codbarras").value = "";
                    document.getElementById('codbarras').focus();   

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

    function buscapordetalle() {
        let idde = document.getElementById("iddependencia").value;
        let anio = document.getElementById("ano").value;
        let expe = document.getElementById("nroexp").value;
        let tipo = document.getElementById("tipo").value;
        let codbarras = idde.padStart(11, '0') + anio + expe.padStart(6, '0') + tipo.padStart(4, '0');

        idde = parseInt(idde); 
        expe = parseInt(expe); 
        tipo = parseInt(tipo); 
        let carpeta = idde + "-" + anio + "-" + expe + "-" + tipo;

        $.ajax({
            url: '{{ route("carpetassgf.buscacarpeta") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                codbarras: codbarras,
                idde: idde,
                anio: anio,
                expe: expe,
                tipo: tipo
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {
                    document.getElementById('codigoverificar').style.display = 'none';
                    document.getElementById('datosgrabar').style.display = 'block';
                    document.getElementById('btngrabar').style.display = 'flex';
                    document.getElementById('codcarpeta').innerHTML = "<b>Carpeta Fiscal: <span class='text-primary'>" + codbarras + "</span> - <span class='text-danger'>" + carpeta + "</span></b>";
                    document.getElementById('codbarrasgrabar').value = codbarras;
                    if (response.dependencia == "") {

                    } else {
                        const selectize = $('#dependencia')[0].selectize;
                        if (selectize.options[response.dependencia]) {
                            selectize.setValue(response.dependencia);
                        }
                        document.getElementById('dependencia').value = response.dependencia;
                        document.getElementById('despacho').value = response.despacho;
                    }
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