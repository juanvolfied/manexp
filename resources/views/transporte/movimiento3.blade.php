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


<!--            <div class="row" id="datacabe">-->
@php
    $vehiculosP = $vehiculosprog->filter(function($v) {
        return $v->estado == 'P';
    });
    $vehiculosD = $vehiculosprog->filter(function($v) {
        return $v->estado == 'D'  && $v->ensede == 'N';
    });
@endphp
              <div class="col-md-12" id="divprogra">

                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Vehículos programados para SALIDA ({{ count($vehiculosP) }})</div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($vehiculosP as $p)
                            <div class="card vehiculo-card" style="cursor:pointer; border: 2px solid #ef4444 !important;"
                            onclick="seleccionarVehiculo(
                                '{{ $p->placa }}',
                                '{{ $p->marca }} {{ $p->modelo }} {{ $p->color }}',
                                '{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}',
                                '{{ $p->observacion ?? '' }}',
                                '{{ $p->kilometraje ?? '' }}',
                                '{{ $p->fechahora_programado ?? '' }}',
                                '{{ $p->id_conductor }}',
                                '{{ $p->id_movimiento }}'
                            )">
                                <div class="contenedor-imagen" style="background: #fff5f5;" >
                                    <img style="padding-top: 15px; width:80px;"
                                        src="/manexp/public/img/auto.png" 
                                        class="imagen-auto"
                                        alt="Vehículo" 
                                    >
                                    <div class="placa-overlay ">
                                        {{ $p->placa }}
                                    </div>
                                </div>
                                <div class="card-body p-2 text-center text-white" style="background: #ef4444; line-height: 1;">
                                    <small style="font-size:11px;"><b>
                                        {{ ucwords(strtolower($p->marca .' '. $p->modelo .' '. $p->color)) }}
                                        <br>
                                        {{ mb_convert_case($p->apellido_paterno .' '. $p->apellido_materno .' '. $p->nombres,
                                        MB_CASE_TITLE,
                                        "UTF-8") }}</b>
                                    </small>
                                </div>
                            </div>                        
                        @endforeach
                    </div>
                  </div>        
                </div>

              </div>

              <div class="col-md-12" id="divencomi">

                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Vehículos en DILIGENCIA ({{ count($vehiculosD) }})</div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($vehiculosD as $p)
                            <div class="card vehiculo-card" style="cursor:pointer; border: 2px solid #eab308 !important;"
                            onclick="seleccionarVehiculoIngreso(
                                '{{ $p->placa }}',
                                '{{ $p->marca }} {{ $p->modelo }} {{ $p->color }}',
                                '{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}',
                                '{{ $p->observacion ?? '' }}',
                                '{{ $p->kilometraje ?? '' }}',
                                '{{ $p->fechahora_programado ?? '' }}',
                                '{{ $p->id_conductor }}',
                                '{{ $p->id_movimiento }}'
                            )">
                                <div class="contenedor-imagen" style="background: #fefce8;" >
                                    <img style="padding-top: 15px; width:80px;"
                                        src="/manexp/public/img/auto.png" 
                                        class="imagen-auto"
                                        alt="Vehículo" 
                                    >
                                    <div class="placa-overlay">
                                        {{ $p->placa }}
                                    </div>
                                </div>
                                <div class="card-body p-2 text-center" style="background: #eab308; line-height: 1;">
                                    <small style="font-size:11px;"><b>
                                        {{ ucwords(strtolower($p->marca .' '. $p->modelo .' '. $p->color)) }}
                                        <br>
                                        {{ mb_convert_case($p->apellido_paterno .' '. $p->apellido_materno .' '. $p->nombres,
                                        MB_CASE_TITLE,
                                        "UTF-8") }}</b>
                                    </small>
                                </div>
                            </div>                        
                        @endforeach
                    </div>
                  </div>        
                </div>

              </div>

              <div class="col-md-12" id="divensede">

                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Vehículos en SEDE ({{ count($vehiculossede) }})</div>
                  </div>
                  <div class="card-body">
                    
                    <div class="row">
                        <div class="col-8">

                    <div class="d-flex flex-wrap gap-3">
                        @foreach($vehiculossede as $p)
                            <div class="card vehiculo-card" style="cursor:pointer; border: 2px solid #eab308 !important;"
                            onclick="seleccionarVehiculoPrograma(
                                '{{ $p->nroplaca }}',
                                '{{ $p->marca }} {{ $p->modelo }} {{ $p->color }}'
                            )">
                                <div class="contenedor-imagen" style="background: #f0fdf4;" >
                                    <img style="padding-top: 15px; width:80px;"
                                        src="/manexp/public/img/auto.png" 
                                        class="imagen-auto"
                                        alt="Vehículo" 
                                    >
                                    <div class="placa-overlay">
                                        {{ $p->nroplaca }}
                                    </div>
                                </div>
                                <div class="card-body p-2 text-center text-white" style="background: #22c55e; line-height: 1;">
                                    <small style="font-size:11px;"><b>
                                        {{ ucwords(strtolower($p->marca .' '. $p->modelo .' '. $p->color)) }}</b>
                                    </small>
                                </div>
                            </div>                        
                        @endforeach
                    </div>

                        </div>

                        <div class="col-4" style="border:1px solid #000;">
                            <b>CONDUCTORES DISPONIBLES ({{ count($conductoressede) }})</b>
                <table id="scanned-list" class="table table-striped table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">ID</th>
                            <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Conductor</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:11px;" >
                        @foreach($conductoressede as $p)
                        <tr>
                            <td style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">{{ $p->id_conductor }}</td>
                            <td style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">👤
                                        {{ mb_convert_case($p->apellido_paterno .' '. $p->apellido_materno .' '. $p->nombres,
                                        MB_CASE_TITLE,
                                        "UTF-8") }}                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>        
                            
                        </div>
                    </div>



                  </div>        
                </div>

              </div>


