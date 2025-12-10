@extends('menu.index')

@section('content')

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Agenda Vehicular</title>
</head>
            <div class="row" id="datacabe">

              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header  d-flex justify-content-between align-items-center">
    <div class="card-title">Agenda Vehicular - Programar y atender solicitudes para uso de vehículos</div>                    
    <button class="btn btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#eventModal">
    Agendar Diligencia para Vehículo
    </button>                        
                  </div>

                  <div class="card-body">
                    <ul class="nav nav-tabs card-header-tabs" id="tabsCard" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1"
                                type="button" role="tab" aria-controls="tab1" aria-selected="true">
                                <b>DILIGENCIAS DE VEHICULOS AGENDADAS Y SOLICITADAS</b>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2"
                                type="button" role="tab" aria-controls="tab2" aria-selected="false">
                                <b>FECHAS AGENDADAS PARA USO DE VEHICULOS</b>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                            <!--<h5 align="center" class="text-primary"><b>LAS SOLICITUDES NUEVAS SERAN EVALUADAS ANTES DE SER AGENDADAS</b></h5>-->
            <table id="eventos" class="table table-striped table-bordered table-hover" width=100%>
                <thead class="thead-dark">
                    <tr>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fiscal</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Asunto</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Detalle</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha Termino</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Conductor Designado</th>

                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Estado</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Acci&oacute;n</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agenda as $p)
                        <tr>
                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->asunto }}</td>
                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->detalle }}</td>
                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->fechahora_inicia }}</td>
                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->fechahora_termina }}</td>
                            <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->conductor }} - {{ $p->nrocelular }}</td>
                            
<td 
    style="
        padding: 5px 5px!important; 
        font-size: 11px !important;
        background-color: {{ $p->estado === 'A' ? 'green' : ($p->estado === 'S' ? 'orange' : 'transparent') }};
        color: white;
    "
><b>
    {{ $p->estado === 'S' ? 'Solicitado' : ($p->estado === 'A' ? 'Agendado' : $p->estado) }}
</b></td>
                            <td style="padding: 5px 5px!important; font-size: 12px !important; text-align:center;">
                            @if($p->estado == 'S')
                                <a href="#" 
                                onclick="aprobar(this)"
                                data-id="{{ $p->id_evento }}"
                                data-nombre="{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}"
                                data-asunto="{{ $p->asunto }}"
                                data-detalle="{{ $p->detalle }}"
                                data-inicio="{{ $p->fechahora_inicia }}"                                                                
                                data-bs-toggle="tooltip" title="Aprobar y agendar uso de vehículo" style="color: green;"><i class="fas fa-check-circle fa-lg"></i><br>Agendar</a>
                            @else
                                <a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fas fa-check-circle fa-lg text-muted" ></i><br>Agendar</a>
                            @endif 
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                            <div id="calendar"></div>
                        </div>
                    </div>


                  </div>        
                </div>
              </div>
            </div>




<!-- Modal -->
<div class="modal fade" id="eventsModal" tabindex="-1" aria-labelledby="eventsModalLabel" aria-hidden="true">
  <div class="modal-dialog " style="max-width: 75vw; width: 75vw;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventsModalLabel">Eventos del día</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered" id="eventsTable">
          <thead class="thead-dark">
            <tr>
              <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none; font-weight: normal;">Asunto</th>
              <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none; font-weight: normal;">Detalle</th>
              <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none; font-weight: normal;">Fiscal</th>
              <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none; font-weight: normal;">Inicio</th>
              <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none; font-weight: normal;">Fin</th>
            </tr>
          </thead>
          <tbody>
            <!-- Aquí se insertarán los eventos -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>



