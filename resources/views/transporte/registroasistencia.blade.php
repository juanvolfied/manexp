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
                    <div class="card-title">Registrar de Asistencia de Conductores : {{ date('Y-m-d') }}</div>
                  </div>
                  <div class="card-body">


    <form id="miFormulario" action="{{ route('mesapartes.store') }}" method="POST" autocomplete="off">
        @csrf


<div class="row mb-3">
    <div class="col-md-3 col-lg-3">
        <div class="form-group" style="padding:5px;">
            <label for="idconductor" class="form-label"><b>ID CONDUCTOR</b></label>
            <div class="input-group">
            <input type="text" class="form-control" id="idconductor" name="idconductor" onkeydown="buscanroinventa(event)" autofocus/>
            <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="ejecutabuscar()">
            <i class="fas fa-check me-1"></i> Registrar
            </button>
            
            </div>
            
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-12">
    <h4>Conductores con Asistencia Registrada</h4>
    <table id="tablaconductores" class="table table-striped table-bordered" width=100%>
        <thead class="thead-dark">
            <tr>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">#</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">ID Conductor</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Conductor</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Hora Ingreso</th>
            </tr>
        </thead>
        <tbody style="font-size:12px;">
        @foreach($conductores as $index => $item)
            <tr>
                <td style="padding: 5px 5px!important; font-size:12px !important; text-transform:none;">{{ $index + 1 }}</td>
                <td style="padding: 5px 5px!important; font-size:12px !important; text-transform:none;">{{ $item->id_conductor }}</td>
                <td style="padding: 5px 5px!important; font-size:12px !important; text-transform:none;">{{ $item->apellido_paterno }} {{ $item->apellido_materno }} {{ $item->nombres }}</td>
                <td style="padding: 5px 5px!important; font-size:12px !important; text-transform:none;">{{ $item->hora }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
    </div>
</div>



    </form>

    

                  </div>
                </div>
              </div>
            </div>    
<!--</div>-->

@endsection

@section('scripts')
<script>
var element = document.getElementById('idconductor');
var maskOptions = {
  mask: '00000000'
};
var mask = IMask(element, maskOptions);


function buscanroinventa(event) {
    let codigo = document.getElementById('idconductor').value;        
	if (event.keyCode === 13) {
    if ($.trim($('#idconductor').val())==="") {
    	//alert("Ingrese y verifique el Nro de Inventario");
    	return false;
    }

	    $.ajax({
		url: '{{ route("transporte.grabaasistencia") }}',
		method: 'POST',
		data: {
		    _token: '{{ csrf_token() }}',
		    idconductor: codigo
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
    const input = document.getElementById("idconductor");
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