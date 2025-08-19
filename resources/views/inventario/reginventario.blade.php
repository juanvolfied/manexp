@extends('menu.index')

@section('content')
    <!-- Mostrar el mensaje de �xito o error -->
    <form id="miFormulario" autocomplete="off">
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

            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">Inventario de Expedientes</div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group" style="padding:5px;">
                          <label for="nroinventario"><b>Nro Inventario:</b></label>
                          <div class="input-group">
                          <input type="text" class="form-control form-control-sm" name="nroinventario" id="nroinventario" onkeydown="buscanroinventa(event)" autofocus/>
                          
                          <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="ejecutabuscar()">
                          <i class="fas fa-check me-1"></i> Verificar
                          </button>
                          
                          </div>
                          
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6 col-md-3 col-lg-3" id="verocultar1" style="display:none;">
                        <div class="form-group" style="padding:5px;">
                          <label for="archivo"><b>Archivo:</b></label>
			  <select name="archivo" id="archivo" class="form-select form-control-sm" >
			    <option value=""></option>
			    <option value="1">Archivo 001</option>
			    <option value="2">Archivo 002</option>
			    <option value="3">Archivo 003</option>
			    <option value="4">Archivo 004</option>
			    <option value="5">Archivo 005</option>
			    <option value="6">Archivo 006</option>
			    <option value="7">Archivo 007</option>
			    <option value="8">Archivo 008</option>
			    <option value="9">Archivo 009</option>
			    <option value="10">Archivo 010</option>
			    <option value="11">Archivo 011</option>
			    <option value="12">Archivo 012</option>
			  </select>
                        </div>
                      </div>
                      <div class="col-6 col-md-3 col-lg-3" id="verocultar2" style="display:none;">
                        <div class="form-group" style="padding:5px;">
                          <label for="nropaquete"><b>Nro Paquete:</b></label>
                          <input type="text" class="form-control form-control-sm" name="nropaquete" id="nropaquete" maxlength="5" style="width: 100px;"/>
                        </div>
                      </div>
                      <div class="col-4 col-md-2 col-lg-2" id="verocultar6" style="display:none;">
                        <div class="form-group" style="padding:5px;">
                          <label for="serie"><b>Serie:</b></label>
                          <input type="text" class="form-control form-control-sm" name="serie" id="serie" maxlength="1" style="width: 100px;" />
                        </div>
                      </div>
                      <div class="col-8 col-md-3 col-lg-3" id="verocultar3" style="display:none;">
                        <div class="form-group" style="padding:5px;">
                          <label for="anaquel"><b>Anaquel:</b></label>
                          <input type="text" class="form-control form-control-sm" name="anaquel" id="anaquel" maxlength="10" style="width: 200px;" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6" id="verocultar4" style="display:none;">
                        <div class="form-group" style="padding:5px;">
                          <label for="dependencia"><b>Dependencia:</b></label>
			  <!--<select name="dependencia" id="dependencia" class="form-select form-control-sm" >-->
			  <select name="dependencia" id="dependencia" class="" data-live-search="true">
			    <option value=""></option>

                            @foreach ($dependencias as $datos)
			    <option value="{{ $datos->id_dependencia }}">{{ $datos->descripcion }}</option>			    
			    @endforeach
			  </select>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6" id="verocultar5" style="display:none;">
                        <div class="form-group" style="padding:5px;">
                          <label for="despacho"><b>Despacho:</b></label>
			  <select name="despacho" id="despacho" class="form-select form-control-sm" >
			    <option value=""></option>
			    <option value="0">DESPACHO</option>
			    <option value="1">1er. DESPACHO</option>
			    <option value="2">2do. DESPACHO</option>
			    <option value="3">3er. DESPACHO</option>
			    <option value="4">4to. DESPACHO</option>
			    <option value="5">5to. DESPACHO</option>
			    <option value="6">6to. DESPACHO</option>
			    <option value="7">7mo. DESPACHO</option>
			    <option value="8">8vo. DESPACHO</option>
			    <option value="9">9no. DESPACHO</option>
			    <option value="10">10mo. DESPACHO</option>
			    <option value="11">11er. DESPACHO</option>
			  </select>
                        </div>
                      </div>
                    </div>
                  </div>        
                  <div class="card-action text-center">
                    <!--<button class="btn btn-success">Grabar</button>
                    <button class="btn btn-danger">Cancel</button>-->
        	    <button id="IniciaScan" class="btn btn-primary"><b>Iniciar escaneo de c&oacute;digos</b></button>
                    
                  </div>
                </div>
              </div>
            </div>
            
            
            



            <div class="row" id="datascan" style="display:none;">            
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Inventario de Expedientes</div>
                  </div>
                  <div class="card-body">

                    <span class="d-none d-md-inline">


                    <div class="row">
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td width="100px;"><b>Nro Inventario:</b></td><td id="datinve"></td></tr></table>
                          <!--<label><b>Nro Inventario:</b> <span id="datinve"></span></label>-->
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 col-lg-3">
                          <table width="100%"><tr><td width="100px;"><b>Archivo:</b></td><td id="datarch"></td></tr></table>
                      </div>
                      <div class="col-md-3 col-lg-3">
                          <table width="100%"><tr><td width="100px;"><b>Nro Paquete:</b></td><td id="datpaqu"></td></tr></table>
                      </div>
                      <div class="col-md-3 col-lg-3">
                          <table width="100%"><tr><td width="100px;"><b>Serie:</b></td><td id="datseri"></td></tr></table>
                      </div>
                      <div class="col-md-3 col-lg-3">
                          <table width="100%"><tr><td width="100px;"><b>Anaquel:</b></td><td id="datanaq"></td></tr></table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td width="100px;"><b>Dependencia:</b></td><td id="datdepe" style="text-align: justify;"></td></tr></table>
                      </div>
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td width="100px;"><b>Despacho:</b></td><td id="datdesp"></td></tr></table>
                      </div>
                    </div>


		                </span>
                    <span class="d-inline d-md-none">

                    <div class="row">
                      <div class="col-12 col-md-12 col-lg-12">
                          <table width="100%"><tr><td><b>Nro Inventario: </b><span id="datinve2"></span></td>
                          <td ><span id="datarch2"></span></td></tr></table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-12 col-lg-12">
                          <table width="100%"><tr><td><b>Paquete: </b><span id="datpaqu2"></span></td>
                          <td><b>Serie: </b><span id="datseri2"></span></td>
                          <td ><b>Anaquel: </b><span id="datanaq2"></span></td></tr></table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td ><b>Dependencia: </b><span id="datdepe2" style="text-align: justify;"></span></td></tr></table>
                      </div>
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td ><b>Despacho: </b><span id="datdesp2"></span></td></tr></table>
                      </div>
                    </div>

		                </span>



                    <div class="row" style="background-color:#F2F5A9;">
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group">
                          <label for="codbarras"><b>C&oacute;digo de Barras (25 caracteres)</b></label>
                          <input type="text" class="form-control" name="codbarras" id="codbarras" placeholder="c&oacute;d. barras" onkeydown="verificarEnter(event)" autofocus/>
                          <small id="msgerr" class="form-text text-muted text-danger" style="display:none;">Escanee con lector o digite el codigo y presione enter.</small>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group">
                          <div class="card-title mt-0 text-danger text-center" id="cantexp"></div>
                        </div>
                      </div>
                    </div>
                  </div>

	          <div class="container mt-0">
                      <span class="d-none d-md-inline">
	              <table id="scanned-list" class="table table-striped table-sm">
		          <thead class="thead-dark">
			      <tr>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">C&oacute;digo de Barras</th>			      
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Dependencia</th>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">A&ntilde;o</th>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Nro Exp</th>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tipo</th>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fecha</th>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tomo</th>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Eliminar</th>
		              </tr>
		          </thead>
		          <tbody style="font-size:12px;" >
		        	<!-- Los datos escaneados se ir�n a�adiendo aqu� -->
		          </tbody>
		      </table>
		      </span>

                      <span class="d-inline d-md-none">
	              <table id="scanned-listcel" class="table table-striped table-sm">
		          <thead class="thead-dark">
			      <tr>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">C&oacute;digo de Barras</th>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fecha y hora</th>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">-</th>
		              </tr>
		          </thead>
		          <tbody>
		        	<!-- Los datos escaneados se ir�n a�adiendo aqu� -->
		          </tbody>
		      </table>
		      </span>

                  </div>
	          <!--<input type="hidden" id="scannedItemsInput" name="scannedItems">-->

        
                  <div class="card-action">
                    <!--<button class="btn btn-success">Grabar</button>
                    <button class="btn btn-danger">Cancel</button>-->
        	    <!--<button id="grabarBtn" class="btn btn-primary">Inventariar c&oacute;digos escaneados</button>-->
        	    <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#textoModal">Inventariar c&oacute;digos escaneados</button>-->
        	    <button type="button" class="btn btn-primary" onclick="prepararYMostrarModal()">Inventariar c&oacute;digos escaneados</button>
                  </div>
                </div>
              </div>
            </div>
            

            <input type="hidden" id="tomo" name="tomo" value=0>
            <input type="hidden" id="codbartmp" name="codbartmp">


    </form>
    <form action="{{ route('expediente.inventa') }}" method="POST" id="miFormulario2" autocomplete="off">
    @csrf  <!-- Este campo incluir� el token CSRF autom�ticamente -->
	          <input type="hidden" id="scannedItemsInput" name="scannedItems">
	          <input type="hidden" id="nroinventarioobs" name="nroinventarioobs">

