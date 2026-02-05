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

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title">REGISTRO DE INGRESO/SALIDA DE VEHICULOS</div>
        </div>
        <div class="card-body table-responsive">


                    <div class="row" >

                      <div class="col-md-8 col-lg-8">
                        <div class="form-group border p-3 rounded shadow-sm bg-light">
                            <!--<h5 class="text-success"><i class="fas fa-car"></i> <i class="fas fa-arrow-right"></i> REGISTRO DE INGRESO DE VEHICULOS</h5>
                            <hr>-->
                            <div class="row mb-3">
                                <div class="col-md-4 col-lg-4">
                                        <label for="activo" class="form-label"><b>TIPO</b></label>
                                        <select name="tipo" id="tipo" class="form-select">
                                            <option value="I" {{ (old('tipo', $controlvehiculo->tipo ?? '') == 'I') ? 'selected' : '' }}>INGRESO</option>
                                            <option value="S" {{ (old('tipo', $controlvehiculo->tipo ?? '') == 'S') ? 'selected' : '' }}>SALIDA</option>
                                        </select>
                                        @error('tipo') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <!--<label for="id_conductor" class="form-label"><b>ID Conductor</b></label>
                                    <input type="text" name="id_conductor" id="id_conductor" class="form-control" maxlength="8" value="" placeholder="00000000" >-->

                                    <div class="form-group" style="padding:5px;">
                                        <label for="id_conductor"><b>ID Conductor</b></label>
                                        <div class="input-group">
                                            <input type="text" name="id_conductor" id="id_conductor" class="form-control" maxlength="8" value="" placeholder="00000000" onkeydown="validaidkey()" autofocus>
                                            <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="validaid()">
                                            <i class="fas fa-check me-1"></i> Validar
                                            </button>
                                        </div>                                    
                                    </div>

                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <!--<label for="placa" class="form-label"><b>Placa Veh&iacute;culo</b></label>
                                    <input type="text" name="placa" id="placa" class="form-control" maxlength="7" value="" placeholder="Ejem. AAA-111">-->

                                    <div class="form-group" style="padding:5px;">
                                        <label for="placa"><b>Placa Veh&iacute;culo</b></label>
                                        <div class="input-group">
                                            <input type="text" name="placa" id="placa" class="form-control" maxlength="7" value="" placeholder="Ejem. AAA-111" onkeydown="validaplacakey()">
                                            <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="validaplaca()">
                                            <i class="fas fa-check me-1"></i> Validar
                                            </button>
                                        </div>                                    
                                    </div>


                                </div>
                            </div>

                        </div>
                      </div>
                      <div class="col-md-4 col-lg-4 d-flex justify-content-center">
                        <div class="form-group border p-3 rounded shadow bg-light text-center">                          
                            <i class="fas fa-calendar-alt fa-4x"></i><hr>
                            <h5 class="card-title" id="clock">{{ now()->format('d/m/Y H:i:s') }}</h5>
                        </div>
                      </div>

                    </div>

        </div>
    </div>

<!--            <div class="row" id="datacabe">-->

              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <!--<div class="card-title">SUBIR PDF DIGITALIZADOS A SERVIDOR</div>-->
                    <ul class="nav nav-tabs card-header-tabs" id="tabsCard" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1"
                                type="button" role="tab" aria-controls="tab1" aria-selected="true">
                                <b>CONDUCTORES Y VEHICULOS EN SEDE</b>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2"
                                type="button" role="tab" aria-controls="tab2" aria-selected="false">
                                <b>CONDUCTORES Y VEHICULOS EN DILIGENCIA</b>
                            </button>
                        </li>
                    </ul>
                  </div>

                  <div class="card-body">
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">

                            <div class="row" >
                            <div class="col-md-6 col-lg-6">

                            <h5><b>Conductores en SEDE</b></h5>
                            <table class="table table-striped table-bordered table-hover" width=100%>
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">ID Conductor</th>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Conductor</th>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($conductoressede as $p)
                                        @if ($p->marco_asistencia=="S" && $p->conductorensede=="S")
                                        <tr>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->id_conductor }}</td>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->fechahora_ultimomov }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            </div>
                            <div class="col-md-6 col-lg-6">

                            <h5><b>Veh&iacute;culos en SEDE</b></h5>
                            <table class="table table-striped table-bordered table-hover" width=100%>
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Placa</th>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Veh&iacute;culo</th>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vehiculossede as $p)
                                        <tr>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->nroplaca }}</td>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->marca }} {{ $p->modelo }} {{ $p->color }}</td>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->fechahora_ultimomov }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            </div>
                            </div>


                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                            <div class="row" >
                            <div class="col-md-6 col-lg-6">

                            <h5><b>Conductores en DILIGENCIA</b></h5>
                            <table class="table table-striped table-bordered table-hover" width=100%>
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">ID Conductor</th>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Conductor</th>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($conductoressede as $p)
                                        @if ($p->marco_asistencia=="S" && $p->conductorensede=="N")
                                        <tr>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->id_conductor }}</td>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->fechahora_ultimomov }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            </div>
                            <div class="col-md-6 col-lg-6">

                            <h5><b>Veh&iacute;culos en DILIGENCIA</b></h5>
                            <table class="table table-striped table-bordered table-hover" width=100%>
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Placa</th>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Veh&iacute;culo</th>
                                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vehiculosdili as $p)
                                        <tr>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->nroplaca }}</td>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->marca }} {{ $p->modelo }} {{ $p->color }}</td>
                                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->fechahora_ultimomov }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            </div>
                            </div>

                        </div>
                    </div>


                  </div>        
                </div>
              </div>
<!--            </div>-->







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
        let tipo = document.getElementById("tipo").value;
        let idco = document.getElementById("id_conductor").value;
        let plac = document.getElementById("placa").value;

        if (plac==""){
            alert("Ingrese la placa del vehiculo");
            return false;
        }

        $.ajax({
            url: '{{ route("transporte.valida2") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tipo: tipo,
                idco: idco,
                plac: plac,
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {
                    sessionStorage.setItem('success_message', response.message);
                    window.location.href = "{{ route("transporte.movimiento2") }}";
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