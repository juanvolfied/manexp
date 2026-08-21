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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Inventario</title>    
</head>
<body>

    <form id="miFormulario" autocomplete="off">

    <div class="container mt-4">
            <div class="row">            
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Consulta de escritos por Carpeta Fiscal</div>
                  </div>
                  <div class="card-body table-responsive">

                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label for="carpetafiscal" class="form-label"><b>Carpeta Fiscal: </b></label>
                            <input type="text" name="carpetafiscal" id="carpetafiscal" class="form-control" required
                                value="{{ $carpetafiscal ??  ''  }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="#" onclick="buscadatos(event)" class="btn btn-primary w-100">
                                <i class="fas fa-arrow-right me-1"></i> Buscar Escritos</a>
                        </div>
                    </div>        



<!--        <h1 class="mb-4">Seguimiento de Registro de Inventario</h1>-->

        <!-- Tabla con clases Bootstrap -->
        <span class="d-none d-md-inline">
@auth
    @php
        $perfil = Auth::user()->perfil->descri_perfil;
    @endphp

        @if(!empty($escritosporcf) && $escritosporcf->isNotEmpty())

        <table id="tablaseguimiento" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">C&oacute;digo</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fecha</th>
                    @if(Auth::user()->personal->fiscal_asistente === "A" || Auth::user()->personal->fiscal_asistente === "C")
                        <!--<th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fiscal</th>-->
                    @endif
                        <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fiscal</th>

                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tipo</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Descripci&oacute;n</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Dependencia Origen</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Remitente</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Carpeta Fiscal</th>			      
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Folios</th>			      
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Ver</th>			      
                </tr>
            </thead>
            <tbody>
                @foreach ($escritosporcf as $datos)
                    @php
                    $tipos = [
                    'E'=> 'Escrito',
                    'O'=> 'Oficio',
                    'S'=> 'Solicitud',
                    'C'=> 'Carta',
                    'I'=> 'Invitación',
                    'F'=> 'Informe',
                    'Z'=> 'OTROS'
                    ];
                    $tipoTexto = $tipos[$datos->tipo] ?? $datos->tipo;

                    $fecha = $datos->fecharegistro; // "2025-07-08 22:12:54"
                    $anio = substr($fecha,0,4);
                    $mes  = substr($fecha,5,2);                    

                    $iconoDetalle = $datos->existepdf
                    ? '<a href="#" onclick="mostrarDetalle(\'' . $anio . '\', \'' . $mes . '\', \'' . $datos->codescrito . '\'); return false;">
                        <i class="fas fa-search"></i>
                    </a>'
                    : '<i class="fas fa-search text-muted" title="Documento digital PDF no disponible" style="opacity: 0.5; cursor: not-allowed;"></i>';
                    @endphp

                    <tr>
                    <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $datos->codescrito }}</td>
                    <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $datos->fecharegistro }}</td>
                    
                    @if(Auth::user()->personal->fiscal_asistente === "A" || Auth::user()->personal->fiscal_asistente === "C")
                        <!--<td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $datos->apellido_paterno ?? '' }} {{ $datos->apellido_materno ?? '' }} {{ $datos->nombres ?? '' }}</td>-->
                    @endif
                        <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $datos->apellido_paterno ?? '' }} {{ $datos->apellido_materno ?? '' }} {{ $datos->nombres ?? '' }}</td>

                    <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $tipoTexto }}</td>
                    <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $datos->descripcion }}</td>
                    <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $datos->dependenciapolicial }}</td>
                    <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $datos->remitente }}</td>
                    <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $datos->carpetafiscal }}</td>			      
                    <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $datos->folios }}</td>			      
                    <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{!! $iconoDetalle !!}</td>			      
                    </tr>
                @endforeach
            </tbody>
        </table>
        @elseif(($carpetafiscal ?? "") != "")
            <div class="alert alert-warning" role="alert">
                No se encontraron Escritos que contengan la carpeta {{$carpetafiscal}}.
            </div>
        @else
            <div class="alert alert-info" role="alert">
                Ingrese la Carpeta Fiscal para realizar la consulta.
            </div>
        @endif


        </span>
        

@endauth

        
                  </div>
                </div>
              </div>
            </div>
        
    </div>

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
        <iframe id="pdfViewer" width="100%" height="600px"></iframe>
      </div>
    </div>
  </div>
</div>
    


    </form>

</body>
</html>



@section('scripts')
<script>
    $('#dependencia').selectize();
</script>
<script>
function mostrarDetalle(anio, mes, codigo) {
    const pdfUrl = `../../storage/app/mesapartes/${anio}/${mes}/${codigo.toUpperCase()}.pdf`;
    $('#pdfViewer').attr('src', pdfUrl);
    $('#pdfModal').modal('show');
}
</script>
@endsection

<script>    
    function buscadatos() {
        let cf = document.getElementById("carpetafiscal").value;
        if (cf==""){
            alert("Ingrese la Carpeta Fiscal de busqueda");
            return false;
        }
        window.location.href =
            '{{ route("mesapartes.escritosporcarpetafiscal") }}?carpetafiscal='+ cf ;
    }

</script>

@push('scripts')
<script>
  $(document).ready(function() {
    $('#tablaseguimiento').DataTable({
      "pageLength": 10,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": false,  // Habilitar búsqueda
      "ordering": true,   // Habilitar ordenación
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


    $('#tablaseguimiento2').DataTable({
      "pageLength": 10,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": true,  // Habilitar búsqueda
      "ordering": true,   // Habilitar ordenación
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
</script>
@endpush


@endsection
