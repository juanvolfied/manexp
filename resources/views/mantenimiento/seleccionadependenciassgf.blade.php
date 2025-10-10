@extends('menu.index')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecciona Dependencias mostradas en Registro de Carpetas SGF</title>    
</head>
<body>


    <div class="container mt-4">
            <div class="row">            
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Selecciona dependencias mostradas en Registro de Carpetas SGF</div>
                  </div>
                  <div class="card-body table-responsive">
        <table id="tablaseguimiento" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Dependencia</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Abreviado</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Ver Registro SGF</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dependencias as $datos)
                    <tr>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ $datos->descripcion}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ $datos->abreviado}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;" class="text-center">
                        <input type="checkbox" class="toggle-inventario"
                            data-id="{{ $datos->id_dependencia }}"
                            {{ $datos->mostrarsgf == 'S' ? 'checked' : '' }}>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
                  </div>
                </div>
              </div>
            </div>
        
    </div>

</body>
</html>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-inventario').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const id = this.dataset.id;
                const estado = this.checked ? 'S' : 'N';

                const urlTemplate = "{{ route('dependenciacambiaestadosgf', ['id' => '__ID__']) }}";
                const url = urlTemplate.replace('__ID__', id);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        estado: estado
                    },
                    success: function(response) {
                        console.log('Estado actualizado', response);
                        //alert('Elemento eliminado correctamente.');
                    },
                    error: function() {
                        alert('Error al actualizar.');
                        console.error(err);
                        this.checked = !this.checked;
                    }
                });

            });
        });
    });
</script>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $('#tablaseguimiento').DataTable({
      "pageLength": 20,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": true,  // Habilitar búsqueda
      "ordering": false,   // Habilitar ordenación
      "info": true,       // Mostrar información de la tabla
      "autoWidth": false,  // Ajustar automáticamente el ancho de las columnas
      "lengthChange": false,
      "language": {
            "search": "Buscar",                         // Cambia "Search" por "Buscar"
            "lengthMenu": "Mostrar _MENU_ dependencias",    // Cambia "Show entries" por "Mostrar entradas"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ dependencias", // Cambia el texto de la información
            "zeroRecords": "No se encontraron registros", // Mensaje cuando no hay resultados
            "infoEmpty": "Mostrando 0 a 0 de 0 dependencias", // Cuando la tabla está vacía
            "infoFiltered": "(filtrado de _MAX_ dependencias totales)", // Cuando hay filtros activos
      
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
