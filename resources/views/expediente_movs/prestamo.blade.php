@extends('menu.index')

@section('content')
<?php
function numeroAOrdinal($numero) {
    $ordinales = [0 => '',1 => '1er',2 => '2do',3 => '3er',4 => '4to',5 => '5to',6 => '6to',7 => '7mo',8 => '8vo',9 => '9no',10 => '10mo',11 => '11er',];
    return $ordinales[$numero] ?? $numero . 'º';
}
?>
    <!-- Mostrar el mensaje de �xito o error -->
    <form action="{{ route('prestamo.grabaprestamo') }}"
      method="POST" id="miFormulario2" enctype="multipart/form-data" autocomplete="off">

    @if(session('messageErr'))
        <div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease;"><b>{{ session('messageErr') }}</b></div>
    @else
        <div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    
    @endif
    @if(session('messageOK'))
        <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('messageOK') }}</b></div>
    @else
        <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
    @endif    
        @csrf
            <div class="row" id="datascan">            

            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">
                        Préstamo de Carpetas
                    </div>
                  </div>
                  <div class="card-body">

                    <div class="row">
                      <div class="col-5 col-md-4 col-lg-4" >
                        <div class="form-group" style="padding:5px;">
                          <label for="oficio"><b>Oficio solicitante:</b></label>
                          <input type="text" class="form-control" name="oficio" id="oficio" maxlength="40" autofocus/>
                        </div>
                      </div>
                      <div class="col-7 col-md-8 col-lg-8" >
<div class="mb-0" style="padding:5px;">
    <label class="form-label">
        <i class="fa fa-paperclip"></i> <b>Adjuntar pdf (opcional)</b>
    </label>
    <br>
    <input type="file" id="archivo" name="archivo" class="d-none" accept=".pdf">
    <button type="button" class="btn btn-outline-primary btn-sm"
            onclick="document.getElementById('archivo').click();">
        <i class="fa fa-upload"></i> Seleccionar archivo
    </button>
    <span id="nombreArchivo" class="ms-2 text-success"></span>
</div>

