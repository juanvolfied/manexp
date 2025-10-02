@extends('menu.index') 

@section('content')
<?php
function numeroAOrdinal($numero) {
    $ordinales = [
        0 => '',
        1 => '1er',
        2 => '2do',
        3 => '3er',
        4 => '4to',
        5 => '5to',
        6 => '6to',
        7 => '7mo',
        8 => '8vo',
        9 => '9no',
        10 => '10mo',
        11 => '11er',
    ];    
    return $ordinales[$numero] ?? $numero . 'º';
}
?>
<form id="form-filtros" action="{{ route('mesapartes.consultafiltrosdetalle') }}" method="POST" class="row g-3" autocomplete="off">
    @csrf
    <div class="card " style="margin-bottom:5px;">
        <div class="card-header">
            <div class="card-title">Consulta de escritos por C&oacute;digo / Descripci&oacute;n / Remitente</div>
        </div>            
        <div class="card-body">
            <div class="row g-3 align-items-center">

                <!-- Filtro 1 -->
                <div class="col-md-4">
                    <label class="form-label"><b>Filtro por c&oacute;digo</b></label>
                    <div class="input-group">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" id="check1" style="border: 2px solid #007bff; box-shadow: 0 0 3px rgba(0,123,255,0.5);" {{ !empty($codigo) ? 'checked' : '' }}>
                        </div>
                        <input type="text" class="form-control" name="codigo" id="codigo" value="{{ $codigo ?? '' }}" placeholder="Ingrese c&oacute;digo" {{ empty($codigo) ? 'disabled' : '' }}>
                    </div>
                </div>

                <!-- Filtro 2 -->
                <div class="col-md-4">
                    <label class="form-label"><b>Filtro por descripci&oacute;n</b></label>
                    <div class="input-group">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" id="check2" style="border: 2px solid #007bff; box-shadow: 0 0 3px rgba(0,123,255,0.5);" {{ !empty($descripcion) ? 'checked' : '' }}>
                        </div>
                        <input type="text" class="form-control" name="descripcion" id="descripcion" value="{{ $descripcion ?? '' }}" placeholder="Ingrese descripci&oacute;n" {{ empty($descripcion) ? 'disabled' : '' }}>
                    </div>
                </div>

            </div>
            <div class="row g-3 align-items-center">

                <!-- Filtro 4 -->
                <div class="col-md-4">
                    <label class="form-label"><b>Filtro por dependencia origen</b></label>
                    <div class="input-group">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" id="check4" style="border: 2px solid #007bff; box-shadow: 0 0 3px rgba(0,123,255,0.5);" {{ !empty($dependenciapolicial) ? 'checked' : '' }}>
                        </div>
                        <input type="text" class="form-control" name="dependenciapolicial" id="dependenciapolicial" value="{{ $dependenciapolicial ?? '' }}" placeholder="Ingrese dependencia origen" {{ empty($dependenciapolicial) ? 'disabled' : '' }}>
                    </div>
                </div>

                <!-- Filtro 3 -->
                <div class="col-md-4">
                    <label class="form-label"><b>Filtro por remitente</b></label>
                    <div class="input-group">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" id="check3" style="border: 2px solid #007bff; box-shadow: 0 0 3px rgba(0,123,255,0.5);" {{ !empty($remitente) ? 'checked' : '' }}>
                        </div>
                        <input type="text" class="form-control" name="remitente" id="remitente" value="{{ $remitente ?? '' }}" placeholder="Ingrese remitente" {{ empty($remitente) ? 'disabled' : '' }}>
                    </div>
                </div>

            <!-- Botón -->
            <div class="col-md-4 mt-4 text-end">
                <a href="#" onclick="mostrarescritos(event)" class="btn btn-primary">Mostrar Escritos</a>
            </div>

            </div>


        </div>
    </div>

    @if(isset($segdetalle) && count($segdetalle) > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">

            <table id="datos" class="table table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">#</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">C&oacute;digo</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Dependencia</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Despacho</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fiscal</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Tipo</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Descripci&oacute;n</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Dependencia<br>Origen</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Remitente</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Carpeta<br>Fiscal</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Folios</th>
                        <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Usuario</th>
                        <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">Ver</th>			      
                    </tr>
                </thead>
                <tbody style="font-size:11px;" >
                @php
                    $tipos = [
                        'E' => 'Escrito',
                        'O' => 'Oficio',
                        'S' => 'Solicitud',
                        'C' => 'Carta',
                        'I' => 'Invitación',
                        'F' => 'Informe',
                        'Z' => 'OTROS'
                    ];
                @endphp
                @foreach($segdetalle as $index => $item)
                @php
                    $fecha = $item->fecharegistro;
                    $anio = substr($fecha, 0, 4);
                    $mes  = substr($fecha, 5, 2);
                    $codescrito = $item->codescrito;                
                    $tipoTexto = $tipos[$item->tipo] ?? $item->tipo;
                    $iconoDetalle = $item->existepdf
                        ? '<a href="#" onclick="mostrarDetalle(\'' . $anio . '\', \'' . $mes . '\', \'' . $codescrito . '\'); return false;">
                                <i class="fas fa-search"></i>
                        </a>'
                        : '<i class="fas fa-search text-muted" title="Documento digital PDF no disponible" style="opacity: 0.5; cursor: not-allowed;"></i>';

                @endphp                
                    <tr>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $index + 1 }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->fecharegistro }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none; border-left: 4px solid #ffc107 !important; font-weight: bold !important;">{{ $item->codescrito }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->abreviado }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ numeroAOrdinal($item->despacho) }} DESPACHO</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->apellido_paterno }} {{ $item->apellido_materno }} {{ $item->nombres }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $tipoTexto }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->descripcionescrito }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->dependenciapolicial }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->remitente }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->carpetafiscal }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->folios }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->usuario }}</td>
                        <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{!! $iconoDetalle !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>        



                </div><!--table responsive-->
            </div>
        </div>
    @else
        @if(isset($segdetalle) && count($segdetalle) == 0)
        <div class="card">
            <div class="card-body">
                        <b>NO SE ENCONTRARON REGISTROS CON LOS DATOS PROPORCIONADOS</b>
            </div>
        </div>
        @endif
    @endif
</form>

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
        <iframe id="pdfViewer" src="" width="100%" height="600px" style="border: none;"></iframe>
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
</style>
@section('scripts')
<script>
$(document).ready(function() {
    $('#datos').DataTable({
  "columnDefs": [
    { "orderable": false, "targets": [0,13] }  // Evitar orden en columnas de acción si no es necesario
  ],
        "pageLength": 20,  // Número de filas por página
        "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
        "searching": true,  // Habilitar búsqueda
        "ordering": true,   // Habilitar ordenación
    order: [[2, 'asc']], 
        "info": true,       // Mostrar información de la tabla
        "autoWidth": false,  // Ajustar automáticamente el ancho de las columnas
        "lengthChange": false,
        "language": {
            "search": "Buscar",                         // Cambia "Search" por "Buscar"
            "lengthMenu": "Mostrar _MENU_ entradas",    // Cambia "Show entries" por "Mostrar entradas"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas", // Cambia el texto de la información
            "zeroRecords": "No se encontraron registros", // Mensaje cuando no hay resultados
            "infoEmpty": "Mostrando 0 a 0 de 0 entradas", // Cuando la tabla está vacía
            "infoFiltered": "(filtrado de _MAX_ entradas totales)", // Cuando hay filtros activos
        
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


    function toggleInput(checkboxId, inputId) {
        const checkbox = document.getElementById(checkboxId);
        const input = document.getElementById(inputId);

        checkbox.addEventListener('change', function () {
            input.disabled = !this.checked;
            if (!this.checked) {
                input.value = ''; // Limpiar el input si se desactiva
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleInput('check1', 'codigo');
        toggleInput('check2', 'descripcion');
        toggleInput('check3', 'remitente');
        toggleInput('check4', 'dependenciapolicial');
    });
</script>


<script>
function generapdf(event) {
    event.preventDefault();

    const fiscal = document.getElementById('fiscal').value;
    const fechareg = document.getElementById('fechareg').value;

    if (!fiscal || !fechareg) {
        alert("SELECCIONE FISCAL Y FECHA.");
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
    if (event) event.preventDefault(); // Previene recarga

    if (!$('#check1').is(':checked') && !$('#check2').is(':checked') && !$('#check3').is(':checked') && !$('#check4').is(':checked')) {
        alert("SELECCIONE E INGRESE EL DATO POR EL CUAL CONSULTAR");
        return false;
    }
    document.getElementById('form-filtros').submit();
}

function numeroAOrdinal(numero) {
    const ordinales = {
        1: '1er',
        2: '2do',
        3: '3er',
        4: '4to',
        5: '5to',
        6: '6to',
        7: '7mo',
        8: '8vo',
        9: '9no',
        10: '10mo',
        11: '11er'
    };

    return ordinales[numero] || numero + ' ';
}
</script>

<script>
function mostrarDetalle(anio, mes, codigo) {
    const pdfUrl = `../../storage/app/mesapartes/${anio}/${mes}/${codigo.toUpperCase()}.pdf`;
    $('#pdfViewer').attr('src', pdfUrl);
    $('#pdfModal').modal('show');
}
</script>
@endsection
