@extends('menu.index')

@section('content')
    <!-- Mostrar el mensaje de �xito o error -->
    <form id="miFormulario" autocomplete="off">
    @if(session('messageErr'))
        <div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease;"><b>{{ session('messageErr') }}</b></div>
    @else
        <div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    
    @endif
    @if(session('success'))
        <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('success') }}</b></div>
    @else
        <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
    @endif


        @csrf

            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">Reactivacion de Inventario</div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group" style="padding:5px;">
                          <label for="nroinventario"><b>Nro Inventario:</b></label>
                          <div class="input-group">
                          <input type="text" class="form-control form-control-sm" name="nroinventario" id="nroinventario" onkeydown="buscanroinventa(event)" autofocus/>

                <input type="hidden" id="archivo" name="archivo">
                <input type="hidden" id="nropaquete" name="nropaquete">
                <input type="hidden" id="anaquel" name="anaquel">
                <input type="hidden" id="dependencia" name="dependencia">
                <input type="hidden" id="despacho" name="despacho">                          

                          <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="ejecutabuscar()">
                          <i class="fas fa-check me-1"></i> Verificar
                          </button>
                          
                          </div>
                          
                        </div>
                      </div>
                    </div>

                  </div>        
                </div>
              </div>
            </div>
            
            
            



            <div class="row" id="datascan" style="display:none;">            
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Reactivacion de Inventario</div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 col-lg-6">
                          <table width="100%"><tr><td width="100px;"><b>Nro Inventario:</b></td><td id="datinve"></td></tr></table>
                          <!--<label><b>Nro Inventario:</b> <span id="datinve"></span></label>-->
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4 col-lg-4">
                          <table width="100%"><tr><td width="100px;"><b>Archivo:</b></td><td id="datarch"></td></tr></table>
                      </div>
                      <div class="col-md-4 col-lg-4">
                          <table width="100%"><tr><td width="100px;"><b>Nro Paquete:</b></td><td id="datpaqu"></td></tr></table>
                      </div>
                      <div class="col-md-4 col-lg-4">
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
		              </tr>
		          </thead>
		          <tbody>
		        	<!-- Los datos escaneados se ir�n a�adiendo aqu� -->
		          </tbody>
		      </table>
		      </span>

                  </div>
        
                  <div class="card-action">
                    <!--<button class="btn btn-success">Grabar</button>
                    <button class="btn btn-danger">Cancel</button>-->
        	    <!--<button id="grabarBtn" class="btn btn-primary">Inventariar c&oacute;digos escaneados</button>-->
        	    <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#textoModal">Inventariar c&oacute;digos escaneados</button>-->
        	    <button type="button" class="btn btn-primary" onclick="prepararYMostrarModal()">REACTIVAR INVENTARIO</button>
                  </div>
                </div>
              </div>
            </div>
            
    </form>
    <form action="{{ route('reactivainventariograbar') }}" method="POST" id="miFormulario2" autocomplete="off">
    @csrf  <!-- Este campo incluir� el token CSRF autom�ticamente -->
	          <input type="hidden" id="nroinventarioact" name="nroinventarioact">

<!-- Modal -->
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR REACTIVACION DE INVENTARIO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <b>SE REACTIVARA EL INVENTARIO, EL USUARIO QUE LO REGISTRO PODRA ACTUALIZAR LOS DATOS; DESEA CONTINUAR?</b>
      </div>      
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" onclick="guardarTexto()">Continuar y Grabar Inventario</button>-->
        <button type="button" id="grabarBtn" class="btn btn-primary">Continuar y Reactivar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
</div>

    </form>



<script>
document.getElementById("miFormulario").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
    }
});
    
let scannedItems = []; // Array para almacenar los c�digos escaneados
let datoscab = []; // Array 

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
	    </tr>
	`);

	tableBodycel.append(`
	    <tr>
		<td style="font-size:11px; padding: 5px 10px !important;">${item.codbarras}</td>
		<td style="font-size:11px; padding: 5px 10px !important;">${item.lafecha} ${item.lahora}</td>
	    </tr>
	`);

    });

}    
    
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
        event.preventDefault();  // Prevenir el comportamiento por defecto
        document.getElementById("miFormulario2").submit();
});
            

function buscanroinventa(event) {
    let codigo = document.getElementById("nroinventario").value;
codigo = codigo.replace(/^[^A-Za-z0-9-]+|[^A-Za-z0-9-]+$/g, '');
    codigo = codigo.trim();
    
    
    document.getElementById("nroinventarioact").value=codigo;
    scannedItems = [];
    var nroreg=0;
	if (event.keyCode === 13) {

    if ($.trim($('#nroinventario').val())==="") {
    	alert("Ingrese y verifique el Nro de Inventario");
    	return false;
    }

	    $.ajax({
		url: '{{ route("reactivainventariobuscar") }}',
		method: 'POST',
		data: {
		    _token: '{{ csrf_token() }}',
		    nroinventario: codigo
		},
		success: function(response) {
		    if (response.success) {

          if (response.estado=="A" || response.estado=="O") {
              updateScannedList();

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
                $('#anaquel').val(registro.anaquel);
                $('#dependencia').val(response.paq_dependencia);
                $('#despacho').val(response.despacho);
                
                $('#datinve').text($('#nroinventario').val()); 
                $('#datarch').text("Archivo 00" + $('#archivo').val());
                $('#datpaqu').text($('#nropaquete').val());
                $('#datanaq').text($('#anaquel').val());
                $('#datdepe').text(response.des_dependencia);
                $('#datdesp').text($('#despacho').val());
            }
            var codbarras = registro.codbarras;
            var dependencia = registro.id_dependencia;
            var ano = registro.ano_expediente;
            var nroexpediente = registro.nro_expediente;
            var tipo = registro.id_tipo;
            var estado = registro.estado;
//            if (estado=="L") {
//                var lafecha = registro.fecha_lectura;
//                var lahora = registro.hora_lectura;
//            }
//            if (estado=="I") {
                var lafecha = registro.fecha_inventario;
                var lahora = registro.hora_inventario;
//            }
            scannedItems.unshift({ codbarras, dependencia, ano, nroexpediente, tipo, estado, lafecha, lahora});

          });

          updateScannedList();
          
          document.getElementById("datacabe").style.display = "none"; 
          document.getElementById("datascan").style.display = "block";

          }

		    } else {
alert(response.message);
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