<script>
document.getElementById('archivo').addEventListener('change', function () {
    document.getElementById('nombreArchivo').innerHTML =
        this.files.length
            ? `<i class="fa fa-file"></i> ${this.files[0].name}`
            : '';
});
</script>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 col-lg-6" >
                        <div class="form-group" style="padding:5px;">
                          <label for="fiscal"><b>Fiscal:</b></label>
                            <select name="fiscal" id="fiscal" class="" data-live-search="true">
                                <option value=""></option>
                                @foreach ($personal as $fiscal)
                                <option value="{{ $fiscal->id_personal }}" 
                                data-id_dependencia="{{ $fiscal->id_dependencia }}"
                                data-descripcion="{{ $fiscal->descripcion }}"
                                data-despacho="{{ $fiscal->despacho }}"                                
                                {{ old('id_personal', $regcab->fiscal ?? null) == $fiscal->id_personal ? 'selected' : '' }}>
                                    {{ $fiscal->apellido_paterno }} {{ $fiscal->apellido_materno }} {{ $fiscal->nombres }}
                                </option>			    
                                @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6" >
                        <div class="form-group" style="padding:5px;">
                            <div id="dependencia"></div>
                            <div id="despacho"></div>                            
                            <!--<b>DEPENDENCIA : </b> {{ optional($fiscal)->descripcion }}<br>
                            <b>DESPACHO : </b> {{ numeroAOrdinal(Auth::user()->personal->despacho) }} DESPACHO-->
                        </div>
                      </div>
                    </div>



                    <div class="row mb-3" >
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group " style="padding:5px;">
                        <p class="text-muted small mb-1"><b>Oficio a Generar</b></p>
        <div class="row">
        <div class="col-3 col-md-3 col-lg-3">
          <!--<label for="nroexp" class="form-label"><b>Nro Expediente</b></label>-->
          <input type="text" id="nrooficio" name="nrooficio" class="form-control text-center" maxlength="6" placeholder="Número" style="width:100px;" required>
        </div>
        <div class="col-2 col-md-2 col-lg-2">
          <!--<label for="ano" class="form-label"><b>A&ntilde;o</b></label>-->
          <input type="text" id="anooficio" name="anooficio" class="form-control text-center" maxlength="4" placeholder="A&ntilde;o" style="width:70px;" value="{{ now()->year }}">
        </div>
        </div>
                        </div>
                      </div>

                      <div class="col-md-6 col-lg-6">
          <label for="nroexp" class="form-label"><b>Observación</b></label>
          <textarea id="observaciones" name="observaciones" rows="3" cols="50" class="form-control" ></textarea>
                      </div>


                    </div>








                    <div class="row" >
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group border p-3 rounded shadow-sm bg-light">
                            <h5 class="text-primary"><i class="fas fa-barcode"></i> Buscar por Código de Carpeta Fiscal</h5>
                            <p class="text-muted small"><b>Escanee con lector o escriba el código completo, luego presione Enter o el botón.</b></p>

                          <!--<label for="codbarras"><b>C&oacute;digo de Carpeta Fiscal</b></label>-->
                          
                          <div class="input-group">

                          <input type="text" class="form-control" name="codbarras" id="codbarras" placeholder="C&oacute;d. Carpeta Fiscal" onkeydown="verificarEnter(event)" autofocus/>
                          <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="limpiarCodigoBarra()">
                          <i class="fas fa-check me-1"></i> Buscar Carpeta Fiscal
                          </button>

                          </div>
                          <small id="msgerr" class="form-text text-muted text-danger" style="display:none;">Escanee con lector o digite el codigo y presione enter.</small>

                        </div>
                      </div>



                      <div class="col-md-6 col-lg-6">
                        <div class="form-group border p-3 rounded shadow-sm bg-light">
                        <h5 class="text-primary"><i class="fas fa-folder-open"></i> Buscar por Año y Nº de Expediente</h5>
                        <p class="text-muted small"><b>Complete ambos campos y presione el botón para buscar.</b></p>

                          <div class="card-title mt-0 text-danger text-center" id="cantexp" style="display:none;"></div>

        <div class="row">
        <div class="col-md-2">
          <!--<label for="ano" class="form-label"><b>A&ntilde;o</b></label>-->
          <input type="text" id="ano" name="ano" class="form-control text-center" maxlength="4" placeholder="A&ntilde;o" style="width:70px;">
        </div>
        <div class="col-md-3">
          <!--<label for="nroexp" class="form-label"><b>Nro Expediente</b></label>-->
          <input type="text" id="nroexp" name="nroexp" class="form-control text-center" maxlength="6" placeholder="Expediente" style="width:100px;">
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <a href="#" onclick="buscaporanoexpediente(event)" class="btn btn-primary w-100">Buscar Carpeta por A&ntilde;o y Expediente</a>
        </div>
        </div>
                          <small id="msgerr2" class="form-text text-muted text-danger" style="display:none;">Escanee con lector o digite el codigo y presione enter.</small>
                          

                        </div>
                      </div>
                    </div>
                  </div>

	          <div class="container mt-0">

            <span class="d-none d-md-inline">
            <table id="scanned-list" class="table table-striped table-sm">
                <thead class="thead-dark">
                  <tr>
                  <!--<th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">C&oacute;digo de Barras</th>-->			      
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Nro Expediente</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Imputado</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Agraviado</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Delito</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Folios</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Eliminar</th>
                  </tr>
                </thead>
                <tbody style="font-size:12px;" >
                <!-- Los datos escaneados se ir�n a�adiendo aqu� -->
                </tbody>
            </table>
            </span>

                  </div>
        
                  <div class="card-action">
        	    <button type="button" class="btn btn-primary" onclick="prepararYMostrarModal()">
                        Prestar Carpetas
                </button>
                <a href="{{ route('prestamo') }}" class="btn btn-secondary">Regresar al Listado de Prestamos</a>

            </div>
                </div>
              </div>
            </div>
            
<!--    </form>
    <form action="{{ route('prestamo.grabaprestamo') }}"
      method="POST" id="miFormulario2" autocomplete="off">
    @csrf  
-->
    <input type="hidden" id="scannedItemsInput" name="scannedItems">
	          <input type="hidden" id="codfiscal" name="codfiscal">
	          <input type="hidden" id="coddependencia" name="coddependencia">
	          <input type="hidden" id="coddespacho" name="coddespacho">

