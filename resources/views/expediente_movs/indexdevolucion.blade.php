@extends('menu.index')

@section('content')
@php
//    $estados = ['G' => 'Generado', 'E' => 'Enviado', 'R' => 'Recepcionado', 'Z' => 'RECHAZADO'];
    $estados = ['G' => 'Generado', 'E' => 'Enviado', 'D' => 'Devuelto'];

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

    <a href="{{ route('devolucion.create') }}" class="btn btn-primary mb-3">+ Nuevo Mvto Devoluci&oacute;n de Carpetas</a>
    <div class="card">
        <div class="card-header">
        <div class="card-title">Devoluci&oacute;n de Carpetas Fiscales</div>
        </div>
        <div class="card-body table-responsive">

    <table id="tablaexpedientes" class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Movimiento</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fiscal</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Dependencia</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Despacho</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Cant<br>Exp</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Estado</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha<br>Generada</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha<br>Envio&nbsp;CF</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha<br>Recepci&oacute;n</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none; text-align:center;" colspan=2>Acciones</th>
            </tr>
          </thead>
        <tbody style="font-size:12px;">
            @foreach($guiacab as $p)
                <tr>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ str_pad($p->nro_mov, 5, '0', STR_PAD_LEFT) }}-{{ $p->ano_mov }}-{{ $p->tipo_mov == 'GI' ? 'I' : $p->tipo_mov }} </td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->abreviado }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ numeroAOrdinal($p->despacho) }} DESPACHO</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->cantidad_exp }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important; {{ $p->estado_mov == 'E' ? 'color:blue;' : ($p->estado_mov == 'D' ? 'color:green;' : '') }}"><b>{{ $estados[$p->estado_mov] ?? $p->estado_mov }}</b></td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fechahora_movimiento }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fechahora_envio }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fechahora_recepcion }}</td>
                    
                    <!--<td style="padding: 5px 5px!important; font-size: 12px !important; text-align:center;">
                    @if($p->estado_mov == 'G' || $p->estado_mov == 'Z')
                      <a href="{{ route('devolucion.edit', ['tipo_mov' => $p->tipo_mov, 'ano_mov' => $p->ano_mov, 'nro_mov' => $p->nro_mov]) }}" data-bs-toggle="tooltip" title="Editar Gu&iacute;a de Internamiento"><i class="fas fa-edit fa-lg"></i><br>Editar</a>
                    @else
                      <a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fas fa-edit fa-lg text-muted"></i><br>Editar</a>
                    @endif 
                    </td>-->

                    <td style="padding: 5px 5px!important; font-size: 12px !important; text-align:center;">
                    @if($p->estado_mov == 'G' || $p->estado_mov == 'Z')
                        <a href="#" data-bs-toggle="tooltip" title="Enviar Gu&iacute;a para Archivo" onclick="prepararYMostrarModal('{{ $p->tipo_mov }}',{{ $p->ano_mov }},{{ $p->nro_mov }},event)" style="color: green;"><i class="fas fa-paper-plane fa-lg"></i><br>Enviar</a>
                    @else
                        <a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fas fa-paper-plane fa-lg text-muted" ></i><br>Enviar</a>
                    @endif 
                    </td>
                    <td style="padding: 5px 5px!important; font-size: 12px !important; text-align:center;">
                        <a href="#" onclick="mostrardetalle('{{ $p->tipo_mov }}',{{ $p->ano_mov }},{{ $p->nro_mov }}, event)" title="Ver detalle" style="color: green;"><i class="fas fa-search fa-lg" ></i><br>Detalle</a>                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
        
        </div>
    </div>


