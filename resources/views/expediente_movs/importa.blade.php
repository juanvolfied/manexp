@extends('menu.index') 

@section('content')
    @if(session('messageErr'))
        <div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease;"><b>{{ session('messageErr') }}</b></div>
    @else
        <div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 1055; min-width: 50%; max-width: 90%; text-align: center;"></div>    
    @endif
    @if(session('messageOK'))
        <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('messageOK') }}</b></div>
    @else
        <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
    @endif    
    <!--<h2 class="mb-4">Seguimiento de Expedientes</h2>-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Importar Carpetas Fiscales</div>
        </div>
        <div class="card-body table-responsive">

    <form action="{{ route('expediente.grabaimporta') }}" method="POST" id="miFormulario2" autocomplete="off">

        @csrf
<!--
        <div class="row">
        <div class="col-md-2">
            <label for="codigo" class="form-label"><b>C&oacute;digo Transferencia</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="text" id="codigo" name="codigo" onkeydown="mostrarcarpetas(event)" class="form-control text-center" maxlength="15" style="width: 150px;" >
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="mostrarcarpetas2(event)" class="btn btn-primary w-100">Mostrar las Carpetas</a>
        </div>        
        <div class="col-md-5 d-flex align-items-end" id="nroexpediente" style="font-size:20px;font-weight:bold; color:red;">
            
        </div>       
      </div>
-->
                    <div class="row">
                      <div class="col-md-4 col-lg-4">
                        <div class="form-group" style="padding:5px;">
                          <label for="codigo"><b>C&oacute;digo Transferencia:</b></label>
                          <div class="input-group">
                          <input type="text" class="form-control " name="codigo" id="codigo" onkeydown="mostrarcarpetas(event)" autofocus/>
                          
                          <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="mostrarcarpetas2(event)">
                          <i class="fas fa-check me-1"></i> Mostrar las Carpetas
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
      
	          <input type="hidden" id="scannedItemsInput" name="scannedItems">

    </form>

    <div class="mt-5">
        <table id="scanned-list" class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">#</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">CARPETA FISCAL</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">FOLIOS</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">AGRAVIADO</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">IMPUTADO</th>			      
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">DELITO</th>
                    <!--<th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">AÑO</th>-->
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">OBSERVACION</th>
                </tr>
            </thead>
            <tbody style="font-size:12px;" >
            </tbody>
        </table>        
    </div>

        </div>


        <div class="card-action">
            <button type="button" class="btn btn-primary" onclick="prepararYMostrarModal()">Importar Carpetas Fiscales</button>
        </div>        
    </div>
    
    



<!-- Modal -->
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        <b>LAS CARPETAS FISCALES SE IMPORTARAN A LA BASE DE DATOS, DESEA CONTINUAR ?</b>
      </div>
      
      <div class="modal-footer">
        <button type="button" id="grabarBtn" class="btn btn-primary">Continuar y Grabar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
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

@endsection




@section('scripts')

<script>
    $('#dependencia').selectize();
let scannedItems = []; // Array para almacenar los c�digos escaneados