<!-- Modal -->
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR GRABACION DE INVENTARIO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        <label for="observacion"><b>Puede ingresar una OBSERVACI&Oacute;N (Opcional) de hasta 100 caracteres</b></label>
        <input type="text" name="observacion" id="observacion" class="form-control" maxlength="100" >
      </div>
      
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" onclick="guardarTexto()">Continuar y Grabar Inventario</button>-->
        <button type="button" id="grabarBtn" class="btn btn-primary">Continuar y Grabar Inventario</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalportomos" tabindex="-1" aria-labelledby="textoModaltomos" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModaltomos">CARPETA FISCAL YA HA SIDO REGISTRADA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        LA CARPETA FISCAL YA HA SIDO REGISTRADA, SI CORRESPONDE A UN NUEVO TOMO, DIGITE EL NUMERO DE TOMO Y PRESIONE CONTINUAR<br><br>
        <label for="tomo_ing" style="display: inline-block; margin-right: 10px;"><b>Ingrese TOMO</b></label>
        <input type="text" name="tomo_ing" id="tomo_ing" class="form-control" maxlength="2" size="2" style="width:50px; display: inline-block;">
      </div>
      
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" onclick="guardarTexto()">Continuar y Grabar Inventario</button>-->
        <button type="button" id="aceptatomo" class="btn btn-primary" onclick="lectoreatomo()">Aceptar y Continuar</button>
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
@section('scripts')
<script>
    $('#dependencia').selectize();
