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


    <div class="container mt-4">
            <div class="row">            
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Seguimiento de Registro de Inventario</div>
                  </div>
                  <div class="card-body table-responsive">
<!--        <h1 class="mb-4">Seguimiento de Registro de Inventario</h1>-->

        <!-- Tabla con clases Bootstrap -->
        <span class="d-none d-md-inline">
@auth
    @php
        $perfil = Auth::user()->perfil->descri_perfil;
    @endphp

        <table id="tablaseguimiento" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
    @if(in_array($perfil, ['Admin','Archivo']))                
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Usuario</th>
    @endif

                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Nro Inventario</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Archivo</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Anaquel</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Nro Paquete</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Serie</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Dependencia</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Despacho</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Total Registros</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Fec. Inventario</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Ver Detalle</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($segdatos as $datos)
                    <tr>
    @if(in_array($perfil, ['Admin','Archivo']))                
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ $datos->usuario }}</td>
    @endif
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ $datos->nro_inventario}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">
                        {{ $datos->archivo == 99 ? 'Archivo Gestión' : 'Archivo ' . str_pad($datos->archivo, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ $datos->anaquel}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ $datos->nro_paquete}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ $datos->serie}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ $datos->descripcion}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ numeroAOrdinal($datos->despacho) }} Despacho</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;" align="center">{{ $datos->total}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;" align="center">{{ $datos->fecha_inv}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;" align="center">
                        <!--<a href="{{ route('seguimiento.detalle', ['nro_inv' => $datos->nro_inventario]) }}" title="Ver detalle">🔍</a>-->
                        <a href="#" onclick="mostrardetalle('{{ $datos->nro_inventario }}', event)" title="Ver detalle">🔍</a>                        
                        </td>
                        <!--fas fa-search-->
                    </tr>
                @endforeach
            </tbody>
        </table>
        </span>
        
        <span class="d-inline d-md-none">
        <table id="tablaseguimiento2" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
    @if(in_array($perfil, ['Admin','Archivo']))                
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Usuario</th>
    @endif
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Nro Inv.</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Total Reg.</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Fec. Inv.</th>                    
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;" align="center">Ver Detalle</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($segdatos as $datos)
                    <tr>
    @if(in_array($perfil, ['Admin','Archivo']))                
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ $datos->usuario }}</td>
    @endif
                        <td style="padding: 5px 10px!important; font-size: 11px !important;">{{ $datos->nro_inventario}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;" align="center">{{ $datos->total}}</td>
                        <td style="padding: 5px 10px!important; font-size: 11px !important;" align="center">{{ $datos->fecha_inv}}</td>                        
                        <td style="padding: 5px 10px!important; font-size: 11px !important;" align="center">
                        <a href="#" onclick="mostrardetalle('{{ $datos->nro_inventario }}', event)" title="Ver detalle">🔍</a>                        
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

@endauth

        </span>

        
                  </div>
                </div>
              </div>
            </div>
        
    </div>
<!--
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#miModal">
  Abrir Modal
</button>
-->
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



</body>
</html>




<script>

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