<!-- MODAL BOOTSTRAP PARA CREAR EVENTO -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 65vw; width: 65vw;">
    <form id="formCreateEvent" class="modal-content" autocomplete=off>
        @csrf
      <div class="modal-header">
        <h5 class="modal-title">Agendar diligencia para vehículo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row mb-3" >
            <div class="col-md-4 col-lg-4" >
            <div class="form-group" style="padding:0px;">
                <label for="fiscal"><b>Fiscal:</b></label>
                <select name="fiscal" id="fiscal" class="form-select" data-live-search="true" required>
                    <option value="">Seleccione...</option>
                    @foreach ($personal as $fiscal)
                    <option value="{{ $fiscal->id_personal }}" >
                        {{ $fiscal->apellido_paterno }} {{ $fiscal->apellido_materno }} {{ $fiscal->nombres }}
                    </option>			    
                    @endforeach
                </select>
            </div>
            </div>            
            <div class="col-md-3 col-lg-3">
            <div class="form-group" style="padding:0px;">
                <label for="asunto"><b>Asunto/Motivo:</b></label>
                <select name="asunto" id="asunto" class="form-select" data-live-search="true" required>
                    <option value="">Seleccione...</option>
                    <option value="Inspección Fiscal">Inspección Fiscal</option>
                    <option value="Allanamiento y descerraje">Allanamiento y descerraje</option>
                    <option value="Operativo inopinado">Operativo inopinado</option>
                    <option value="Levantamiento de cadáver">Levantamiento de cadáver</option>
                    <option value="Constatación Fiscal">Constatación Fiscal</option>
                    <option value="Constatación domiciliaria">Constatación domiciliaria</option>
                    <option value="Toma de muestra en Laboratorio de IML">Toma de muestra en Laboratorio de IML</option>
                    <option value="PNP Alta tecnología DIVINDAT">PNP Alta tecnología DIVINDAT</option>
                    <option value="Pericia Psicológica">Pericia Psicológica</option>           
                    <option value="Otro">Otro</option>
                </select>
            </div>

            </div>

        </div>
        <div class="row mb-3" >
            <div class="col-md-12 col-lg-12">
            <label for="detalle"><b>Detalle de la diligencia</b></label>
            <input type="text" class="form-control" name="detalle" id="detalle" maxlength="100">
            </div>

        </div>
        <div class="row mb-3" >
            <div class="col-md-2 col-lg-2">
            <label for="fechainicio"><b>Fecha inicio</b></label>
            <input type="date" class="form-control" name="fechainicio" id="fechainicio" required>
            </div>
            <div class="col-md-2 col-lg-2">
            <label for="horainicio"><b>Hora inicio</b></label>
            <input type="time" class="form-control" name="horainicio" id="horainicio" required>
            </div>
        </div>
        <div class="row mb-3" >

            <div class="col-md-2 col-lg-2">
            <label for="fechatermino"><b>Fecha termino</b></label>
            <input type="date" class="form-control" name="fechatermino" id="fechatermino">
            </div>
            <div class="col-md-2 col-lg-2">
            <label for="horatermino"><b>Hora termino</b></label>
            <input type="time" class="form-control" name="horatermino" id="horatermino">
            </div>
        </div><hr style="height: 3px; background-color: #222; border: none;">
        
        <div class="row mb-3" >
            <div class="col-md-4 col-lg-4">
            <div class="form-group" style="padding:0px;">
                <label for="conductor"><b>Conductor Designado:</b></label>
                <select name="conductor" id="conductor" class="form-select" data-live-search="true" required>
                    <option value="">Seleccione...</option>
                    @foreach ($conductores as $cond)
                    <option value="{{ $cond->id_conductor }}" >
                        {{ $cond->apellido_paterno }} {{ $cond->apellido_materno }} {{ $cond->nombres }}
                    </option>			    
                    @endforeach
                </select>
            </div>
            </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Generar y Enviar Solicitud</button>
      </div>
    </form>
  </div>
</div>



<!-- MODAL BOOTSTRAP PARA CREAR EVENTO -->
<div class="modal fade" id="eventModal2" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md" >
    <form id="formCreateEvent2" class="modal-content" autocomplete="off">
        @csrf

      <div class="modal-header">
        <h5 class="modal-title">Agendar Solicitud de Vehículo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-12 col-lg-12">
            <table width=100%>
            <tr><td><b>FISCAL:</b></td><td><span id="fis"></span></td></tr>
            <tr><td><b>ASUNTO:</b></td><td><span id="asu"></span></td></tr>
            <tr><td><b>DETALLE:</b></td><td><span id="det"></span></td></tr>
            <tr><td><b>FECHA:</b></td><td><span id="fec"></span></td></tr>
            </tr></table>
            <hr>
            <div class="form-group" style="padding:0px;">
              <label for="conductor2"><b>Conductor Designado:</b></label>
              <select name="conductor2" id="conductor2" class="form-select" data-live-search="true" required>
                <option value="">Seleccione...</option>
                @foreach ($conductores as $cond)
                <option value="{{ $cond->id_conductor }}">
                    {{ $cond->apellido_paterno }} {{ $cond->apellido_materno }} {{ $cond->nombres }}
                </option>
                @endforeach
              </select>
            </div>
          </div>
        </div> <!-- cierre row -->

        <input type="hidden" id="idevento">
      </div> <!-- cierre modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" onclick="grabaaprobacion()">Aceptar y Agendar Solicitud</button>
      </div>

    </form> <!-- cierre form -->
  </div>
