@extends('menu.index')

@section('content')

<!--<div class="container mt-4">
    <h2 class="mb-4">Lista de Personal</h2>-->
<form autocomplete="off">
        @csrf

    @if(session('success'))
        <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('success') }}</b></div>
    @else
        <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display: none;"></div>    
    @endif 

            <div class="row" id="datacabe">

              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">VEHICULOS EN COMISIÓN</div>
                  </div>

                  <div class="card-body">

                    <div class="d-flex flex-wrap gap-3">
                        @foreach($vehiculosdili as $p)
                            <div class="card vehiculo-card">
                                <div class="contenedor-imagen bg-danger">
                                    <img style="padding-top: 15px;"
                                        src="/manexp/public/img/auto.png" 
                                        class="imagen-auto"
                                        alt="Vehículo" 
                                    >
                                    <div class="placa-overlay">
                                        {{ $p->nroplaca }}
                                    </div>
                                </div>
                                <div class="card-body p-2 text-center bg-primary text-white">
                                    <small style="font-size:12px;"><b>
                                        {{ $p->marca }} {{ $p->modelo }}</b>
                                    </small>
                                </div>
                            </div>                        
                        @endforeach
                    </div>



                  </div>        
                </div>
              </div>

            </div>

            <div class="row" id="datacabe">

              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">VEHICULOS EN SEDE</div>
                  </div>

                  <div class="card-body">

                    <div class="d-flex flex-wrap gap-3">
                        @foreach($vehiculossede as $p)
                            <div class="card vehiculo-card">
                                <div class="contenedor-imagen bg-success">
                                    <img style="padding-top: 15px;"
                                        src="/manexp/public/img/auto.png" 
                                        class="imagen-auto"
                                        alt="Vehículo" 
                                    >
                                    <div class="placa-overlay">
                                        {{ $p->nroplaca }}
                                    </div>
                                </div>
                                <div class="card-body p-2 text-center bg-primary text-white">
                                    <small style="font-size:12px;"><b>
                                        {{ $p->marca }} {{ $p->modelo }}</b>
                                    </small>
                                </div>
                            </div>                        
                        @endforeach
                    </div>



                  </div>        
                </div>
              </div>

            </div>


<style>
.vehiculo-card{
    width:180px;    
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 2px 8px rgba(0,0,0,0.2);
}

.contenedor-imagen{
    position:relative;
    text-align:center;
}


.imagen-auto{
    width:130px;
    height:auto;
    object-fit:cover;
}

.placa-overlay{
    position:absolute;
    bottom:8px;
    left:50%;
    transform:translateX(-50%);
    
    background:rgba(0,0,0,0.7);
    color:white;

    padding:4px 2px;
    border-radius:6px;

    font-weight:bold;
    font-size:16px;
    letter-spacing:1px;
}    
</style>    



<!-- Modal -->
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">MOVIMIENTO NO REGISTRADO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="textomostrar">
        
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    
    </div>
  </div>
</div>

</form>
<!--</div>-->
@endsection

@push('scripts')
<script>
var element = document.getElementById('kilomet');
var maskOptions = {
  mask: '000000'
};
var mask = IMask(element, maskOptions);

var maskIdConductor = IMask(
  document.getElementById('id_conductor'),
  { mask: '00000000' } 
);
IMask(document.getElementById('placa'), {
  mask: 'AAA-000', 
  prepare: function (str) {
    return str.toUpperCase(); // fuerza mayúsculas
  },
  definitions: {
    'A': /[A-Za-z0-9]/  // permite letras o números en los primeros 3
  }
});

    function validaidkey() {
        if (event.keyCode === 13) {
            validaid();
        }
    }
    function validaid() {
        let idco = document.getElementById("id_conductor").value;
        if (idco==""){
            alert("Ingrese el ID del conductor");
            return false;
        }

        $.ajax({
            url: '{{ route("transporte.valida") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                idco: idco,
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {
                    document.getElementById("placa").focus();
                } else {
                    document.getElementById('textomostrar').innerHTML = `
                    <div style="display:flex; align-items:flex-start; gap:10px;">
                        <i class="fas fa-exclamation-triangle fa-3x" style="color:#f0ad4e; flex-shrink:0;"></i>
                        <div>${response.message}</div>
                    </div>
                    `;
                    const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
                    myModal.show();
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
                            alert('Hubo un error al grabar.');
                        }
            }

        });

    }
    function validaplacakey() {
        if (event.keyCode === 13) {
            validaplaca();
        }
    }
    function validaplaca() {
        let plac = document.getElementById("placa").value;
        if (plac==""){
            alert("Ingrese la placa del vehiculo");
            return false;
        }

        $.ajax({
            url: '{{ route("transporte.valida") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                plac: plac,
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {
                    document.getElementById("kilomet").focus();
                } else {
                    document.getElementById('textomostrar').innerHTML = `
                    <div style="display:flex; align-items:flex-start; gap:10px;">
                        <i class="fas fa-exclamation-triangle fa-3x" style="color:#f0ad4e; flex-shrink:0;"></i>
                        <div>${response.message}</div>
                    </div>
                    `;
                    const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
                    myModal.show();
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
                            alert('Hubo un error al grabar.');
                        }
            }

        });

    }



    function grabarmov(e) {
        let tipo = document.getElementById("tipo").value;
        let idco = document.getElementById("id_conductor").value;
        let plac = document.getElementById("placa").value;
        let kilo = document.getElementById("kilomet").value;
        let obse = document.getElementById("observa").value;

        if (idco==""){
            alert("Ingrese el ID del conductor");
            return false;
        }
        if (plac==""){
            alert("Ingrese la placa del vehiculo");
            return false;
        }

        $.ajax({
            url: '{{ route("transporte.grabamovimiento") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tipo: tipo,
                idco: idco,
                plac: plac,
                kilo: kilo,
                obse: obse,
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {
                    sessionStorage.setItem('success_message', response.message);
                    window.location.href = "{{ route("transporte.movimiento") }}";
                } else {
                    document.getElementById('textomostrar').innerHTML = `
                    <div style="display:flex; align-items:flex-start; gap:10px;">
                        <i class="fas fa-exclamation-triangle fa-3x" style="color:#f0ad4e; flex-shrink:0;"></i>
                        <div>${response.message}</div>
                    </div>
                    `;
                    const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
                    myModal.show();
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
                            alert('Hubo un error al grabar.');
                        }
            }

        });

    }


    function updateClock() {
        const now = new Date();
        const formatted =
            now.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            }) + '<br>' + now.toLocaleTimeString('es-ES');

        //document.getElementById('clock').textContent = formatted;
        document.getElementById('clock').innerHTML = formatted;
    }

    updateClock();
    setInterval(updateClock, 1000); // Actualiza cada segundo



    document.addEventListener('DOMContentLoaded', function () {
        let msg = sessionStorage.getItem('success_message');
        if (msg) {
            document.getElementById('messageOK').innerHTML = `<b>${msg}</b>`;
            sessionStorage.removeItem('success_message');
            document.getElementById('messageOK').style.display = 'block';

        }
    });
window.onload = function() {
    var messageOK = document.getElementById('messageOK');
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


@endpush