<!-- Modal -->
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR PRESTAMO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Las carpetas seleccionadas serán prestadas, confirma y continua ?
      </div>      
      <div class="modal-footer">
        <button type="button" id="grabarBtn" class="btn btn-primary">Continuar y Grabar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
</div>

<div class="modal fade" id="textoModal2" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div id="textomodalelimina" class="modal-body">
      </div>
      <input type="hidden" id="elementoindex" name="elementoindex">
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="eliminarItem(event)">Eliminar de la Lista</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="textoModal3" tabindex="-1" aria-labelledby="textoModal3Label" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="textoModal3Label">Selecciona la Carpeta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div id="textomodalelimina" class="modal-body">
        <table id="resultados" class="table" >        
            <thead class="table-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Carpeta Fiscal</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Nro Expediente</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Imputado</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Agraviado</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Delito</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Folios</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Seleccionar</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

    </form>




<style>
    .selectize-dropdown, .selectize-input, .selectize-input input {
        font-size: 11px!important;  /* O cualquier valor que desees */
    }
    .selectize-input {
        padding: 4px 4px!important;  
    }    
</style>
@endsection

@section('scripts')
<script>
    var selectizeFiscal = $('#fiscal').selectize({
        onChange: function(value) {

            if (!value) {
                $('#dependencia').text('');
                $('#despacho').text('');
                $('#coddependencia').val('');
                $('#coddespacho').val('');
                return;
            }

            var item = this.options[value];

            $('#dependencia').html('<b>DEPENDENCIA:</b> ' + (item.descripcion || ''));
            $('#despacho').html('<b>DESPACHO:</b> ' + numeroAOrdinal(item.despacho) + ' Despacho');
            $('#coddependencia').val( (item.id_dependencia || ''));
            $('#coddespacho').val( (item.despacho || ''));
        }
    })[0].selectize;    

    function numeroAOrdinal(numero) {
        const ordinales = {
            0: ' ',
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

        return ordinales[numero] || numero ;
    }
</script>



<script>
document.getElementById("miFormulario2").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
       // event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
    }
});
    
let scannedItems = []; // Array para almacenar los c�digos escaneados
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
//        if (valor.length !== 25) {
//            alert("El c\u00F3digo de barras " + valor + " no es v\u00E1lido. Solo tiene "+ valor.length +" caracteres.");
//            return false;
//        }
	
	const codbarras = valor;
	const dependencia = parseInt(valor.substring(0, 11)); 
	const ano = valor.substring(11, 15); 
	const nroexpediente = parseInt(valor.substring(15, 21)); 
	const tipo = parseInt(valor.substring(21, 25)); 


            const yaExiste = scannedItems.some(item => item.codbarras === codbarras);
            if (yaExiste) {
              alert("El c\u00F3digo de barras " + valor + " ya se encuentra en la lista.");
              document.getElementById("codbarras").value='';
              document.getElementById('codbarras').focus();        
              return false;
            }