</script>
@endsection




<script>
  const abreviados = @json($dependencias->pluck('abreviado', 'id_dependencia'));


document.getElementById("miFormulario").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
    }
});
    
let scannedItems = []; // Array para almacenar los c�digos escaneados
let datoscab = []; // Array 
function verificarEnter(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter

        document.getElementById('codbartmp').value ="";
        document.getElementById('tomo').value =0;
        document.getElementById('tomo_ing').value ="";

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
	const dependencia = parseInt(valor.substring(0, 11)); 
	const ano = valor.substring(11, 15); 
	const nroexpediente = parseInt(valor.substring(15, 21)); 
	const tipo = parseInt(valor.substring(21, 25)); 
  const tomo = document.getElementById("tomo").value;
  document.getElementById('codbartmp').value =codbarras;

        var formData = $('#miFormulario').serialize();
        $.ajax({
            url: '{{ route("expediente.lectura") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {
                    const lafecha = response.fechalect;
                    const lahora = response.horalect;
                    const estado = 'L';
                    scannedItems.unshift({ codbarras, dependencia, ano, nroexpediente, tipo, estado, lafecha, lahora, tomo});
                    updateScannedList();
                    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);
                } else {
/*
    var modalElement = document.getElementById('modalportomos');
    var modal = new bootstrap.Modal(modalElement);
    modal.show();
    modalElement.addEventListener('shown.bs.modal', function () {
      document.getElementById('tomo_ing').focus();
    }, { once: true });
*/


                    $('#msgerr').html('<b>' + mensaje + '</b>');
                    msgerr.style.display = 'block';
                    setTimeout(function() {
                        msgerr.style.display = 'none';
                    }, 4000);                 


                }
            },
            error: function(xhr) {
                //$('#mensaje-guardar').html('<div style="color: red;">Error inesperado</div>');
                //console.error(xhr.responseText);
            }
        });

  document.getElementById("tomo").value=0;
	document.getElementById("tomo_ing").value='';

	document.getElementById("codbarras").value='';
	document.getElementById('codbarras').focus();        

    }

    function lectoreatomo() {
      var modalElement = document.getElementById('modalportomos');
      var modalInstance = bootstrap.Modal.getInstance(modalElement);
      if (modalInstance) {
        modalInstance.hide();
      }

      document.getElementById("codbarras").value = document.getElementById('codbartmp').value;
        document.getElementById("tomo").value = document.getElementById('tomo_ing').value;
        limpiarCodigoBarra();
    }







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
		<td style="font-size:12px; padding: 5px 10px !important;">${item.codbarras}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">${item.dependencia}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">${item.ano}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">${item.nroexpediente}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">${item.tipo}</td>                        
		<td style="font-size:12px; padding: 5px 10px !important;">${item.lafecha} ${item.lahora}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">${item.tomo}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">
		    <button onclick="eliminarItem(${index},event)" style="border: none; background: transparent; cursor: pointer;">
		    <i class="fas fa-trash-alt" style="color: red;"></i>
		    </button>
		</td>
	    </tr>
	`);

	tableBodycel.append(`
	    <tr>
		<td style="font-size:11px; padding: 5px 10px !important;">${item.codbarras}</td>
		<td style="font-size:11px; padding: 5px 10px !important;">${item.lafecha} ${item.lahora}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">
		    <button onclick="eliminarItem(${index},event)" style="border: none; background: transparent; cursor: pointer;">
		    <i class="fas fa-trash-alt" style="color: red;"></i>
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
    
