@extends('menu.index')

@section('content')
@php
function numeroAOrdinal($numero) {
    $ordinales = [0 => '',1 => '1er',2 => '2do',3 => '3er',4 => '4to',5 => '5to',6 => '6to',7 => '7mo',8 => '8vo',9 => '9no',10 => '10mo',11 => '11er',];
    return $ordinales[$numero] ?? $numero . 'º';
}
@endphp
<form id="miFormulario" autocomplete="off">
    @csrf  <!-- Este campo incluir� el token CSRF autom�ticamente -->

<!--<div class="container mt-4">-->
    <!--<h2 class="mb-4">Expedientes Registrados</h2>-->

    @if(session('success'))
        <div id="messageOK" class="alert alert-success">{{ session('success') }}</div>
    @else
        <div id="messageOK" class="alert alert-success" style="display:none;"></div>
    @endif

    <a href="{{ route('recaudacion.registrodatos') }}" class="btn btn-primary mb-3">+ Nuevo Registro</a>
    <div class="card">
        <div class="card-header">
        <div class="card-title">Recaudaci&oacute;n: Carpetas Registradas</div>
        </div>
        <div class="card-body table-responsive">

    <table id="tablaexpedientes" class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">ID Dependencia<br>Carpeta</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">A&ntilde;o</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Nro Exp</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Tipo</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Carpeta</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Dependencia</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Despacho</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Voucher?</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Monto</th>
            </tr>
          </thead>
        <tbody style="font-size:12px;">
            @foreach($recauda as $p)
                <tr>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->id_dependencia }} </td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->ano_expediente }} </td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->nro_expediente }} </td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->id_tipo }} </td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->carpeta }} </td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->abreviado }} </td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ numeroAOrdinal($p->despacho) }} DESPACHO</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fecharegistro }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->voucher }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;" align='right'>{{ $p->monto }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
        
        </div>
    </div>

</form>


<!--</div>-->
@endsection
@push('scripts')

<script>
  $(document).ready(function() {
    $('#tablaexpedientes').DataTable({

      "pageLength": 10,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": false,  // Habilitar búsqueda
      "ordering": false,   // Habilitar ordenación
      "info": true,       // Mostrar información de la tabla
      "autoWidth": false,  // Ajustar automáticamente el ancho de las columnas
      "lengthChange": false,
      "language": {
            "search": "Buscar",                         // Cambia "Search" por "Buscar"
            "lengthMenu": "Mostrar _MENU_ carpetas",    // Cambia "Show entries" por "Mostrar entradas"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ carpetas", // Cambia el texto de la información
            "zeroRecords": "No se encontraron registros", // Mensaje cuando no hay resultados
            "infoEmpty": "Mostrando 0 a 0 de 0 carpetas", // Cuando la tabla está vacía
            "infoFiltered": "(filtrado de _MAX_ carpetas totales)", // Cuando hay filtros activos
      
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



<script>
window.onload = function() {
    var messageErr = document.getElementById('messageErr');
    var messageOK = document.getElementById('messageOK');
    if (messageErr) {
        setTimeout(function() {
            messageErr.style.opacity = '0';
            setTimeout(() => {
                messageErr.style.display = 'none';
            }, 500);
        }, 3000); 
    }
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