<!--            </div>-->



<style>
.vehiculo-card{
    transition: 0.2s;
}

.vehiculo-card:hover{
    transform: scale(1.03);
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
}    
.vehiculo-card{
    width:115px;    
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 2px 8px rgba(0,0,0,0.2);
}

.contenedor-imagen{
    position:relative;
    text-align:center;
    display: flex;
    justify-content: center;    
}


.imagen-auto{
    width:100px;
    height:auto;
    object-fit:cover;
}

.placa-overlay{
    position:absolute;
    bottom:3px;
    /*left:50%;
    transform:translateX(-50%);*/
    
    background:rgba(0,0,0,0.7);
    color:white;

    padding:4px 2px;
    border-radius:6px;

    font-weight:bold;
    font-size:14px;
    letter-spacing:1px;
}    
</style>    
<script>
function seleccionarVehiculo(placa, vehiculo, conductor, ruta, kilometraje, fecha, id_conductor, id_movimiento) {
    document.getElementById('placavalida').innerHTML = placa;
    document.getElementById('vehiculovalida').innerHTML = vehiculo;
    document.getElementById('conductorvalida').innerHTML = conductor;
    document.getElementById('rutavalida').innerHTML = ruta;
    document.getElementById('kilometrajevalida').innerHTML = kilometraje;
    document.getElementById('fechavalida').innerHTML = fecha;
    document.getElementById('id_movimiento').value = id_movimiento;

    const myModal = new bootstrap.Modal(
        document.getElementById('modalvalidarsalida')
    );
    myModal.show();
}
function seleccionarVehiculoIngreso(placa, vehiculo, conductor, ruta, kilometraje, fecha, id_conductor, id_movimiento) {
    document.getElementById('placaing').innerHTML = placa;
    document.getElementById('vehiculoing').innerHTML = vehiculo;
    //document.getElementById('id_conductoring').value = id_conductor;
    maskIdConductoring.value = id_conductor;
    document.getElementById('msgconing').innerHTML = conductor;
    document.getElementById('rutaing').value = ruta;
    document.getElementById('kilometrajeantes').innerHTML = kilometraje;
    //document.getElementById('kilometrajeing').value = kilometraje;
    maskKilometrajeing.value = kilometraje;    
    //document.getElementById('fechavalida').innerHTML = fecha;
    document.getElementById('id_movimiento').value = id_movimiento;
    const myModal = new bootstrap.Modal(
        document.getElementById('modalingreso')
    );
    myModal.show();
}
function seleccionarVehiculoPrograma(placa, vehiculo) {
    document.getElementById('placapro').innerHTML = placa;
    document.getElementById('vehiculopro').innerHTML = vehiculo;
//    const myModal = new bootstrap.Modal(
//        document.getElementById('modalprograma')
//    );
//    myModal.show();
    const modalElement = document.getElementById('modalprograma');
    const myModal = new bootstrap.Modal(modalElement);
    modalElement.addEventListener('shown.bs.modal', function () {
        document.getElementById('id_conductorpro').focus();
    }, { once: true });
    myModal.show();      
}


</script>