document.getElementById("IniciaScan").addEventListener("click", function(event) {
    event.preventDefault();
    if ($.trim($('#nroinventario').val())==="") {
    	alert("Ingrese y verifique el Nro de Inventario");
    	return false;
    }
    if (document.getElementById("verocultar1").style.display == "none") {
    	alert("Verifique el Nro de Inventario");
    	return false;
    } 
    
    if ($.trim($('#archivo').val())==="") {
    	alert("Selecciona el Archivo");
    	return false;
    }
    if ($.trim($('#nropaquete').val())==="") {
    	alert("Ingresa el Nro de Paquete");
    	return false;
    }
    if ($.trim($('#anaquel').val())==="") {
    	alert("Ingresa el anaquel");
    	return false;
    }
    if ($.trim($('#dependencia').val())==="") {
    	alert("Selecciona la Dependencia");
    	return false;
    }
    if ($.trim($('#despacho').val())==="") {
    	alert("Selecciona el Despacho");
    	return false;
    }
   
    $('#datinve').text($('#nroinventario').val()); 
    $('#datarch').text($('#archivo option:selected').text());
    $('#datpaqu').text($('#nropaquete').val());
    $('#datseri').text($('#serie').val());
    $('#datanaq').text($('#anaquel').val());
    $('#datdepe').text($('#dependencia option:selected').text());
    $('#datdesp').text($('#despacho option:selected').text());

    $('#datinve2').text($('#nroinventario').val()); 
    $('#datarch2').text($('#archivo option:selected').text());
    $('#datpaqu2').text($('#nropaquete').val());
    $('#datseri2').text($('#serie').val());
    $('#datanaq2').text($('#anaquel').val());
//    $('#datdepe2').text($('#dependencia option:selected').text());
    $('#datdepe2').text(abreviados[$('#dependencia').val()]);
    $('#datdesp2').text($('#despacho option:selected').text());


    document.getElementById("datacabe").style.display = "none"; 
    document.getElementById("datascan").style.display = "block";
    document.getElementById("codbarras").value='';
    document.getElementById('codbarras').focus();        
});

