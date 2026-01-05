@extends('menu.index') 

@section('content')

    <!--<h2 class="mb-4">Seguimiento de Expedientes</h2>-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Consulta de escritos por Fecha y Fiscal</div>
        </div>
        <div class="card-body table-responsive">

    <form id="form-filtros" class="row g-3" autocomplete="off">
        @csrf
        <div class="row">

        <div class="col-md-4 col-lg-4">
                <div class="form-group" style="padding:0px;">

                <label for="fiscal" class="form-label"><b>Fiscal</b></label>

                    <div class="input-group">
        <div class="flex-fill">

                <select name="fiscal" id="fiscal" class="" onchange="oculta()" >
                        <option value="">-- Seleccione --</option>
                        @foreach($fiscales as $p)
                            <option value="{{ $p->id_personal }}" {{ old('fiscal', $libroescritos->id_fiscal ?? '') == $p->id_personal ? 'selected' : '' }}>
                                {{ $p->apellido_paterno ." ". $p->apellido_materno ." ". $p->nombres }} 
                            </option>
                        @endforeach
                            </select>
        </div>                            
                    <button class="btn btn-success" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="abrirCalendario()">
                    <i class="fas fa-calendar-alt fa-lg me-1"></i></button>

                @error('fiscal') <div class="text-danger">{{ $message }}</div> @enderror

                    </div>
                </div>


<!--
            <div class="row">
                <div class="col-md-6 col-lg-6">
                <div class="form-group" style="padding:5px;">
                    <label for="nroinventario"><b>Nro Inventario:</b></label>
                    <div class="input-group">
                    <input type="text" class="form-control form-control-sm" name="nroinventario" id="nroinventario" onkeydown="buscanroinventa(event)" autofocus/>
                    
                    <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="ejecutabuscar()">
                    <i class="fas fa-check me-1"></i> Verificar
                    </button>
                    
                    </div>
                    
                </div>
                </div>
            </div>
-->            


<!--            <div class="form-group" style="padding:5px;font-size:12px; color:blue;" id="descdependencia">
                {{ isset($libroescritos) ? $libroescritos->descripcion : '' }}
            </div>-->
            <input type="hidden" id="id_dependencia" name="id_dependencia">
            <input type="hidden" id="despacho" name="despacho">
        </div>
        <div class="col-md-2">
            <label for="fechareg" class="form-label"><b>Fecha</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="fechareg" name="fechareg" onchange="oculta()" class="form-control text-center" value="{{ old('fechareg', date('Y-m-d')) }}" style="width: 120px;" >
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-center">
            <a href="#" onclick="mostrarescritos(event)" class="btn btn-primary w-100">Mostrar Escrito(s)</a>
        </div>
        <div class="col-md-2 d-flex align-items-center">
            <a href="#" onclick="generapdf(event)" class="btn btn-primary w-100" id="botonimprime" style="display:none;">Imprimir Escritos</a>
        </div>        
        <div class="col-md-2 d-flex align-items-center">
            <a href="#" onclick="verdigital()" class="btn btn-warning w-100" id="botoncargo" style="display:none;">Cargo</a>
            <input type="hidden" id="rutapdf" value="">
        </div>        

        </div> <!-- row -->

    </form>

    <div class="mt-5">
        <table id="scanned-list" class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">#</th>
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">C&oacute;digo</th>
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">Tipo</th>
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">Descripci&oacute;n</th>
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">Dependencia Origen</th>
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">Remitente</th>
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">Carpeta Fiscal</th>			      
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">Folios</th>			      
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">Ver</th>			      
                </tr>
            </thead>
            <tbody style="font-size:12px;" >
            </tbody>
        </table>        
    </div>

        </div>
    </div>
    
<!-- Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Visualizar PDF</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <iframe id="pdfFrame" src="" width="100%" height="600px" style="border: none;"></iframe>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalCalendario" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-calendar-alt"></i> CARGOS A FISCALES</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
    .card-body {
        overflow: visible !important;
        position: relative; /* asegúrate de que esté definido */
        z-index: 1;
    }

    .selectize-dropdown {
        z-index: 9999 !important; /* fuerza que se vea por encima de todo */
    }    
    /*.selectize-dropdown, .selectize-input, .selectize-input input {
        font-size: 11px!important;  
    }
    .selectize-input {
        padding: 4px 4px!important;  
    }*/    