</div>


<body>






@push('scripts')
<script>
  $(document).ready(function() {
    $('#eventos').DataTable({

      "pageLength": 10,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": false,  // Habilitar búsqueda
      "ordering": false,   // Habilitar ordenación
      "info": true,       // Mostrar información de la tabla
      "autoWidth": false,  // Ajustar automáticamente el ancho de las columnas
      "lengthChange": false,
      "language": {
            "search": "Buscar",                         // Cambia "Search" por "Buscar"
            "lengthMenu": "Mostrar _MENU_ eventos",    // Cambia "Show entries" por "Mostrar entradas"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ eventos", // Cambia el texto de la información
            "zeroRecords": "No se encontraron registros", // Mensaje cuando no hay resultados
            "infoEmpty": "Mostrando 0 a 0 de 0 eventos", // Cuando la tabla está vacía
            "infoFiltered": "(filtrado de _MAX_ eventos totales)", // Cuando hay filtros activos
      
            // Personaliza "Previous" y "Next" en la paginación
            "paginate": {
              "previous": "Anterior",   // Cambia "Previous" por "Anterior"
              "next": "Siguiente"       // Cambia "Next" por "Siguiente"
            },
      
            // Personaliza el texto de "Showing entries"
            "emptyTable": "No hay datos disponibles en la tabla", // Mensaje si no hay datos
      }      
    });
  });