function mostrarcarpetas(event) {
            const tableBody = $('#scanned-list tbody');
            const tableBodycel = $('#scanned-listcel tbody');
            tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla
            tableBodycel.empty(); // Limpiar la tabla antes de volver a renderizarla

    //document.getElementById('nroexpediente').innerHTML="";

    let codtra = document.getElementById("codigo").value;
    codtra = codtra.replace(/^[^A-Za-z0-9-]+|[^A-Za-z0-9-]+$/g, '');
    codtra = codtra.trim();
    document.getElementById("codigo").value=codtra;

    //if (event) event.preventDefault(); // Previene recarga
    //const codtra = document.getElementById('codigo').value;

    if (event.keyCode === 13) {    
        if ( codtra=="" ) {
            alert ("EL CODIGO NO ESTA INGRESADO CORRECTAMENTE");
            return false;
        }

        $.ajax({
            url: '{{ route("expediente.buscapaqtransfe") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                codtra: codtra
            },
            success: function(response) {
                if (response.success) {
                    var coddep = response.coddepen;
                    var despac = response.despacho;
                    var nropaq = response.paquete;

    const selectizeInstance = $('#dependencia')[0].selectize;
    selectizeInstance.setValue(coddep);     
    document.getElementById("despacho").value=despac;
    document.getElementById("nropaquete").value=nropaq;


                    var registros = response.registros;
                    registros.forEach(function(registro, index) {

            var codbarras = registro.nrocarp;
            let partes = codbarras.split("-");
            var dependencia = partes[0]; // "223322"
            var ano = partes[1]; // "2025"
            var nroexpediente = partes[2]; // "001123"  
            var tipo = 0;  
            var estado="A";
            var tomo = 0;          

            var folios = registro.folios;          
            var agraviado = registro.agraviado;          
            var imputado = registro.imputado;          
            var delito = registro.codmateria;          
            var observacion = registro.observacion;          

                        var ano=registro.ano;

                        tableBody.append(`
                            <tr>
                                <td style="font-size:12px; padding: 5px 10px !important;">${index+1}</td>
                                <td style="font-size:12px; padding: 5px 10px !important;">${registro.nrocarp}</td>
                                <td style="font-size:12px; padding: 5px 10px !important;">${registro.folios}</td>
                                <td style="font-size:12px; padding: 5px 10px !important;">${registro.agraviado}</td>
                                <td style="font-size:12px; padding: 5px 10px !important;">${registro.imputado}</td>                        
                                <td style="font-size:12px; padding: 5px 10px !important;">${registro.descripcion}</td>                        
                                <td style="font-size:12px; padding: 5px 10px !important;">${registro.observacion}</td>                        
                            </tr>
                        `);

                    //scannedItems.unshift({ codbarras, dependencia, ano, nroexpediente, tipo, estado, lafecha, lahora, tomo});
                    scannedItems.push({ codbarras, dependencia, ano, nroexpediente, tipo, estado, tomo, folios, agraviado, imputado, delito, observacion});


                    });
                    //updateScannedList();
                    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);


                        document.getElementById("verocultar1").style.display = "block";
                        document.getElementById("verocultar2").style.display = "block";
                        document.getElementById("verocultar3").style.display = "block";
                        document.getElementById("verocultar4").style.display = "block";
                        document.getElementById("verocultar5").style.display = "block";
                        document.getElementById("verocultar6").style.display = "block";


                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 419) {
                    // No autorizado - probablemente sesión expirada
                    alert('TU SESION HA EXPIRADO. SERAS REDIRIGIDO AL LOGIN.');
                    window.location.href = '{{ route("usuario.login") }}';
                } else {
                    // Otro tipo de error
                    console.error('Error en la petición:', xhr.status);
                    alert('Hubo un error al buscar codigo de transferencia.');
                }
            }        
        });
	
    } else {
	    //updateScannedList();
	    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);
	    
	    document.getElementById("verocultar1").style.display = "none";
	    document.getElementById("verocultar2").style.display = "none";
	    document.getElementById("verocultar3").style.display = "none";
	    document.getElementById("verocultar4").style.display = "none";
	    document.getElementById("verocultar5").style.display = "none";
	    document.getElementById("verocultar6").style.display = "none";
	}
}
function mostrarcarpetas2() {
    const input = document.getElementById("codigo");
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



function prepararYMostrarModal() {
  if (event) event.preventDefault(); // Previene recarga
  var msgerr="";
  if (document.getElementById('codigo').value.trim() === "") {
      msgerr = "INGRESE EL CODIGO DE TRANSFERENCIA A IMPORTAR Y PRESIONE EL BOTON MOSTRAR CARPETAS FISCALES";
  } else if (document.getElementById('archivo').value.trim() === "") {
      msgerr = "SELECCIONE EL ARCHIVO";
  } else if (document.getElementById('nropaquete').value.trim() === "") {
      msgerr = "INGRESE EL PAQUETE";
  } else if (document.getElementById('anaquel').value.trim() === "") {
      msgerr = "INGRESE EL ANAQUEL";
  } else if (document.getElementById('dependencia').value.trim() === "") {
      msgerr = "SELECCIONE LA DEPENDENCIA";
  } else if (document.getElementById('despacho').value.trim() === "") {
      msgerr = "SELECCIONE EL DESPACHO";
  } else if (scannedItems.length == 0) {
      msgerr = "CARPETAS FISCALES NO DISPONIBLES";
  }
  if (msgerr == "") {
  //if (scannedItems.length > 0) {
      const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
      myModal.show();
  } else {
//      document.getElementById('messageErr').innerHTML = '<b>CARPETAS FISCALES NO DISPONIBLES</b>';
      document.getElementById('messageErr').innerHTML = '<b>' + msgerr + '</b>';
      var messageErr = document.getElementById('messageErr');
      messageErr.style.opacity = '1';
      messageErr.style.display = 'block';
          messageErr.scrollIntoView({ behavior: 'smooth', block: 'start' });

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
        document.getElementById('messageErr').innerHTML = '<b>CARPETAS FISCALES NO DISPONIBLES</b>';
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
