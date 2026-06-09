@extends('menu.index')

@section('content')

<form autocomplete="off">
        @csrf

<div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    

@if(session('success'))
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('success') }}</b></div>
@else
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
@endif

<!--<div class="container mt-4">-->
    <!--<h2>Registrar Nuevo Expediente</h2>-->

            <div class="row" id="datacabe">            
              <div class="col-md-12" id="divensede">

                <div class="card">
                  <div class="card-header">
                    <div class="card-title">
                        PROGRAMAR SALIDA DE VEHICULO ({{ count($vehiculossede) }} Vehículos) - <i class="fas fa-calendar-alt text-primary"></i>
                        <span class="card-title text-primary" id="clock">{{ now()->format('d/m/Y H:i:s') }}</span>
                    </div>
                  </div>
                  <div class="card-body">
                    
                    <div class="row">
                        <div class="col-12">

                    <div class="d-flex flex-wrap gap-3">
                        @foreach($vehiculossede as $p)
                            <div class="card vehiculo-card" style="cursor:pointer; border: 2px solid #eab308 !important;"
                            onclick="seleccionarVehiculoPrograma(
                                '{{ $p->nroplaca }}',
                                '{{ $p->marca }} {{ $p->modelo }} {{ $p->color }}'
                            )">
                                <div class="contenedor-imagen" style="background: #f0fdf4;" >
                                    <img style="padding-top: 15px; width:70px;"
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
                    </div>



                  </div>        
                </div>

              </div>
            </div>    



<style>
.vehiculo-card{
    transition: 0.2s;
}

.vehiculo-card:hover{
    transform: scale(1.03);
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
}    
.vehiculo-card{
    width:90px;    
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

    function updateClock() {
        const now = new Date();
        const formatted =
            now.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            }) + ' ' + now.toLocaleTimeString('es-ES');

        //document.getElementById('clock').textContent = formatted;
        document.getElementById('clock').innerHTML = formatted;
    }

    updateClock();
    setInterval(updateClock, 1000); // Actualiza cada segundo

</script>
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
<div class="modal fade" id="modalelimina" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">ELIMINAR PROGRAMACION DE SALIDA DE VEHICULO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" style="font-size: 16px;">
        <div class="mb-2">
            <b>Placa : </b><span id="placaeli"></span>
        </div>
        <div class="mb-2">
            <b>🚛Vehículo : </b><span id="vehiculoeli"></span>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>👤Conductor : </b><span id="conductoreli"></span>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>⛽Kilometraje : </b><span id="kilometrajeeli"></span>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>🗺️Ruta programada : </b><span id="rutaeli"></span>
        </div>

        ⚠️ Al presionar Eliminar Registro, el vehículo pasará a SEDE y estará disponible para volver a ser programado.
        <input type="hidden" id="id_movimientoeli">
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" 
        id="programarsalida" 
        class="btn btn-primary" 
        onclick="eliminar(
        document.getElementById('id_movimientoeli').value,
        document.getElementById('placaeli').textContent
        )">
        Eliminar Registro
        </button>
      </div>
    
    </div>
  </div>
</div>
<div class="modal fade" id="modalmodifica" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">EDITAR PROGRAMACION DE VEHICULO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" style="font-size: 16px;">
        <div class="mb-2">
            <b>Placa : </b><span id="placamod"></span>
        </div>
        <div class="mb-2">
            <b>🚛Vehículo : </b><span id="vehiculomod"></span>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>👤ID Conductor : </b><input type="text" name="id_conductormod" id="id_conductormod" class="form-control" maxlength="8" style="width: 100px;" value="" placeholder="00000000" >
            <small id="msgconing" class="form-text text-muted text-primary"></small>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>⛽Kilometraje ingreso : </b><input type="text" name="kilometrajemod" id="kilometrajemod" class="form-control" maxlength="6" style="width: 100px;" value="" placeholder="000000">
        </div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <b>🗺️Ruta programada : </b><input type="text" name="rutamod" id="rutamod" class="form-control" maxlength="100" style="width: 500px;" value="">
        </div>

        ⚠️ Al confirmar, se actualizará los cambios realizados a la programación del vehículo.
        <input type="hidden" id="id_movimientomod">
      </div>      
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" 
        id="confirmaingreso" 
        class="btn btn-primary" 
        onclick="grabarmov('E',
        document.getElementById('id_movimientomod').value,
        document.getElementById('placamod').textContent,
        document.getElementById('id_conductormod').value,
        document.getElementById('kilometrajemod').value,
        document.getElementById('rutamod').value
        )">
        Grabar Cambios
        </button>
      </div>
    
    </div>
  </div>