</script>
@endpush

    <script>
        
        document.addEventListener('DOMContentLoaded', function() {


            let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                selectable: true,
    //eventColor: '#0d6efd',       // azul bootstrap
    //eventTextColor: '#000000',   // negro para mejor contraste
                events: '/manexp/public/events?tipo=V',
                locale: 'es',        // 👈 AQUI CAMBIA EL IDIOMA
                //height: '100%',      // 👈 hace que se ajuste al contenedor
                height: 'auto',
                contentHeight: '100%',
                expandRows: true,    // 👈 evita recortes
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },

                // --- Personalizar el contenido del evento con iconos ---
    eventContent: function(arg) {
        let icon = '';

        switch(arg.event.extendedProps.tipo) {
            case 'V':
                icon = '<i class="fas fa-car"></i>';       // Vehículo
                break;
            case 'R':
                icon = '<i class="fas fa-users"></i>'; // Reunión/Cita
                break;
            case 'I':
                icon = '<i class="fas fa-building"></i>';  // Institucional
                break;
            default:
                icon = '<i class="fas fa-calendar"></i>';  // Por defecto
        }

        return { 
            html: '<b>' + icon + ' &nbsp;' + arg.event.title  + '</b>'
        };
    },
    eventDidMount: function(info) {
        switch(info.event.extendedProps.tipo) {
            case 'V':
                info.el.style.backgroundColor = '#198754';
                info.el.style.color = '#ffffff';
                break;
            case 'R':
                info.el.style.backgroundColor = '#ffc107';
                info.el.style.color = '#000000';
                break;
            case 'I':
                info.el.style.backgroundColor = '#0d6efd';
                info.el.style.color = '#ffffff';
                break;
            default:
                //info.el.style.backgroundColor = '#0d6efd';
                info.el.style.color = '#000000';
        }
    },

        dateClick: function(info) {
            // info.dateStr es YYYY-MM-DD
            var dateClicked = info.dateStr;

            // Filtrar eventos del día
            var eventsOfDay = calendar.getEvents().filter(function(event) {
                // FullCalendar devuelve start y end como objetos Date
                // Convertimos start a YYYY-MM-DD
                var startDate = event.start.toISOString().split('T')[0];
                return startDate === dateClicked;
            });

            if (eventsOfDay.length === 0) {
                return; // No hay eventos, no hacemos nada
            }

            // Limpiar tabla
            var tbody = document.querySelector('#eventsTable tbody');
            tbody.innerHTML = '';

            // Agregar filas
            eventsOfDay.forEach(function(e) {
                var tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none; font-weight: normal;">${e.title}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none; font-weight: normal;">${e.extendedProps.detalle || ''}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none; font-weight: normal;">${e.extendedProps.fiscal || ''}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none; font-weight: normal;">${e.start.toLocaleString()}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none; font-weight: normal;">${e.end ? e.end.toLocaleString() : ''}</td>
                `;
                tbody.appendChild(tr);
            });
            var modalTitle = document.getElementById('eventsModalLabel');
            modalTitle.textContent = `Diligencias con vehículo agendados para el día ${dateClicked}`;
            // Abrir modal usando Bootstrap 5
            var eventsModal = new bootstrap.Modal(document.getElementById('eventsModal'));
            eventsModal.show();
        },    
                ccselect: function(info) {
                    let title = prompt('Título del evento:');
                    if (title) {
                        fetch('/manexp/public/events', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content
                            },
                            body: JSON.stringify({
                                title: title,
                                start: info.startStr,
                                end: info.endStr,
                                type: info.tipo
                            })
                        }).then(() => calendar.refetchEvents());
                    }
                }

            });

            calendar.render();

    // cuando el tab se muestre, recalculamos tamaños
document.getElementById('tab2-tab')
    .addEventListener('shown.bs.tab', function () {
        calendar.updateSize();
    });



    // --- CREAR EVENTO DESDE MODAL ---
    const form = document.getElementById('formCreateEvent');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (document.getElementById('fiscal').value=="") {
            alert("Seleccione el fiscal");
            return false;
        }
        if (document.getElementById('asunto').value=="") {
            alert("Ingrese el Asunto/Motivo de la diligencia");
            return false;
        }
        if (document.getElementById('fechainicio').value=="") {
            alert("Ingrese la fecha de la diligencia");
            return false;
        }
        if (document.getElementById('horainicio').value=="") {
            alert("Ingrese la hora");
            return false;
        }
        if (document.getElementById('conductor').value=="") {
            alert("Seleccione el conductor designado");
            return false;
        }


        const tipo = "V";
        const fiscal = document.getElementById('fiscal').value;
        const asunto = document.getElementById('asunto').value;
        const detalle = document.getElementById('detalle').value;
        const start = document.getElementById('fechainicio').value;
        const end   = document.getElementById('fechatermino').value;
        const hstart = document.getElementById('horainicio').value;
        const hend = document.getElementById('horainicio').value;
        const conductor = document.getElementById('conductor').value;
        const response = await fetch('/manexp/public/grabaragendavehicular', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':  '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tipo, fiscal, asunto, detalle, start, end, hstart, hend, conductor })
        });
        const data = await response.json(); // ← IMPORTANTE

        if (data.success) {
            window.location.reload();
        } else {
            alert('Error al guardar la solicitud');
        }        
        
        // Cerrar modal
        //bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
        // Limpiar form
        //form.reset();
        // Refrescar calendario
        //calendar.refetchEvents();

    });




        });


        function aprobar(el) {
            var idevento = el.dataset.id;
            var nombre   = el.dataset.nombre;
            var asunto   = el.dataset.asunto;
            var detalle  = el.dataset.detalle;
            var inicio   = el.dataset.inicio;
            var termino  = el.dataset.termino;            

            document.getElementById('idevento').value=idevento;
            document.getElementById('fis').innerHTML=nombre;
            document.getElementById('asu').innerHTML=asunto;
            document.getElementById('det').innerHTML=detalle;
            document.getElementById('fec').innerHTML=inicio;
            var miModal2 = new bootstrap.Modal(document.getElementById('eventModal2'));
            miModal2.show();
        }
        function grabaaprobacion() {
            if (document.getElementById('conductor2').value=="") {
                alert("SELECCIONE AL CONDUCTOR DESIGNADO");
                return false;
            }
            var miModal2 = new bootstrap.Modal(document.getElementById('eventModal2'));
            miModal2.hide();

            idevento=document.getElementById('idevento').value;
            idconductor=document.getElementById('conductor2').value;
            $.ajax({
                url: '{{ route("agenda.grabaraprueba") }}', 
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idevento: idevento,
                    idconductor: idconductor,
                },
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    }
                },
                error: function() {
                    alert('Error en proceso de grabacion.');
                }
            });
        }

    </script>
</body>
</html>

@endsection
@section('scripts')
<script>
//    $('#fiscal').selectize();
</script>
@endsection