<div class="modal fade" id="modalvalidarsalida" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">VALIDAR SALIDA DE VEHICULO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" style="font-size: 16px;">
        <b>Placa : </b><span id="placavalida"></span><br>
        <b>🚛Vehículo : </b><span id="vehiculovalida"></span><br>
        <b>👤Conductor : </b><span id="conductorvalida"></span><br>
        <b>🗺️Ruta programada : </b><span id="rutavalida"></span><br>
        <b>⛽Kilometraje salida : </b><span id="kilometrajevalida"></span><br>
        <b>Fecha programación : </b><span id="fechavalida"></span><br><br>
        ⚠️ Al confirmar, se registrará fecha/hora de salida y el vehículo pasará a estado "EN DILIGENCIA".
        <input type="hidden" id="id_movimiento">
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" 
        id="confirmasalida" 
        class="btn btn-primary" 
        onclick="grabarmov('V',
        document.getElementById('id_movimiento').value,
        document.getElementById('placavalida').textContent
        )">
        Confirmar Salida
        </button>
      </div>
    
    </div>
  </div>
</div>

<div class="modal fade" id="modalingreso" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">INGRESO DE VEHICULO DE DILIGENCIA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" style="font-size: 16px;">
        <div class="mb-2">
            <b>Placa : </b><span id="placaing"></span>
        </div>
        <div class="mb-2">
            <b>🚛Vehículo : </b><span id="vehiculoing"></span>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>👤ID Conductor : </b><input type="text" name="id_conductoring" id="id_conductoring" class="form-control" maxlength="8" style="width: 100px;" value="" placeholder="00000000" >
            <small id="msgconing" class="form-text text-muted text-primary"></small>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>⛽Kilometraje ingreso : [<span id="kilometrajeantes"></span>]</b><input type="text" name="kilometrajeing" id="kilometrajeing" class="form-control" maxlength="6" style="width: 100px;" value="" placeholder="000000">
            <small id="msgkilo" class="form-text text-muted text-danger">Dato no válido</small>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>🗺️Ruta programada : </b><input type="text" name="rutaing" id="rutaing" class="form-control" maxlength="100" style="width: 500px;" value="">
        </div>

        ⚠️ Al confirmar, se registrará fecha/hora de ingreso y el vehículo estar "EN SEDE".
        <input type="hidden" id="id_movimiento">
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" 
        id="confirmaingreso" 
        class="btn btn-primary" 
        onclick="grabarmov('I',
        document.getElementById('id_movimiento').value,
        document.getElementById('placaing').textContent,
        document.getElementById('id_conductoring').value,
        document.getElementById('kilometrajeing').value,
        document.getElementById('rutaing').value
        )">
        Confirmar Ingreso
        </button>
      </div>
    
    </div>
  </div>
</div>

<div class="modal fade" id="modalprograma" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">PROGRAMAR SALIDA DE VEHICULO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" style="font-size: 16px;">
        <div class="mb-2">
            <b>Placa : </b><span id="placapro"></span>
        </div>
        <div class="mb-2">
            <b>🚛Vehículo : </b><span id="vehiculopro"></span>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>👤ID Conductor : </b><input type="text" name="id_conductorpro" id="id_conductorpro" class="form-control" maxlength="8" style="width: 100px;" value="" placeholder="00000000" >
            <small id="msgcon" class="form-text text-muted text-danger">Ingrese DNI registrado</small>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>⛽Kilometraje : </b><input type="text" name="kilometrajepro" id="kilometrajepro" class="form-control" maxlength="6" style="width: 100px;" value="" placeholder="000000">
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>🗺️Ruta programada : </b><input type="text" name="rutapro" id="rutapro" class="form-control" maxlength="100" style="width: 500px;" value="">
        </div>

        ⚠️ Al presionar programar salida, el vehículo estará en lista de vehículos programados.
        <input type="hidden" id="id_movimientopro">
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" 
        id="programarsalida" 
        class="btn btn-primary" 
        onclick="grabarmov('P',
        0,
        document.getElementById('placapro').textContent,
        document.getElementById('id_conductorpro').value,
        document.getElementById('kilometrajepro').value,
        document.getElementById('rutapro').value
        )">
        Programar Salida
        </button>
      </div>
    
    </div>
  </div>
</div>





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
var element = document.getElementById('kilometrajeing');
var maskOptions = {
  mask: '000000'
};
var maskKilometrajeing = IMask(element, maskOptions);
maskKilometrajeing.on('accept', function () {
    //let newkilo = maskKilometrajeing.value.toUpperCase().trim();
    //let oldkilo = document.getElementById('kilometrajeantes').innerHTML;
    let newkilo = parseInt(maskKilometrajeing.value || 0, 10);
    let oldkilo = parseInt(document.getElementById('kilometrajeantes').innerHTML || 0, 10);    
    if (newkilo>=oldkilo) {
        msgkilo.innerHTML = (newkilo - oldkilo) + ' Km recorridos.';
        msgkilo.classList.remove('text-danger');
        msgkilo.classList.add('text-primary');
    } else {
        msgkilo.innerHTML = 'Kilometraje no válido';
        msgkilo.classList.remove('text-primary');
        msgkilo.classList.add('text-danger');
    }
});  



