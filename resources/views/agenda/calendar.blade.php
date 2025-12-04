@extends('menu.index')

@section('content')

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Agenda</title>

    <style>
        .card-body {
            height: calc(100vh - 150px); /* Ajusta según tu layout */
            /*padding: 0 !important;       /* Evita que recorte */
        }

        #calendar {
            height: 100% !important;
        }
    </style>    
</head>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
    <div class="card-title">Agenda</div>

    <button class="btn btn-success mb-0" data-bs-toggle="modal" data-bs-target="#eventModal">
    <i class="fas fa-plus"></i> Crear evento
    </button>

    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>


<!-- MODAL BOOTSTRAP PARA CREAR EVENTO -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formCreateEvent" class="modal-content">
        @csrf
      <div class="modal-header">
        <h5 class="modal-title">Crear nuevo evento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
        <label class="form-label">Tipo</label>
        <select class="form-select" id="tipo">
            <option value="">Sin especificar</option>
            <option value="V">Control Veh&iacute;cular</option>
            <option value="R">Reuniones/Citas</option>
            <option value="I">Institucional</option>
        </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Título</label>
          <input type="text" class="form-control" id="asunto" maxlength="30" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Fecha inicio</label>
          <input type="datetime-local" class="form-control" id="fechainicio" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Fecha termino</label>
          <input type="datetime-local" class="form-control" id="fechatermino">
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Guardar</button>
      </div>
    </form>
  </div>
</div>

<body>

    <script>
        
        document.addEventListener('DOMContentLoaded', function() {

            let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                selectable: true,
    //eventColor: '#0d6efd',       // azul bootstrap
    //eventTextColor: '#000000',   // negro para mejor contraste
                events: '/manexp/public/events',
                locale: 'es',        // 👈 AQUI CAMBIA EL IDIOMA
                height: '100%',      // 👈 hace que se ajuste al contenedor
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

                select: function(info) {
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
                },

                eventDrop: function(info) {
                    fetch(`/admin/agenda/events/${info.event.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({
                            title: info.event.title,
                            start: info.event.startStr,
                            end: info.event.endStr
                        })
                    });
                },

                eventResize: function(info) {
                    fetch(`/admin/agenda/events/${info.event.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({
                            title: info.event.title,
                            start: info.event.startStr,
                            end: info.event.endStr
                        })
                    });
                },

                eventClick: function(info) {
                    if (confirm("¿Eliminar evento?")) {
                        fetch(`/admin/agenda/events/${info.event.id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrf }
                        }).then(() => calendar.refetchEvents());
                    }
                }
            });

            calendar.render();



    // --- CREAR EVENTO DESDE MODAL ---
    const form = document.getElementById('formCreateEvent');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const tipo = document.getElementById('tipo').value;
        const asunto = document.getElementById('asunto').value;
        const start = document.getElementById('fechainicio').value;
        const end   = document.getElementById('fechatermino').value;
        await fetch('/manexp/public/events', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':  '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tipo, asunto, start, end })
        });
        // Cerrar modal
        bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
        // Limpiar form
        form.reset();
        // Refrescar calendario
        calendar.refetchEvents();

    });




        });




    </script>
</body>
</html>

@endsection