//            var formData = $('#miFormulario').serialize();
        $.ajax({
            url: '{{ route("solicitud.buscacarpeta") }}',
            method: 'POST',
//            data: formData,
            data: {
                _token: '{{ csrf_token() }}',
                codbarras: codbarras
            },     
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                let id_expediente = response.id_expediente;
                let imputado = response.imputado;
                let agraviado = response.agraviado;
                let desc_delito = response.desc_delito;
                let nro_folios = response.nro_folios;

                if (response.success) {
                    scannedItems.unshift({ codbarras, dependencia, ano, nroexpediente, tipo, id_expediente, imputado, agraviado, desc_delito, nro_folios});
                    updateScannedList();
                    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);
                } else {
                    $('#msgerr').html('<b>' + mensaje + '</b>');
                    msgerr.style.display = 'block';
                    setTimeout(function() {
                        msgerr.style.display = 'none';
                    }, 4000);                 }
            },
            error: function(xhr) {
                //$('#mensaje-guardar').html('<div style="color: red;">Error inesperado</div>');
                //console.error(xhr.responseText);
            }
        });

	document.getElementById("codbarras").value='';
	document.getElementById('codbarras').focus();        

    }


    function buscaporanoexpediente() {
        let ano = document.getElementById("ano").value;
        let nroexp = document.getElementById("nroexp").value;
	
        $.ajax({
            url: '{{ route("solicitud.buscacarpeta") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ano: ano,
                nroexp: nroexp
            },            
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                let id_expediente = response.id_expediente;
                let imputado = response.imputado;
                let agraviado = response.agraviado;
                let desc_delito = response.desc_delito;
                let nro_folios = response.nro_folios;

                if (response.success) {

                    var registros = response.registros;

                    let $tbody = $('#resultados tbody');
                    $tbody.empty();

                    registros.forEach(function (registro) {
                        $tbody.append(`
                            <tr>
                                <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">${registro.codbarras}</td>
                                <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">${registro.id_dependencia}-${registro.ano_expediente}-${registro.nro_expediente}-${registro.id_tipo}</td>
                                <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">${registro.imputado || ''}</td>
                                <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">${registro.agraviado || ''}</td>
                                <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">${registro.desc_delito || ''}</td>
                                <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">${registro.nro_folios || ''}</td>
                                <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">
                                <button type="button" class="btn btn-primary btn-sm seleccionar-registro" 
                                data-codbarras="${registro.codbarras}" 
                                data-dependencia="${registro.id_dependencia}" 
                                data-ano="${registro.ano_expediente}"
                                data-nroexpediente="${registro.nro_expediente}"
                                data-tipo="${registro.id_tipo}"
                                data-id_expediente="${registro.id_expediente}"
                                data-imputado="${registro.imputado}"
                                data-agraviado="${registro.agraviado}"
                                data-desc_delito="${registro.desc_delito}"
                                data-nro_folios="${registro.nro_folios}"
                                >Seleccionar</button></td>
                            </tr>
                        `);
                    });

      let myModal3 = new bootstrap.Modal(document.getElementById('textoModal3'));
      myModal3.show();

//                    scannedItems.unshift({ codbarras, dependencia, ano, nroexpediente, tipo, id_expediente, imputado, agraviado, desc_delito, nro_folios});
//                    updateScannedList();
//                    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);
                } else {
                    $('#msgerr2').html('<b>' + mensaje + '</b>');
                    msgerr2.style.display = 'block';
                    setTimeout(function() {
                        msgerr2.style.display = 'none';
                    }, 4000);                 }
            },
            error: function(xhr) {
                //$('#mensaje-guardar').html('<div style="color: red;">Error inesperado</div>');
                //console.error(xhr.responseText);
            }
        });

	document.getElementById("ano").value='';
	document.getElementById("nroexp").value='';
//	document.getElementById('codbarras').focus();        

    }
$(document).on('click', '.seleccionar-registro', function () {
    let codbarras = $(this).data('codbarras');
    let dependencia = $(this).data('dependencia');
    let ano = $(this).data('ano');
    let nroexpediente = $(this).data('nroexpediente');
    let tipo = $(this).data('tipo');
    let id_expediente = $(this).data('id_expediente');
    let imputado = $(this).data('imputado');
    let agraviado = $(this).data('agraviado');
    let desc_delito = $(this).data('desc_delito');
    let nro_folios = $(this).data('nro_folios');

    const yaExiste = scannedItems.some(item => item.codbarras === codbarras);
    if (yaExiste) {
      alert("El c\u00F3digo de barras " + codbarras + " ya se encuentra en la lista.");     
      return false;
    }    

    scannedItems.unshift({ codbarras, dependencia, ano, nroexpediente, tipo, id_expediente, imputado, agraviado, desc_delito, nro_folios});
    updateScannedList();
    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);

    let myModal3 = bootstrap.Modal.getInstance(document.getElementById('textoModal3'));
    myModal3.hide();

    // Opcional: limpiar resultados
    $('#resultados tbody').empty();
});