</div>


            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">                  
                  <div class="card-header">
                    <div class="card-title">VEHICULOS PROGRAMADOS PARA SALIDA</div>
                  </div>
                  <div class="card-body">
                    <table id="scanned-list" class="table table-striped table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Vehículo</th>
                                <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Conductor</th>
                                <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Km</th>
                                <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Ruta</th>
                                <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Fecha</th>
                                <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Estado</th>
                                <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;" colspan=2>Acción</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:12px;" >
                        @foreach($movsvehiculos as $p)
                            @if($p->estado != 'S')
                            <tr>
                                <td style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">{{ $p->placa }}<br><span style="font-size:0.7rem;">{{ $p->marca }} {{ $p->modelo }} {{ $p->color }}</span></td>
                                <td style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">{{ $p->id_conductor }}<br>{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                                <td style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">{{ $p->kilometraje }}</td>
                                <td style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">{{ $p->observacion }}</td>
                                <td style="padding: 5px 5px!important; font-size: 10px !important; text-transform:none;">
                                    {!! $p->estado == 'P' ? '🕑'. $p->fechahora_programado : '🕑'. $p->fechahora_programado .'<br>'. '✅'. $p->fechahora_registro !!}
                                </td>
                                <td style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">
                                    <b>
                                    {{ $p->estado == 'P' ? '🕑 Programado' : ($p->estado == 'D' ? '✅ En diligencia' : $p->estado) }}
                                    </b>
                                </td>
                                <td style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">
                                @if($p->estado == 'D')
                                    <button type="button" disabled style="border: none; background: transparent; cursor: not-allowed;">
                                        <i class="fas fa-trash-alt fa-lg" style="color: gray;"></i>
                                    </button>
                                @else
                                    <button type="button" onclick="modaleliminarregistro(
                                    {{ $p->id_movimiento }}, 
                                    '{{ $p->placa }}','{{ $p->marca }} {{ $p->modelo }} {{ $p->color }}',
                                    '{{ $p->id_conductor }} - {{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}',
                                    '{{ $p->kilometraje }}',
                                    '{{ $p->observacion }}'
                                    )" 
                                            style="border: none; background: transparent; cursor: pointer;">
                                        <i class="fas fa-trash-alt fa-lg" style="color: red;"></i>
                                    </button>
                                @endif
                                </td>                                
                                <td style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">
                                @if($p->estado == 'D')
                                    <button type="button" disabled style="border: none; background: transparent; cursor: not-allowed;">
                                        <i class="fas fa-edit fa-lg" style="color: gray;"></i>
                                    </button>
                                @else
                                    <button type="button" onclick="seleccionarmodifica(
                                    '{{ $p->placa }}',
                                    '{{ $p->marca }} {{ $p->modelo }} {{ $p->color }}',
                                    '{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}',
                                    '{{ $p->observacion ?? '' }}',
                                    '{{ $p->kilometraje ?? '' }}',
                                    '{{ $p->fechahora_programado ?? '' }}',
                                    '{{ $p->id_conductor }}',
                                    '{{ $p->id_movimiento }}'
                                    )" 
                                            style="border: none; background: transparent; cursor: pointer;">
                                        <i class="fas fa-edit fa-lg text-primary" ></i>
                                    </button>
                                @endif
                                </td>                                
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>        



                  </div>
                </div>
              </div>
            </div>    

</form>

@endsection

@section('scripts')
<script>
var element = document.getElementById('kilometrajepro');
var maskOptions = {
  mask: '000000'
};
var mask = IMask(element, maskOptions);

var maskIdConductor = IMask(
  document.getElementById('id_conductorpro'),
  { mask: '00000000' } 
);
var maskIdConductormod = IMask(
  document.getElementById('id_conductormod'),
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
maskIdConductor.on('accept', function () {
    let idconductor = maskIdConductor.value.toUpperCase().trim();
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

function seleccionarmodifica(placa, vehiculo, conductor, ruta, kilometraje, fecha, id_conductor, id_movimiento) {
    document.getElementById('placamod').innerHTML = placa;
    document.getElementById('vehiculomod').innerHTML = vehiculo;
    //document.getElementById('id_conductormod').value = id_conductor;
    maskIdConductormod.value = id_conductor;
    //document.getElementById('msgconmod').innerHTML = conductor;
    document.getElementById('rutamod').value = ruta;
    document.getElementById('kilometrajemod').value = kilometraje;
    //document.getElementById('fechavalida').innerHTML = fecha;
    document.getElementById('id_movimientomod').value = id_movimiento;
    const myModal = new bootstrap.Modal(
        document.getElementById('modalmodifica')
    );
    myModal.show();
}
    function modaleliminarregistro(idmov,nroplaca,vehiculo,idconductor="",kilometraje="",ruta="") {        
        document.getElementById('placaeli').innerHTML = nroplaca;
        document.getElementById('vehiculoeli').innerHTML = vehiculo;
        document.getElementById('conductoreli').innerHTML = idconductor;
        document.getElementById('kilometrajeeli').innerHTML = kilometraje;
        document.getElementById('rutaeli').innerHTML = ruta;
        document.getElementById('id_movimientoeli').value = idmov;
        const myModal = new bootstrap.Modal(
            document.getElementById('modalelimina')
        );
        myModal.show();
    }
    function eliminar(idmov,nroplaca,vehiculo="",idconductor="",kilometraje="",ruta="") {        
        $.ajax({
            url: '{{ route("transporte.eliminarprogramacion") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
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
                    window.location.href = "{{ route("transporte.programarsalida") }}";
                } else {
                    $('#modalelimina').modal('hide');
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

    function grabarmov(tp, idmov,nroplaca,idconductor="",kilometraje="",ruta="") {
        if (tp=="P" || tp=="E") {
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
                    window.location.href = "{{ route("transporte.programarsalida") }}";
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


@endsection