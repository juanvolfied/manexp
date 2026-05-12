@extends('menu.index')

@section('content')

<div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    

@if(session('success'))
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('success') }}</b></div>
@else
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
@endif

<!--<div class="container mt-4">-->
    <!--<h2>Registrar Nuevo Expediente</h2>-->
            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">Programar Solicitud de Salida de Vehículo : {{ date('Y-m-d') }}</div>
                  </div>
                  <div class="card-body">


    <form id="miFormulario"  autocomplete="off">
        @csrf
<div class="d-flex justify-content-center">
<div class="recuadro-validacion">
<div class="row mb-3">
    <div class="col-md-12 col-lg-12">
        <div class="form-group" style="padding:5px;">
            <label for="nroplaca" class="form-label"><b>PLACA</b></label>
            <div class="input-group">
            <input type="text" class="form-control" id="nroplaca" name="nroplaca" placeholder="XXX-123" onkeydown="buscanroinventa(event)" autofocus/>
            <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="ejecutabuscar()">
            <i class="fas fa-check me-1"></i> Validar Placa
            </button>
            
            </div>
            
        </div>

    </div>
</div>
</div>
</div>

<style>
.recuadro-validacion{
    background-color:#f8f9fa;
    border:2px solid #dcdcdc;
    border-radius:12px;

    padding:20px;

    width:450px;
    
    box-shadow:0 2px 8px rgba(0,0,0,0.15);
}    
</style>    


    </form>

    

                  </div>
                </div>
              </div>
            </div>    
<!--</div>-->

@endsection

@section('scripts')
<script>
var element = document.getElementById('nroplaca');
var maskOptions = {
  mask: 'XXX-000',
  definitions: {
    'X': /[A-Za-z0-9]/,
    '0': /[0-9]/
  },
  prepare: function (str) {
    return str.toUpperCase();
  }
};
var mask = IMask(element, maskOptions);


function buscanroinventa(event) {
    let codigo = document.getElementById('nroplaca').value;        
	if (event.keyCode === 13) {
    if ($.trim($('#nroplaca').val())==="") {
    	//alert("Ingrese y verifique el Nro de Inventario");
    	return false;
    }

	    $.ajax({
		url: '{{ route("transporte.grabasolicitudplaca") }}',
		method: 'POST',
		data: {
		    _token: '{{ csrf_token() }}',
		    nroplaca: codigo
		},
		success: function(response) {
		    if (response.success) {
                window.location.href = '{{ route("transporte.registroasistencia") }}';
		    } else {

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
	} 
}

function ejecutabuscar() {
    const input = document.getElementById("nroplaca");
    const event = new KeyboardEvent("keydown", {
        key: "Enter",
        keyCode: 13,
        which: 13,
        bubbles: true
    });
    input.dispatchEvent(event);
}    



</script>


@endsection