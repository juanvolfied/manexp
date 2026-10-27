@extends('menu.index')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Ubicaciones por Despacho</title>    
</head>
<body>

    <form id="miFormulario" autocomplete="off">
<!--
    <div class="container mt-4">
            <div class="row">            
              <div class="col-md-12">-->
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Consulta de Ubicaciones por Despacho</div>
                  </div>
                  <div class="card-body table-responsive">

                    <div class="row mb-2">
                        <div class="col-md-6">
                                <label for="dependencia" class="form-label"><b>Dependencia: </b></label>
                                <select name="dependencia" id="dependencia" class="">
                                    <option value="">Dependencia...</option>
                                    @foreach ($dependencias as $datos)
                                    <!--<option value="{{ $datos->id_dependencia }}">{{ $datos->descripcion }}</option>	-->		    
                                    <option value="{{ $datos->id_dependencia }}" 
                                        {{ ($depend ?? -1) == $datos->id_dependencia ? 'selected' : '' }}>
                                        {{ $datos->descripcion }}
                                    </option>	                                    
                                    @endforeach
                                </select>
                        </div>

                      <div class="col-md-3 col-lg-3" >
                        <div class="form-group" style="padding:5px;">
                          <label for="despacho"><b>Despacho:</b></label>
			  <select name="despacho" id="despacho" class="form-select form-control-sm" >
			    <option value=""></option>
                @foreach(config('listas.listadespachos') as $valor => $texto)
                    <option value="{{ $valor }}" {{ (string)($despac ?? '') === (string)$valor ? 'selected' : '' }}>
                        {{ $texto }}
                    </option>
                @endforeach
			  </select>
                        </div>
                      </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <a href="#" onclick="buscadatos(event)" class="btn btn-primary w-100">
                                <i class="fas fa-arrow-right me-1"></i> Continuar</a>
                        </div>


                    </div>        



<!--        <h1 class="mb-4">Seguimiento de Registro de Inventario</h1>-->

        <!-- Tabla con clases Bootstrap -->
        <span class="d-none d-md-inline">
@auth

        @if(!empty($segdatos) && $segdatos->isNotEmpty())

        <table id="tablaseguimiento" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;" align="center">Archivo</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;" align="center">Anaquel</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;" align="center">Nro Paquete</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;" align="center">Año Carpeta</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;" align="center">Total Carpetas</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;" align="center">Fec. Inventario</th>
                    <!--<th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;" align="center">Ver Detalle</th>-->
                </tr>
            </thead>
            <tbody>
                @foreach ($segdatos as $datos)
                    <tr>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">
{{ [
    99 => 'Archivo Gestión 1',
    92 => 'Archivo Gestión 2',
    93 => 'Archivo Gestión 3',
    94 => 'Archivo Gestión 4',
][$datos->archivo] ?? 'Archivo ' . str_pad($datos->archivo, 3, '0', STR_PAD_LEFT) }}

                        </td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->anaquel}}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->nro_paquete}}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->ano_expediente}}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;" align="center">{{ $datos->total}}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;" align="center">{{ $datos->fecha_inv}}</td>
                        <!--<td style="padding: 5px 10px!important; font-size: 12px !important;" align="center">{{ $depend}} {{ $despac}}</td>-->
                    </tr>
                @endforeach
            </tbody>
        </table>
        @elseif(($tipoing ?? -1) >= 0)
            <!-- Mensaje solo si intentó consultar (tipoing >= 0) pero no hubo registros -->
            <div class="alert alert-warning" role="alert">
                No se encontraron registros para los filtros seleccionados.
            </div>
        @else
            <!-- Estado inicial cuando tipoing es -1 -->
            <div class="alert alert-info" role="alert">
                Seleccione la dependencia y despacho para realizar la consulta.
            </div>
        @endif


        </span>
        
@endauth


        
                  </div>
                </div>
<!--              </div>
            </div>
        
    </div>-->

<!-- Modal -->
<div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content custom-modal-height">
      
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel">Detalle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body" id="detalleseguimiento">
          <table id="scanned-list" class="table table-striped table-sm">
              <thead class="thead-dark">
                  <tr>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">C&oacute;digo de Barras</th>			      
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Dependencia</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">A&ntilde;o</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Nro Exp</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tipo</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tomo</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fecha Lectoreo</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fecha Inventariado</th>
                  </tr>
              </thead>
              <tbody style="font-size:12px;" >
		<!-- Los datos escaneados se irán añadiendo aquí -->
              </tbody>
          </table>        
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <!--<button type="button" class="btn btn-primary">Guardar cambios</button>-->
      </div>

    </div>
  </div>
