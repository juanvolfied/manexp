@extends('menu.index')

@section('content')
<!--<div class="container mt-4">-->
    <!--<h2 class="mb-4">Expedientes Registrados</h2>-->

    @if(session('success'))
        <div id="messageOK" class="alert alert-success">{{ session('success') }}</div>
    @else
        <div id="messageOK" class="alert alert-success" style="display:none;"></div>
    @endif

    <div class="card">
        <div class="card-header">
        <div class="card-title">Recepci&oacute;n de Gu&iacute;as de Internamiento</div>
        </div>
        <div class="card-body table-responsive">

    <table id="tablaexpedientes" class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Movimiento</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fiscal</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Estado</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha Generaci&oacute;n</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha Envio</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha Recepci&oacute;n</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;" width="155">Acciones</th>
            </tr>
        </thead>
        <tbody style="font-size:12px;">
            @foreach($guiacab as $p)
                <tr>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->tipo_mov }} {{ $p->ano_mov }}-{{ $p->nro_mov }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->estado_mov }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fechahora_movimiento }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fechahora_envio }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fechahora_recepcion }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">
                    @if($p->estado_mov == 'E')
                        <a href="{{ route('internamiento.ver', ['tipo_mov' => $p->tipo_mov, 'ano_mov' => $p->ano_mov, 'nro_mov' => $p->nro_mov] ) }}" class="btn btn-primary" style="padding: 5px 8px!important;"><b>Verifica y Recepciona</b></a>
                    @endif 
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
        
        </div>
    </div>


<!--</div>-->
@endsection
@push('scripts')
<script>
  $(document).ready(function() {
    $('#tablaexpedientes').DataTable({
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


<script>
function enviarinternamiento(xtipo,xano,xnro,event) {
    if (event) event.preventDefault(); // Previene recarga
    const tipo = xtipo;
    const ano = xano;
    const nro = xnro;
    if (confirm(`DESEA CONTINUAR CON EL ENVIO DE LA GUIA DE INTERNAMIENTO ?`)) {
        $.ajax({
            url: '{{ route("internamiento.envio") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tipo_mov: tipo,
                ano_mov: ano,
                nro_mov: nro
            },
            success: function(response) {
//                alert('Elemento eliminado correctamente.');
                window.location.href = '{{ route("internamiento.index") }}';
            },
            error: function() {
                alert('Error en proceso de envio.');
            }
        });
    }
}
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const msg = sessionStorage.getItem('successMessage');
        if (msg) {
          sessionStorage.removeItem('successMessage');

          document.getElementById('messageOK').innerHTML = msg;
          var messageOK = document.getElementById('messageOK');
          messageOK.style.opacity = '1';
          messageOK.style.display = 'block';
          setTimeout(function() {
              messageOK.style.opacity = '0';
              setTimeout(() => {
                  messageOK.style.display = 'none';
              }, 500);
          }, 3000); 

        }
    });
</script>
@endpush