</style>
@section('scripts')
<script>
/*  const iddependencia = @json($fiscales->pluck('id_dependencia', 'id_personal'));
  const descdependencia = @json($fiscales->pluck('descripcion', 'id_personal'));
  const despacho = @json($fiscales->pluck('despacho', 'id_personal'));

    $('#fiscal').selectize({
        onChange: function(value) {
            // Solo ejecuta la función si hay un valor seleccionado
            if (value) {
                muestradato(value);
            }
        }
    });*/

    $('#fiscal').selectize();
</script>    

<script>
function oculta() {
    const tableBody = $('#scanned-list tbody');
    tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla
    document.getElementById('botonimprime').style.display = 'none';
    document.getElementById('botoncargo').style.display = 'none';
}
function generapdf(event) {
    event.preventDefault();

    const fiscal = document.getElementById('fiscal').value;
    const fechareg = document.getElementById('fechareg').value;

    if (!fiscal || !fechareg) {
        alert("SELECCIONE FISCAL Y FECHA.");
        return;
    }

    const tabla = document.getElementById('scanned-list');
    const filas = tabla.querySelectorAll('tbody tr');
    if (filas.length > 0) {
    } else {
            alert("NO HAY ESCRITOS REGISTRADOS CON EL FISCAL O EN LA FECHA SELECCIONADA");
            return;
    }

    const basePdfUrl = @json(route('escritosfiscal.pdf', ['fiscal' => '__FISCAL__', 'fecha' => '__FECHA__']));
    const url = basePdfUrl
        .replace('__FISCAL__', encodeURIComponent(fiscal))
        .replace('__FECHA__', encodeURIComponent(fechareg));

    // Construir la URL del PDF (ajústala según cómo esté definida tu ruta Laravel)
    //const url = `/mesapartes/${encodeURIComponent(fiscal)}/${encodeURIComponent(fechareg)}/pdf`;
    if (event) event.preventDefault(); // Previene recarga    
    $('#pdfFrame').attr('src', url);
    $('#pdfModal').modal('show');

    // Asignar al iframe
//    document.getElementById('iframePDF').src = url;
    // Mostrar el modal (usando Bootstrap 5)
//    const modal = new bootstrap.Modal(document.getElementById('modalPDF'));
//    modal.show();
}

function mostrarescritos(event) {
    document.getElementById('botoncargo').style.display = 'none';
    document.getElementById('rutapdf').value = "";

    const tableBody = $('#scanned-list tbody');
    const tableBodycel = $('#scanned-listcel tbody');
    tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla
    tableBodycel.empty(); // Limpiar la tabla antes de volver a renderizarla

    if (event) event.preventDefault(); // Previene recarga
    const fiscal = document.getElementById('fiscal').value;
    const fechareg = document.getElementById('fechareg').value;
    if ( fiscal=="" ) {
        alert ("SELECCIONE FISCAL");
        return false;
    }
    if ( fechareg=="" ) {
        alert ("INGRESA LA FECH DE REGISTRO");
        return false;
    }

    $.ajax({
        url: '{{ route("mesapartes.consultadetalle") }}', 
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            fiscal: fiscal,
            fechareg: fechareg
        },
        success: function(response) {
            if (response.success) {                                
                var registros = response.registros;
                registros.forEach(function(registro, index) {                

                    const tipos = {
                    'E': 'Escrito',
                    'O': 'Oficio',
                    'S': 'Solicitud',
                    'C': 'Carta',
                    'I': 'Invitación',
                    'F': 'Informe',
                    'Z': 'OTROS'
                    };
                    const tipoTexto = tipos[registro.tipo] || registro.tipo;

                const fecha = registro.fecharegistro; // "2025-07-08 22:12:54"
                const anio = fecha.substring(0, 4);   // "2025"
                const mes  = fecha.substring(5, 7);   // "07"                    
                const codescrito = registro.codescrito;


                    const iconoDetalle = registro.existepdf
                        ? `<a href="#" onclick="mostrarDetalle('${anio}', '${mes}', '${registro.codescrito}'); return false;">
                            <i class="fas fa-search"></i>
                        </a>`
                        : `<i class="fas fa-search text-muted" title="Documento digital PDF no disponible" style="opacity: 0.5; cursor: not-allowed;"></i>`;

                    tableBody.append(`
                        <tr>
                            <td style="font-size:11px; padding: 5px 5px !important;">${index + 1}</td>
                            <td style="font-size:11px; padding: 5px 5px !important; border-left: 4px solid #ffc107 !important; font-weight: bold !important;">${registro.codescrito || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${tipoTexto || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.descripcion || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.dependenciapolicial || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.remitente || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.carpetafiscal || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.folios || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">
                            ${iconoDetalle}
                            </td>
                        </tr>
                    `);
                
                
                });
                if (response.cargodigital) {
                    document.getElementById('botoncargo').style.display = 'block';
                    document.getElementById('rutapdf').value = response.rutacargo;
                }
                document.getElementById('botonimprime').style.display = 'block';

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
                alert('HUBO UN ERROR AL CONSULTAR ESCRITOS.');
            }
        }        
    });

}


