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
    return $ordinales[$numero] ?? $numero . '';
}
?>

<!--<div class="container mt-4">
    <h2 class="mb-4">Lista de Personal</h2>-->

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('personal.create') }}" class="btn btn-primary mb-3">+ Nuevo Registro</a>

    <div class="card">
        <div class="card-header">
        <div class="card-title">Lista de Personal</div>
        </div>
        <div class="card-body table-responsive">


    <table id="tablapersonal" class="table table-striped table-bordered table-hover" width=100%>
        <thead class="thead-dark">
            <tr>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">ID</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Apellido Paterno</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Apellido Materno</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Nombres</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Dependencia</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Despacho</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Activo</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($personal as $p)
                <tr>
                    <td style="padding: 5px 5px!important; font-size: 12px !important;">{{ $p->id_personal }}</td>
                    <td style="padding: 5px 5px!important; font-size: 12px !important;">{{ $p->apellido_paterno }}</td>
                    <td style="padding: 5px 5px!important; font-size: 12px !important;">{{ $p->apellido_materno }}</td>
                    <td style="padding: 5px 5px!important; font-size: 12px !important;">{{ $p->nombres }}</td>
                    <td style="padding: 5px 5px!important; font-size: 12px !important;">{{ $p->descripcion }}</td>
                    <td style="padding: 5px 5px!important; font-size: 12px !important;">{{ numeroAOrdinal($p->despacho) }} DESPACHO</td>
                    <td style="padding: 5px 5px!important; font-size: 12px !important;">{{ $p->activo }}</td>
                    <td style="padding: 5px 5px!important; font-size: 12px !important;">
                        <a href="{{ route('personal.edit', $p->id_personal) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('personal.destroy', $p->id_personal) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('�Est�s seguro de eliminar este registro?')">Eliminar</button>
                        </form>
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
    $('#tablapersonal').DataTable({
  "columnDefs": [
    { "orderable": false, "targets": [7] }  // Evitar orden en columnas de acción si no es necesario
  ],
        "pageLength": 20,  // Número de filas por página
        "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
        "searching": true,  // Habilitar búsqueda
        "ordering": true,   // Habilitar ordenación
    order: [[1, 'asc']], 
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