function prepararYMostrarModal() {
  if (event) event.preventDefault(); // Previene recarga
  if (scannedItems.length > 0) {
      const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
      myModal.show();
  } else {
      document.getElementById('messageErr').innerHTML = '<b>No puedes inventariar si no hay registros lectoreados</b>';
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
        document.getElementById('messageErr').innerHTML = '<b>No puedes inventariar si no hay registros lectoreados</b>';
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
            

function buscanroinventa(event) {
    let codigo = document.getElementById("nroinventario").value;
codigo = codigo.replace(/^[^A-Za-z0-9-]+|[^A-Za-z0-9-]+$/g, '');
    codigo = codigo.trim();
    
    
    document.getElementById("nroinventarioobs").value=codigo;
    scannedItems = [];
    var nroreg=0;
	if (event.keyCode === 13) {

    if ($.trim($('#nroinventario').val())==="") {
    	alert("Ingrese y verifique el Nro de Inventario");
    	return false;
    }

	    $.ajax({
		url: '{{ route("inventario.buscar") }}',
		method: 'POST',
		data: {
		    _token: '{{ csrf_token() }}',
		    nroinventario: codigo
		},
		success: function(response) {
		    if (response.success) {

          if (response.estado=="I" || response.estado=="O") {
              updateScannedList();
              document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);

              document.getElementById('messageErr').innerHTML = '<b>' + response.message + '</b>';
                                var messageErr = document.getElementById('messageErr');
                                messageErr.style.opacity = '1';
                                messageErr.style.display = 'block';
                                setTimeout(function() {
                                    messageErr.style.opacity = '0';
                                    setTimeout(() => {
                                        messageErr.style.display = 'none';
                                    }, 500);
                                }, 3000); 

          } else {

            var registros = response.registros;
            registros.forEach(function(registro) {

            nroreg=nroreg+1;
            if (nroreg==1) {
                // Rellenar los otros inputs con los datos del producto
                $('#archivo').val(registro.archivo);
                $('#nropaquete').val(registro.nro_paquete);
                $('#serie').val(registro.serie);
                $('#anaquel').val(registro.anaquel);
                $('#dependencia').val(response.paq_dependencia);
                                  var $select = $("#dependencia").selectize();
                                  var selectize = $select[0].selectize;
                                  selectize.setValue(response.paq_dependencia); 
                $('#despacho').val(response.despacho);
                
                $('#datinve').text($('#nroinventario').val()); 
                $('#datarch').text($('#archivo option:selected').text());
                $('#datpaqu').text($('#nropaquete').val());
                $('#datseri').text($('#serie').val());
                $('#datanaq').text($('#anaquel').val());
                $('#datdepe').text($('#dependencia option:selected').text());
                $('#datdesp').text($('#despacho option:selected').text());

                $('#datinve2').text($('#nroinventario').val()); 
                $('#datarch2').text($('#archivo option:selected').text());
                $('#datpaqu2').text($('#nropaquete').val());
                $('#datseri2').text($('#serie').val());
                $('#datanaq2').text($('#anaquel').val());
                //$('#datdepe2').text($('#dependencia option:selected').text());
                $('#datdepe2').text(abreviados[response.paq_dependencia]);
                $('#datdesp2').text($('#despacho option:selected').text());


            }
            var codbarras = registro.codbarras;
            var dependencia = registro.id_dependencia;
            var ano = registro.ano_expediente;
            var nroexpediente = registro.nro_expediente;
            var tipo = registro.id_tipo;
            var estado = registro.estado;
            if (estado=="L") {
                var lafecha = registro.fecha_lectura;
                var lahora = registro.hora_lectura;
            }
            if (estado=="I") {
                var lafecha = registro.fecha_inventario;
                var lahora = registro.hora_inventario;
            }
            var tomo = registro.tomo;
            scannedItems.unshift({ codbarras, dependencia, ano, nroexpediente, tipo, estado, lafecha, lahora, tomo});

          });

          updateScannedList();
          document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);
          
          document.getElementById("datacabe").style.display = "none"; 
          document.getElementById("datascan").style.display = "block";

                            document.getElementById("codbarras").value='';
                            document.getElementById('codbarras').focus();        
          
          }

		    } else {
                        document.getElementById("verocultar1").style.display = "block";
                        document.getElementById("verocultar2").style.display = "block";
                        document.getElementById("verocultar3").style.display = "block";
                        document.getElementById("verocultar4").style.display = "block";
                        document.getElementById("verocultar5").style.display = "block";
                        document.getElementById("verocultar6").style.display = "block";
		    }
		},
		error: function(xhr, status, error) {
                    if (xhr.status === 419) {
                        // No autorizado - probablemente sesi�n expirada
                        alert('TU SESION HA EXPIRADO. SERAS REDIRIGIDO AL LOGIN.');
                        window.location.href = '{{ route("usuario.login") }}';
                    } else {
                        // Otro tipo de error
                        console.error('Error en la petici�n:', xhr.status);
                        alert('Hubo un error al buscar nro inventario.');
                    }
//		    alert('Hubo un error al buscar nro inventario.');
		}


	    });
	} else {
	    updateScannedList();
	    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);
	    
	    document.getElementById("verocultar1").style.display = "none";
	    document.getElementById("verocultar2").style.display = "none";
	    document.getElementById("verocultar3").style.display = "none";
	    document.getElementById("verocultar4").style.display = "none";
	    document.getElementById("verocultar5").style.display = "none";
	    document.getElementById("verocultar6").style.display = "none";
	}
}

function ejecutabuscar() {
    const input = document.getElementById("nroinventario");
    // Crear un nuevo evento tipo teclado
    const event = new KeyboardEvent("keydown", {
        key: "Enter",
        keyCode: 13,
        which: 13,
        bubbles: true
    });
    // Disparar el evento sobre el input
    input.dispatchEvent(event);
}    


function eliminarItem(index,event) {
    if (event) event.preventDefault(); // Previene recarga

    const item = scannedItems[index];
    const codbar = item.codbarras;
    const tomo = item.tomo;
    if (confirm(`\u00BF Est\u00E1s seguro de eliminar el elemento con c\u00F3digo de barras: ${codbar} ?`)) {
        $.ajax({
            url: '{{ route("elimina.item") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                codbarras: codbar,
                tomo: tomo
            },
            success: function(response) {
              if (response.success) {
                scannedItems.splice(index, 1);
                updateScannedList();
                document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);

                alert('Elemento eliminado correctamente.');
              } else {
                alert(response.error);
              }
            },
            error: function() {
                alert('Error al eliminar el elemento.');
            }
        });
    }
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
</script>
@endsection