function verdigital() {
    let ruta = document.getElementById('rutapdf').value;
    let pdfUrl = `../../storage/app/mesapartescargos/${ruta}.pdf`;
    //const pdfUrl = `../../storage/app/mesapartescargos/${anio}/${mes}/${codigo.toUpperCase()}.pdf`;
    $('#pdfFrame').attr('src', pdfUrl);
    $('#pdfModal').modal('show');
}
</script>

<script>
function mostrarDetalle(anio, mes, codigo) {
    const pdfUrl = `../../storage/app/mesapartes/${anio}/${mes}/${codigo.toUpperCase()}.pdf`;
    $('#pdfFrame').attr('src', pdfUrl);
    $('#pdfModal').modal('show');
}
</script>

<style>
.fc-event-main {
    display: flex !important;
    align-items: center;
    justify-content: center;
}
</style>

<script>
let calendar;
function abrirCalendario() {
    const fiscalId = document.getElementById('fiscal').value;
    // Mostrar modal
    let modal = new bootstrap.Modal(document.getElementById('modalCalendario'));
    modal.show();
    // Esperar que el modal esté visible
    setTimeout(() => {
        if (calendar) {
            calendar.destroy();
        }
        calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 'auto',
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch(`/manexp/public/mesapartes/calendarcargos?fiscal_id=${fiscalId}`)
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => failureCallback(error));
            },
            eventContent: function(arg) {
                let icon = '';

                switch(arg.event.extendedProps.existe) {
                    case true:
                        icon = '<i class="fas fa-file-pdf fa-2x d-block text-center"></i>';
                        break;
                    case false:
                        icon = '<i class="fas fa-file-alt fa-2x d-block text-center"></i>';
                        break;
                    default:
                        icon = '<i class="fas fa-calendar"></i>';  // Por defecto
                }
                return { 
                    html: '<b>' + icon + ' ' + arg.event.title + '</b>'
                };
            },
            eventDidMount: function(info) {
                switch(info.event.extendedProps.existe) {
                    case true:
                        info.el.style.backgroundColor = '#198754';
                        textColor  = '#ffffff';
                        break;
                    case false:
                        info.el.style.backgroundColor = '#ffc107';
                        textColor  = '#000000';
                        break;
                    default:
                        //info.el.style.backgroundColor = '#0d6efd';
                        textColor  = '#000000';
                }

            // Cambiar color del texto interno
            info.el.querySelectorAll(
                '.fc-event-title, .fc-event-time, .fc-event-main'
            ).forEach(el => {
                el.style.color = textColor;
            });


            }
                        
        });
        calendar.render();
    }, 300);
}
</script>

@endsection
