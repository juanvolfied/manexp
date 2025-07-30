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
    <title>Verificaci&oacute;n de Gu&iacute;a de Internamiento a recepcionar</title>    
  </head>
<body>
<form id="miFormulario" autocomplete="off">
    @csrf  <!-- Este campo incluir� el token CSRF autom�ticamente -->

    <!--<div class="container mt-4">-->
    @if(session('messageerr'))
        <div id="messageErr" class="alert alert-danger text-danger">{{ session('messageerr') }}</div>
    @else
        <div id="messageErr" class="alert alert-danger text-danger" style="display:none;"></div>
    @endif
    @if(session('success'))
        <div id="messageOK" class="alert alert-success">{{ session('success') }}</div>
    @else
        <div id="messageOK" class="alert alert-success" style="display:none;"></div>
    @endif
<input type='hidden' id="tipo_mov" name="tipo_mov" value="{{ $guiacab->tipo_mov }}">
<input type='hidden' id="ano_mov" name="ano_mov" value="{{ $guiacab->ano_mov }}">
<input type='hidden' id="nro_mov" name="nro_mov" value="{{ $guiacab->nro_mov }}">

            <div class="row">            
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Detalle - Gu&iacute;a de Internamiento</div>
                  </div>
                  <div class="card-body">
<!--        <h1 class="mb-4">Seguimiento de Registro de Inventario</h1>-->


                    <div class="row">
                      <div class="col-md-12 col-lg-12">
                          <table width="100%"><tr><td width="100px;"><b>Movimiento:</b></td><td id="datarch">{{ str_pad($guiacab->nro_mov, 5, '0', STR_PAD_LEFT) }}-{{ $guiacab->ano_mov }}-{{ $guiacab->tipo_mov == 'GI' ? 'I' : $guiacab->tipo_mov }}</td></tr></table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-lg-12">
                          <table width="100%"><tr><td width="100px;"><b>Fiscal:</b></td><td id="datanaq">{{ $guiacab->apellido_paterno . " " . $guiacab->apellido_materno . " " . $guiacab->nombres }}</td></tr></table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-lg-12">
                          <table width="100%"><tr><td width="100px;"><b>Dependencia:</b></td><td id="datanaq">{{ $guiacab->descripcion }}</td></tr></table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-lg-12">
                          <table width="100%"><tr><td width="100px;"><b>Despacho:</b></td><td id="datanaq">{{ numeroAOrdinal($guiacab->despacho) }} DESPACHO</td></tr></table>
                      </div>
                    </div>

        <!-- Tabla con clases Bootstrap -->
        <table id="tabladetalle" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                  <th style="padding: 5px 5px!important; font-size:12px !important; text-transform:none;" width="40">#</th>			      
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;" width="150">Nro Expediente</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Imputado</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Agraviado</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Delito</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Oficio</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Folios</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none; text-align:center;" width="120">Estado</th>
                </tr>
            </thead>
            <tbody id="tabla-codigos">
                @foreach ($segdetalle as $datos)
                    <tr data-codigo="{{ $datos->codbarras }}">
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $loop->iteration }}</td> <!-- Correlativo -->
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->id_dependencia }}-{{ $datos->ano_expediente }}-{{ $datos->nro_expediente }}-{{ $datos->id_tipo }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->imputado }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->agraviado }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->desc_delito }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->nro_oficio }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->nro_folios }}</td>                        
                      <td style="padding: 5px 5px!important; font-size: 12px !important; text-align:center;" class="estado fw-bold">
                      @if($datos->estado_mov == "R")
                        <i class="fas fa-check-circle me-1" style="color:green;"></i><span class="fw-bold " style="color:green;">Recepcionado</span>
                      @else
                        <i class="fas fa-times-circle me-1" style="color:red;"></i><span class="fw-bold " style="color:red;">Pendiente</span>
                      @endif
                      </td>                        
                    </tr>
                @endforeach
            </tbody>
        </table>

                  </div>
                  <div class="card-footer">
                    <a href="{{ route('internamiento.recepcion') }}" class="btn btn-danger">Regresar a la Pantalla Anterior</a>
                  </div>


                </div>
              </div>
            </div>

    <!--</div>-->

  </form>
</body>
</html>

@endsection
@push('scripts')
<script>
  $(document).ready(function() {
    $('#tabladetalle').DataTable({
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