var element = document.getElementById('kilometrajepro');
var maskOptions = {
  mask: '000000'
};
var mask = IMask(element, maskOptions);

var maskIdConductoring = IMask(
  document.getElementById('id_conductoring'),
  { mask: '00000000' } 
);
var maskIdConductorpro = IMask(
  document.getElementById('id_conductorpro'),
  { mask: '00000000' } 
);

    const conductores = @json($conductores);
    const inputConductor = document.getElementById('id_conductorpro');
    const msgCon = document.getElementById('msgcon');
/*    inputConductor.addEventListener('input', function () {
        let idconductor = this.value.toUpperCase().trim();
        let conductor = conductores.find(v =>
            v.id_conductor.toUpperCase() === idconductor
        );
        if (conductor) {
            msgCon.innerHTML = conductor.apellido_paterno + ' ' + conductor.apellido_materno + ' ' + conductor.nombres;
            msgCon.classList.remove('text-danger');
            msgCon.classList.add('text-primary');
        } else {
            msgCon.innerHTML = 'Ingrese DNI registrado';
            msgCon.classList.remove('text-primary');
            msgCon.classList.add('text-danger');
        }
    });*/
maskIdConductorpro.on('accept', function () {
    let idconductor = maskIdConductorpro.value.toUpperCase().trim();
    let conductor = conductores.find(v =>
        v.id_conductor.toUpperCase() === idconductor
    );
    if (conductor) {
        msgCon.innerHTML =
            conductor.apellido_paterno + ' ' +
            conductor.apellido_materno + ' ' +
            conductor.nombres;
        msgCon.classList.remove('text-danger');
        msgCon.classList.add('text-primary');
    } else {
        msgCon.innerHTML = 'Ingrese DNI registrado';
        msgCon.classList.remove('text-primary');
        msgCon.classList.add('text-danger');
    }
});  

    const inputConductorIng = document.getElementById('id_conductoring');
    const msgConIng = document.getElementById('msgconing');
/*    inputConductorIng.addEventListener('input', function () {
        let idconductor = this.value.toUpperCase().trim();
        let conductor = conductores.find(v =>
            v.id_conductor.toUpperCase() === idconductor
        );
        if (conductor) {
            msgConIng.innerHTML = conductor.apellido_paterno + ' ' + conductor.apellido_materno + ' ' + conductor.nombres;
            msgConIng.classList.remove('text-danger');
            msgConIng.classList.add('text-primary');
        } else {
            msgConIng.innerHTML = 'Ingrese DNI registrado';
            msgConIng.classList.remove('text-primary');
            msgConIng.classList.add('text-danger');
        }
    });*/
maskIdConductoring.on('accept', function () {
    let idconductor = maskIdConductoring.value.toUpperCase().trim();
    let conductor = conductores.find(v =>
        v.id_conductor.toUpperCase() === idconductor
    );
    if (conductor) {
        msgConIng.innerHTML =
            conductor.apellido_paterno + ' ' +
            conductor.apellido_materno + ' ' +
            conductor.nombres;
        msgConIng.classList.remove('text-danger');
        msgConIng.classList.add('text-primary');
    } else {
        msgConIng.innerHTML = 'Ingrese DNI registrado';
        msgConIng.classList.remove('text-primary');
        msgConIng.classList.add('text-danger');
    }
});  


    function grabarmov(tp, idmov,nroplaca,idconductor="",kilometraje="",ruta="") {
        if (tp=="I" || tp=="P") {
            if (idconductor=="") {
                alert("Ingrese el ID del CONDUCTOR");
                return;
            }
            if (kilometraje=="") {
                alert("Ingrese el KILOMETRAJE");
                return;
            }
            if (ruta=="") {
                alert("Ingrese la RUTA");
                return;
            }
        }

        if (tp=="I") {
            let newkilo = parseInt(document.getElementById('kilometrajeing').value || 0, 10);
            let oldkilo = parseInt(document.getElementById('kilometrajeantes').innerHTML || 0, 10);    
            if (newkilo<oldkilo) {
                alert("El KILOMETRAJE de ingreso no puede ser inferior al de salida");
                return;
            }
        }


        $.ajax({
            url: '{{ route("transporte.grabamovimiento3") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tp: tp,
                idmov: idmov,
                plac: nroplaca,
                idconductor:idconductor,
                kilometraje:kilometraje,
                ruta:ruta,
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {
                    sessionStorage.setItem('success_message', response.message);
                    window.location.href = "{{ route("transporte.movimiento3") }}";
                } else {
                    $('#modalprograma').modal('hide');
                    document.getElementById('id_conductorpro').value="";
                    document.getElementById('kilometrajepro').value="";
                    document.getElementById('rutapro').value="";
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

/*
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
*/


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