<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR ENVIO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        DESEA CONTINUAR CON EL ENVIO DE CARPETAS FISCALES?
      </div>
      
      <input type='hidden' id='tpmov' name='tpmov'>
      <input type='hidden' id='anomov' name='anomov'>
      <input type='hidden' id='nromov' name='nromov'>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" onclick="guardarTexto()">Continuar y Grabar Inventario</button>-->
        <a href="#" onclick="enviardevolucion(event)" class="btn btn-primary">Enviar Carpetas Fiscales a Archivo</a>
        <!--<button type="button" id="grabarBtn" class="btn btn-primary">Aceptar y enviar gu&iacute;a</button>-->
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
</div>
<!-- Modal -->
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
        <iframe id="pdfFrame" src="" width="100%" height="600px" style="border: none;"></iframe>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content custom-modal-height">
      
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel">CARPETAS FISCALES EN MOVIMIENTO DE DEVOLUCION</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body" id="detalleseguimiento">
        <b>CARPETAS FISCALES<br>
        </b><br><br>
          <table id="scanned-list" class="table table-striped table-sm">
              <thead class="thead-dark">
                  <tr>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Carpeta Fiscal</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Imputado</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Agraviado</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Delito</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Folios</th>
                      <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tomo</th>
                  </tr>
              </thead>
              <tbody style="font-size:12px;" >
		<!-- Los datos escaneados se irán añadiendo aquí -->
              </tbody>
          </table>        
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>

    </div>
  </div>
</div>


</form>


<!--</div>-->
@endsection
@push('scripts')
<script>
function generapdf(url,event) {
    if (event) event.preventDefault(); // Previene recarga    
    $('#pdfFrame').attr('src', url);
    $('#pdfModal').modal('show');
}
</script>


<script>
  $(document).ready(function() {
    $('#tablaexpedientes').DataTable({
  "columnDefs": [
    { "orderable": false, "targets": [9,10,11] }  // Evitar orden en columnas de acción si no es necesario
  ],
      "pageLength": 10,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": true,  // Habilitar búsqueda
      "ordering": false,   // Habilitar ordenación
      "info": true,       // Mostrar información de la tabla
      "autoWidth": false,  // Ajustar automáticamente el ancho de las columnas
      "lengthChange": false,
      "language": {
            "search": "Buscar",                         // Cambia "Search" por "Buscar"
            "lengthMenu": "Mostrar _MENU_ paquetes",    // Cambia "Show entries" por "Mostrar entradas"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ paquetes", // Cambia el texto de la información
            "zeroRecords": "No se encontraron registros", // Mensaje cuando no hay resultados
            "infoEmpty": "Mostrando 0 a 0 de 0 paquetes", // Cuando la tabla está vacía
            "infoFiltered": "(filtrado de _MAX_ paquetes totales)", // Cuando hay filtros activos
      
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
const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
function prepararYMostrarModal(xtipo,xano,xnro,event) {
    if (event) event.preventDefault(); // Previene recarga

    document.getElementById('tpmov').value=xtipo;
    document.getElementById('anomov').value=xano;
    document.getElementById('nromov').value=xnro;
    myModal.show();
}

function enviardevolucion(event) {
    if (event) event.preventDefault(); // Previene recarga
    const tipo = document.getElementById('tpmov').value;
    const ano = document.getElementById('anomov').value;
    const nro = document.getElementById('nromov').value;
    myModal.hide();

    $.ajax({
        url: '{{ route("devolucion.envio") }}', 
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            tipo_mov: tipo,
            ano_mov: ano,
            nro_mov: nro
        },
        success: function(response) {
            //window.location.href = '{{ route("internamiento.index") }}';
          if (response.success) {
              // Guardar el mensaje para mostrarlo en la siguiente vista
              sessionStorage.setItem('successMessage', response.message);

              // Redirigir manualmente
              window.location.href = response.redirect_url;
          } else {
            alert(response.message);
          }
        },
        error: function() {
            alert('Error en proceso de envio.');
        }
    });
}
</script>

<script>
function mostrardetalle(tpmov, anomov, nromov,event) {

            const tableBody = $('#scanned-list tbody');
            tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla

    if (event) event.preventDefault(); // Previene recarga

                    $.ajax({
                        url: '{{ route("solicitud.recepcion") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            tpmov: tpmov,
                            anomov: anomov,
                            nromov: nromov
                        },
                        success: function(response) {

                           if (response.success) {
                                				
                                var registros = response.registros;
                                registros.forEach(function(registro) {
                                
                tableBody.append(`
                    <tr>
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.codbarras}</td>
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.imputado || ''}</td>
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.agraviado || ''}</td>
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.desc_delito || ''}</td>
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.nro_folios || ''}</td>                        
                        <td style="font-size:12px; padding: 5px 10px !important;">${registro.tomo}</td>
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
                                alert('Hubo un error al buscar las carpetas de la solicitud.');
                            }
                        }





                        
                    });
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