@extends('menu.index')

@section('content')
    <!-- Mostrar el mensaje de éxito o error -->
    <form id="miFormulario" autocomplete="off">
    @if(session('messageErr'))
        <div id="messageErr" class="alert alert-danger text-danger"><b>{{ session('messageErr') }}</b></div>
    @else
        <div id="messageErr" class="alert alert-danger text-danger" style="display:none;"></div>    
    @endif
    @if(session('messageOK'))
        <div id="messageOK" class="alert alert-success text-success"><b>{{ session('messageOK') }}</b></div>
    @else
        <div id="messageOK" class="alert alert-success text-success" style="display:none;"></div>
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
                          <input type="text" class="form-control form-control-sm" name="nroinventario" id="nroinventario" onkeydown="buscanroinventa(event)"/>
                          
                          <button class="btn btn-primary" style="padding:0px 1rem!important;" type="button" onclick="ejecutabuscar()">
                          <i class="fas fa-check me-1"></i> Verificar
                          </button>
                          
                          </div>
                          
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group" style="padding:5px;">
                          <div id="msginventarioreg" class="form-text text-muted text-danger text-center" style="font-size:16px;"></div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6" id="verocultar1" style="display:none;">
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
			  </select>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6" id="verocultar2" style="display:none;">
                        <div class="form-group" style="padding:5px;">
                          <label for="nropaquete"><b>Nro Paquete:</b></label>
                          <input type="text" class="form-control form-control-sm" name="nropaquete" id="nropaquete" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6" id="verocultar3" style="display:none;">
                        <div class="form-group" style="padding:5px;">
                          <label for="dependencia"><b>Dependencia:</b></label>
			  <!--<select name="dependencia" id="dependencia" class="form-select form-control-sm" >-->
			  <select name="dependencia" id="dependencia" class="selectpicker form-control form-control-sm" data-live-search="true">
			    <option value=""></option>

                            @foreach ($dependencias as $datos)
			    <option value="{{ $datos->id_dependencia }}">{{ $datos->descripcion }}</option>			    
			    @endforeach
			  </select>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6" id="verocultar4" style="display:none;">
                        <div class="form-group" style="padding:5px;">
                          <label for="despacho"><b>Despacho:</b></label>
			  <select name="despacho" id="despacho" class="form-select form-control-sm" >
			    <option value=""></option>
			    <option value="1">1er. Despacho</option>
			    <option value="2">2do. Despacho</option>
			    <option value="3">3er. Despacho</option>
			    <option value="4">4to. Despacho</option>
			    <option value="5">5to. Despacho</option>
			    <option value="6">6to. Despacho</option>
			    <option value="7">7mo. Despacho</option>
			    <option value="8">8vo. Despacho</option>
			    <option value="9">9no. Despacho</option>
			    <option value="10">10mo. Despacho</option>
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
                    <div class="row">
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td width="100px;"><b>Nro Inventario:</b></td><td id="datinve"></td></tr></table>
                          <!--<label><b>Nro Inventario:</b> <span id="datinve"></span></label>-->
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td width="100px;"><b>Archivo:</b></td><td id="datarch"></td></tr></table>
                          <!--<label><b>Archivo:</b> <span id="datarch"></span></label>-->
                      </div>
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td width="100px;"><b>Nro Paquete:</b></td><td id="datpaqu"></td></tr></table>
                          <!--<label><b>Nro Paquete:</b> <span id="datpaqu"></span></label>-->
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td width="100px;"><b>Dependencia:</b></td><td id="datdepe" style="text-align: justify;"></td></tr></table>
                          <!--<label><b>Dependencia:</b> <span id="datdepe"></span></label>-->
                      </div>
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td width="100px;"><b>Despacho:</b></td><td id="datdesp"></td></tr></table>
                          <!--<label><b>Despacho:</b> <span id="datdesp"></span></label>-->
                      </div>
                    </div>

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
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Hora</th>
			          <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Eliminar</th>
		              </tr>
		          </thead>
		          <tbody style="font-size:12px;" >
		        	<!-- Los datos escaneados se irán añadiendo aquí -->
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
		        	<!-- Los datos escaneados se irán añadiendo aquí -->
		          </tbody>
		      </table>
		      </span>

                  </div>
	          <!--<input type="hidden" id="scannedItemsInput" name="scannedItems">-->

        
                  <div class="card-action">
                    <!--<button class="btn btn-success">Grabar</button>
                    <button class="btn btn-danger">Cancel</button>-->
        	    <button id="grabarBtn" class="btn btn-primary">Inventariar c&oacute;digos escaneados</button>
                    
                  </div>
                </div>
              </div>
            </div>
            
            
            
    </form>
    <form action="{{ route('expediente.inventa') }}" method="POST" id="miFormulario2" autocomplete="off">
    @csrf  <!-- Este campo incluirá el token CSRF automáticamente -->
	          <input type="hidden" id="scannedItemsInput" name="scannedItems">
    </form>


    
    <style>
    .choices .choices__inner {
    padding:0px !important;
    min-height:0px !important;
    font-size:12px !important;
    }
    .choices__input {
    font-size:12px !important;
    }
    .choices__item {
    font-size:12px !important;
    }
    .choices__item--choice {
    padding:5px !important;
    }
    </style>
    
