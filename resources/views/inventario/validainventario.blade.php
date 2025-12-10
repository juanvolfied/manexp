@extends('menu.index')

@section('content')

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nro Inventario Faltantes</title>
</head>

    <div class="card">
        
        <div class="card-header  d-flex justify-content-between align-items-center">
        <div class="card-title">Valida secuencia de Nro de Inventario - Identificar códigos faltantes y no correspondientes</div>
        </div>

        <div class="card-body">

        <div class="row" id="datacabe">
            <div class="col-md-4 col-lg-4 text-center">    
                <b>FALTANTES CON PREFIJO ARE</b>    
<table id="eventos" class="table table-striped table-bordered table-hover" width=100%>
    <thead class="thead-dark">
        <tr>
            <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Desde</th>
            <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Hasta</th>
        </tr>
    </thead>
    <tbody>
        @foreach($are_faltantes as $p)
            @if ($p->rangodesde == $p->rangohasta) 
                <tr class="separador">
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->rangodesde }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;"></td>
                </tr>
            @else 
                <tr>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->rangodesde }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->rangohasta }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

            </div>
            <div class="col-md-4 col-lg-4 text-center">        
                <b>FALTANTES CON PREFIJO INT25</b>    
<table id="numerosint" class="table table-striped table-bordered table-hover" width=100%>
    <thead class="thead-dark">
        <tr>
            <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Desde</th>
            <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Hasta</th>
        </tr>
    </thead>
    <tbody>
        @foreach($int_faltantes as $p)
            @if ($p->rangodesde == $p->rangohasta) 
                <tr>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->rangodesde }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;"></td>
                </tr>
            @else 
                <tr>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->rangodesde }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->rangohasta }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

            </div>
            <div class="col-md-4 col-lg-4 text-center">        
                <b>NO CORRESPONDEN</b>   

<table id="nosonareint" class="table table-striped table-bordered table-hover" width=100%>
    <thead class="thead-dark">
        <tr>
            <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Nro inventario</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nosonareint as $p)
            <tr>
                <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->nro_inventario }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

            </div>


        </div>


        </div>        
    </div>  


<body>
@push('scripts')

<script>
$(document).ready(function() {
    $('#eventos').DataTable({
"dom": '<"top"p>rt',
      "pageLength": 15,  // Número de filas por página
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

    $('#numerosint').DataTable({
"dom": '<"top"p>rt',
      "pageLength": 15,  // Número de filas por página
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

    $('#nosonareint').DataTable({
"dom": '<"top"p>rt',
      "pageLength": 15,  // Número de filas por página
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

</body>
</html>

@endsection