</div>
<style>
  .custom-modal-height {
    height: 90vh; /* 90% del alto de la pantalla */
  }
  .custom-modal-height .modal-body {
    overflow-y: auto; /* Scroll interno si el contenido excede el alto */
    flex: 1 1 auto;   /* Hace que crezca en el espacio disponible */
  }
</style>


    </form>

</body>
</html>



@section('scripts')
<script>
//    $('#dependencia').selectize();
    $('#dependencia').selectize({
        dropdownParent: 'body'
    });        
</script>
<style>
.selectize-input {
font-size: 11px !important; /* Ajusta según el tamaño que necesites */
}
.selectize-dropdown .option {
    font-size: 11px !important;
}
</style>    
@endsection

<script>    
    function buscadatos() {
        let depend = document.getElementById("dependencia").value;
        let despac = document.getElementById("despacho").value;
        if (depend==""){
            alert("Seleccione la dependencia");
            return false;
        }
        if (depend=="" || despac==""){
            alert("Seleccione el despacho");
            return false;
        }
        window.location.href =
            '{{ route("consultaubicaciones") }}?depend=' + depend + '&despac=' + despac;
    }


let scannedItems = []; 

function mostrardetalle(nro_inv,event) {

scannedItems = [];
var nroreg=0;

            const tableBody = $('#scanned-list tbody');
            const tableBodycel = $('#scanned-listcel tbody');
            tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla
            tableBodycel.empty(); // Limpiar la tabla antes de volver a renderizarla

    if (event) event.preventDefault(); // Previene recarga

                    $.ajax({
                        url: '{{ route("seguimiento.detalle") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nroinventario: nro_inv
                        },
                        success: function(response) {

                           if (response.success) {
                                				
                                var registros = response.registros;
                                registros.forEach(function(registro) {
                                
                                nroreg=nroreg+1;
                            	if (nroreg==1) {
                                    // Rellenar los otros inputs con los datos del producto
                                    //$('#archivo').val(registro.archivo);
                                    //$('#nropaquete').val(registro.nro_paquete);
                                    //$('#dependencia').val(registro.paq_dependencia);
                                    //choices.setChoiceByValue(registro.paq_dependencia);
                                    //$('#despacho').val(registro.despacho);
                                }
                                var codbarras = registro.codbarras;
                                var dependencia = registro.id_dependencia;
                                var ano = registro.ano_expediente;
                                var nroexpediente = registro.nro_expediente;
                                var tipo = registro.id_tipo;
                                var estado = registro.estado;
                                if (estado=="L") {
                                    var lafecha = registro.fecha_lectura;
                                    var lahora = registro.hora_lectura;
                                }
                                if (estado=="I") {
                                    var lafecha = registro.fecha_inventario;
                                    var lahora = registro.hora_inventario;
                                }
                                
                tableBody.append(`
                    <tr>
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.codbarras}</td>
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.id_dependencia}</td>
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.ano_expediente}</td>
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.nro_expediente}</td>
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.id_tipo}</td>                        
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.tomo}</td>                        
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.fecha_lectura ?? ''} ${registro.hora_lectura ?? ''}</td>
                        <td style="font-size:12px; padding: 5px 10px !important;">${(registro.fecha_inventario ?? '') + ' ' + (registro.hora_inventario ?? '')}</td>
                    </tr>
                `);
                                
                                
                                });
                                

      var miModal = new bootstrap.Modal(document.getElementById('miModal'));
      miModal.show();
                                                                
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
                                alert('Hubo un error al buscar nro inventario.');
                            }
                        }





                        
                    });
}
</script>

@push('scripts')
<script>
  $(document).ready(function() {
    $('#tablaseguimiento').DataTable({
      "pageLength": 20,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": false,  // Habilitar búsqueda
      "ordering": true,   // Habilitar ordenación
        "order": [
            [0, 'asc'], // Ordena primero por la Columna 1 (índice 0)
            [1, 'asc']  // En caso de empate, ordena por la Columna 2 (índice 1)
        ],
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