<script>
  const element = document.getElementById('dependencia');
  const choices = new Choices(element, {
    searchEnabled: true,  // Habilitar la búsqueda
    itemSelectText: 'Presiona para seleccionar',
    noResultsText: 'Sin resultados',
    placeholderValue: 'Selecciona...',
    searchFloor: 1, // Número mínimo de caracteres para activar la búsqueda
    searchResultLimit: 5, // Limitar los resultados a 5
    // Aquí habilitamos el filtrado por subcadena
    searchFilter: function (query, item) {
      // Filtramos si el query (lo que se escribe) está dentro del texto de la opción
      //return item.label.toLowerCase().includes(query.toLowerCase());
      return item.label.toLowerCase() === query.toLowerCase();
    }
  });
</script>   


   
<script>
document.getElementById("miFormulario").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se envíe cuando se presiona Enter
    }
});
    
let scannedItems = []; // Array para almacenar los códigos escaneados
let datoscab = []; // Array 
function verificarEnter(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se envíe cuando se presiona Enter
        limpiarCodigoBarra();
    }
}

    function limpiarCodigoBarra() {
        let valor = document.getElementById("codbarras").value;
        valor = valor.replace(/^[^0-9]+|[^0-9]+$/g, '');  // Remueve caracteres no alfanuméricos del inicio y final

        valor = valor.trim();
        document.getElementById("codbarras").value = valor;
        if (valor.length !== 25) {
            alert("El c\u00F3digo de barras " + valor + " no es v\u00E1lido. Solo tiene "+ valor.length +" caracteres.");
            return false;
        }
	
	const codbarras = valor;
	const dependencia = valor.substring(0, 11); // Primeras 6 posiciones
	const ano = valor.substring(11, 15); // Las siguientes 4 posiciones
	const nroexpediente = valor.substring(15, 21); // Últimas 5 posiciones
	const tipo = valor.substring(21, 25); // Últimas 5 posiciones

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
                    scannedItems.push({ codbarras, dependencia, ano, nroexpediente, tipo, estado, lafecha, lahora});
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
		<td style="font-size:12px; padding: 5px 10px !important;">${item.lafecha}</td>
		<td style="font-size:12px; padding: 5px 10px !important;">${item.lahora}</td>
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
    $('#datdepe').text($('#dependencia option:selected').text());
    $('#datdesp').text($('#despacho option:selected').text());
    document.getElementById("datacabe").style.display = "none"; 
    document.getElementById("datascan").style.display = "block";
    document.getElementById("codbarras").value='';
    document.getElementById('codbarras').focus();        
});

document.getElementById("grabarBtn").addEventListener("click", function(event) {
    event.preventDefault();  // Prevenir el comportamiento por defecto
    document.getElementById("miFormulario2").submit();
});
            

function buscanroinventa(event) {
    let codigo = document.getElementById("nroinventario").value;
    codigo = codigo.replace(/^[^0-9]+|[^0-9]+$/g, ''); 
    codigo = codigo.trim();
    document.getElementById("nroinventario").value = codigo;
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

			if (response.estado=="I") {
			    document.getElementById('msginventarioreg').innerHTML = '<b>Este Nro de Inventario ya fue registrado</b>';
			    updateScannedList();
			    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);

			} else {

			var registros = response.registros;
			registros.forEach(function(registro) {

			nroreg=nroreg+1;
			if (nroreg==1) {
			    // Rellenar los otros inputs con los datos del producto
			    $('#archivo').val(registro.archivo);
			    $('#nropaquete').val(registro.nro_paquete);
			    $('#dependencia').val(registro.paq_dependencia);
			    choices.setChoiceByValue(registro.paq_dependencia);
			    $('#despacho').val(registro.despacho);
			    
			    $('#datinve').text($('#nroinventario').val()); 
			    $('#datarch').text($('#archivo option:selected').text());
			    $('#datpaqu').text($('#nropaquete').val());
			    $('#datdepe').text($('#dependencia option:selected').text());
			    $('#datdesp').text($('#despacho option:selected').text());
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
			scannedItems.push({ codbarras, dependencia, ano, nroexpediente, tipo, estado, lafecha, lahora});

			});

			updateScannedList();
			document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);
			
			document.getElementById("datacabe").style.display = "none"; 
			document.getElementById("datascan").style.display = "block";

                        document.getElementById("codbarras").value='';
                        document.getElementById('codbarras').focus();        
			
			}

		    } else {
			//alert(response.message);
                        document.getElementById("verocultar1").style.display = "block";
                        document.getElementById("verocultar2").style.display = "block";
                        document.getElementById("verocultar3").style.display = "block";
                        document.getElementById("verocultar4").style.display = "block";
		    }
		},
		error: function() {
		    alert('Hubo un error al buscar nro inventario.');
		}
	    });
	} else {
	    document.getElementById('msginventarioreg').innerHTML = '';
	    updateScannedList();
	    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);
	    
	    document.getElementById("verocultar1").style.display = "none";
	    document.getElementById("verocultar2").style.display = "none";
	    document.getElementById("verocultar3").style.display = "none";
	    document.getElementById("verocultar4").style.display = "none";
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
    if (confirm(`\u00BF Est\u00E1s seguro de eliminar el elemento con c\u00F3digo de barras: ${codbar} ?`)) {
        $.ajax({
            url: '{{ route("elimina.item") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                codbarras: codbar
            },
            success: function(response) {
                scannedItems.splice(index, 1);
                updateScannedList();
                alert('Elemento eliminado correctamente.');
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
            messageErr.style.display = 'none';
        }, 4000); 
    }
    if (messageOK) {
        setTimeout(function() {
            messageOK.style.display = 'none';
        }, 4000); 
    }
};
</script>
@endsection