function updateScannedList() {
    const tableBody = $('#scanned-list tbody');
    const tableBodycel = $('#scanned-listcel tbody');
    tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla
    tableBodycel.empty(); // Limpiar la tabla antes de volver a renderizarla
    var cantexp=0;
    scannedItems.forEach((item, index) => {
	cantexp=cantexp+1;
	tableBody.append(`
	    <tr>
		<!--<td style="font-size:12px; padding: 5px 10px !important;">${item.codbarras}</td>-->
		<td style="font-size:12px; padding: 5px 10px !important;" class="fw-bold">${item.dependencia}-${item.ano}-${item.nroexpediente}-${item.tipo}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">${item.imputado || ''}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">${item.agraviado || ''}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">${item.desc_delito || ''}</td>                        
		<td style="font-size:12px; padding: 5px 10px !important;">${item.nro_folios || ''}</td>                        
		<td style="font-size:12px; padding: 5px 10px !important;">
		    <button onclick="prepararYMostrarModal2(${index},event)" style="border: none; background: transparent; cursor: pointer;">
		    <i class="fas fa-trash-alt fa-lg" style="color: red;"></i>
		    </button>
		</td>
	    </tr>
	`);

    });

    if (cantexp==0) {
	document.getElementById('cantexp').innerHTML = '';
    } else {
	document.getElementById('cantexp').innerHTML = cantexp + ' Registro(s)';
    }
}    
    
function prepararYMostrarModal() {
  if (event) event.preventDefault(); // Previene recarga
  if (document.getElementById('oficio').value=="") {
    alert("INGRESE OFICIO DEL SOLICITANTE");
    document.getElementById('oficio').focus();
    return false;
  }
  if (document.getElementById('fiscal').value=="") {
    alert("SELECCIONE FISCAL");
    document.getElementById('fiscal').focus();
    return false;
  }
  if (document.getElementById('nrooficio').value=="") {
    alert("INGRESE NUMERO DEL OFICIO A GENERAR");
    document.getElementById('nrooficio').focus();
    return false;
  }
  if (document.getElementById('anooficio').value=="") {
    alert("INGRESE AÑO DEL OFICIO A GENERAR");
    document.getElementById('anooficio').focus();
    return false;
  }
  document.getElementById('codfiscal').value=document.getElementById('fiscal').value;
  if (scannedItems.length > 0) {
      const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
      myModal.show();
  } else {
      document.getElementById('messageErr').innerHTML = '<b>No puedes generar solicitud, ingrese carpetas fiscales</b>';
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
}

document.getElementById("grabarBtn").addEventListener("click", function(event) {
    if (event) event.preventDefault(); // Previene recarga
    if (scannedItems.length > 0) {
        event.preventDefault();  // Prevenir el comportamiento por defecto
        document.getElementById("miFormulario2").submit();
    } else {
        document.getElementById('messageErr').innerHTML = '<b>No puedes realizar préstamo, ingrese carpetas fiscales</b>';
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

});
            

function prepararYMostrarModal2(index,event) {
    if (event) event.preventDefault(); // Previene recarga
    const item = scannedItems[index];
    const codbar = item.codbarras;  
    document.getElementById('textomodalelimina').innerHTML="SE VA A ELIMINAR DE LA LISTA EL CODIGO: " + codbar + "<br>DESEA CONTINUAR?";
    document.getElementById('elementoindex').value=index;
    const myModal2 = new bootstrap.Modal(document.getElementById('textoModal2'));
    myModal2.show();
}
function eliminarItem(event) {
    if (event) event.preventDefault(); // Previene recarga
    const myModal2 = bootstrap.Modal.getInstance(document.getElementById('textoModal2'));
    if (myModal2) {
        myModal2.hide();
    }
    var index=document.getElementById('elementoindex').value;
    scannedItems.splice(index, 1);
    updateScannedList();
    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);
}


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


    const detalles = @json($regdet ?? []);
    detalles.forEach(function(registro) {
        var codbarras = registro.codbarras;
        var dependencia = registro.id_dependencia;
        var ano = registro.ano_expediente;
        var nroexpediente = registro.nro_expediente;
        var tipo = registro.id_tipo;
        var id_expediente = registro.id_expediente;
        var imputado = registro.imputado;
        var agraviado = registro.agraviado;
        var desc_delito = registro.desc_delito;
        var nro_folios = registro.nro_folios;

        scannedItems.unshift({ codbarras, dependencia, ano, nroexpediente, tipo, id_expediente, imputado, agraviado, desc_delito, nro_folios});
    });
    updateScannedList();
    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);

</script>
@endsection
