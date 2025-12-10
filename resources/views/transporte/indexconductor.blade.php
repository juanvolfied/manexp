@extends('menu.index')

@section('content')

<!--<div class="container mt-4">
    <h2 class="mb-4">Lista de Personal</h2>-->

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <div class="card">
        <div class="card-header d-flex justify-content-between">
        <div class="card-title">Conductores Registrados</div>
    <a href="{{ route('transporte.createconductor') }}" class="btn btn-primary mb-0">+ Nuevo Registro</a>
        </div>
        <div class="card-body table-responsive">


    <table id="tablapersonal" class="table table-striped table-bordered table-hover" width=100%>
        <thead class="thead-dark">
            <tr>
                <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">ID</th>
                <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Apellido Paterno</th>
                <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Apellido Materno</th>
                <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Nombres</th>
                <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Celular</th>
                <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Nro Licencia</th>
                <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Categor&iacute;a</th>
                <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Activo</th>
                <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;" colspan=2>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($conductores as $p)
                <tr>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->id_conductor }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->apellido_paterno }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->apellido_materno }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->nombres }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->nrocelular }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->nrolicencia }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->categoria }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->activo }}</td>

                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-align:center;" >
                        <a href="{{ route('transporte.editconductor', $p->id_conductor) }}"><i class="fas fa-edit fa-lg"></i><br>Editar</a>
                    </td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-align:center;">
                        <form action="{{ route('transporte.destroyconductor', $p->id_conductor) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 btn-danger" style="font-size: 11px !important;text-decoration: none;" onclick="return confirm('�Est�s seguro de eliminar este registro?')">
                                <i class="fas fa-trash-alt fa-lg"></i><br>Eliminar</button>
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
    { "orderable": false, "targets": [8, 9] }  // Evitar orden en columnas de acción si no es necesario
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