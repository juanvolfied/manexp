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
    <title>Verificaci&oacute;n y Envio de Carpetas Fiscales</title>    
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
                    <div class="card-title">Gu&iacute;a de Internamiento a recepcionar</div>
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
                    
<!--
                    <div class="row" style="background-color:#F2F5A9;">
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group">
                          <label for="codbarras"><b>C&oacute;digo de Barras</b></label>
                          <input type="text" class="form-control" name="codbarras" id="codbarras" placeholder="C&oacute;digo de Barras" autofocus/>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group">
                          <br>
                        </div>
                      </div>

                    </div>
-->
        <!-- Tabla con clases Bootstrap -->
        <table id="tabladetalle" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                  <th style="padding: 5px 5px!important; font-size:12px !important; text-transform:none;" width="40">#</th>			      
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;" width="150">Carpeta Fiscal</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;" width="150">Expediente</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Archivo</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Anaquel</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Paquete</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Serie</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tomo</th>
                  <!--<th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none; text-align:center;" width="120">Estado</th>-->
                </tr>
            </thead>
            <tbody id="tabla-codigos">
                @foreach ($segdetalle as $datos)
                    <tr data-codigo="{{ $datos->codbarras }}">
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $loop->iteration }}</td> <!-- Correlativo -->
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->codbarras }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->id_dependencia }}-{{ $datos->ano_expediente }}-{{ $datos->nro_expediente }}-{{ $datos->id_tipo }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">Archivo {{ str_pad($datos->archivo, 3, '0', STR_PAD_LEFT) }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->anaquel }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->nro_paquete }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->serie }}</td>
                      <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $datos->tomo }}</td>                        
                      <!--<td style="font-size:13px; padding: 5px 10px !important; text-align:center;" class="estado fw-bold">-->
                      @if($datos->estado_mov == "R")
                        <!--<i class="fas fa-check-circle me-1" style="color:green;"></i><span class="fw-bold " style="color:green;">Recepcionado</span>-->
                      @else
                        <!--<i class="fas fa-times-circle me-1" style="color:red;"></i><span class="fw-bold " style="color:red;">Pendiente</span>-->
                      @endif
                      <!--</td>-->                        
                    </tr>
                @endforeach
            </tbody>
        </table>

                  </div>
                  <div class="card-footer">
                    <a href="{{ route('solicitud.atencion') }}" class="btn btn-secondary">Regresar a pantalla anterior</a>
                    <a href="#" onclick="prepararYMostrarModal('{{ $guiacab->tipo_mov }}',{{ $guiacab->ano_mov }},{{ $guiacab->nro_mov }},event)" class="btn btn-primary">ENVIAR CARPETAS SOLICITADAS</a>
                    <!--<a href="#" onclick="prepararYMostrarModal2('{{ $guiacab->tipo_mov }}',{{ $guiacab->ano_mov }},{{ $guiacab->nro_mov }},event)" class="btn btn-danger">RECHAZAR GUIA DE INTERNAMIENTO</a>-->
                  </div>

                </div>
              </div>
            </div>

    <!--</div>-->

      <input type='hidden' id='tpmov' name='tpmov'>
      <input type='hidden' id='anomov' name='anomov'>
      <input type='hidden' id='nromov' name='nromov'>
    
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR ENVIO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        DESEA CONTINUAR CON EL ENVIO DE LAS CARPETAS FISCALES SOLICITADAS?
      </div>
      <div class="modal-footer">
        <a href="#" onclick="EnviarCarpetas(event)" class="btn btn-primary">Enviar a Archivo</a>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="textoModal2" tabindex="-1" aria-labelledby="textoModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel2">CONFIRMAR RECHAZO DE GUIA DE INTERNAMIENTO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        <label for="observacion"><b>OBSERVACI&Oacute;N O MOTIVO DE RECHAZO: (m&aacute;x 200 caracteres)</b></label>
        <textarea name="observacion" id="observacion" class="form-control" maxlength="200" rows="4" cols="70"></textarea>
      </div>
      
      <div class="modal-footer">
        <a href="#" onclick="rechazarinternamiento(event)" class="btn btn-primary">Rechazar Gu&iacute;a de Internamiento</a>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
</div>


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
      "searching": false,  // Habilitar búsqueda
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
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('codbarras');
  const filas = document.querySelectorAll('#tabla-codigos tr');

  input.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
          e.preventDefault();

          let valor = document.getElementById("codbarras").value;
          valor = valor.replace(/^[^0-9]+|[^0-9]+$/g, '');  // Remueve caracteres no alfanum�ricos del inicio y final

          valor = valor.trim();
          document.getElementById("codbarras").value = valor;
          if (valor.length !== 25) {
              alert("El c\u00F3digo de barras " + valor + " no es v\u00E1lido. Solo tiene "+ valor.length +" caracteres.");
              return false;
          }

          var formData = $('#miFormulario').serialize();
          $.ajax({
              url: '{{ route("internamiento.grabarecepcioncodigo") }}',
              method: 'POST',
              data: formData,
              success: function(response) {
                  let mensaje = response.message || 'Respuesta sin mensaje';
                  if (response.success) {
                    filas.forEach(fila => {
                        const codigo = fila.getAttribute('data-codigo');
                        if (codigo === valor) {
                            encontrado = true;
                            if (!fila.classList.contains('match')) {
                                fila.classList.add('match');
                                fila.querySelector('.estado').innerHTML = `<i class="fas fa-check-circle me-1" style="color:green;"></i><span class="fw-bold" style="color:green;">Recepcionado</span>`;
                            }
                        }
                    });
                  } else {
                    document.getElementById('messageErr').innerHTML = '<b>' + mensaje + '</b>';
                    var messageErr = document.getElementById('messageErr');
                    messageErr.style.opacity = '1';
                    messageErr.style.display = 'block';
                    setTimeout(function() {
                        messageErr.style.opacity = '0';
                        setTimeout(() => {
                            messageErr.style.display = 'none';
                        }, 500);
                    }, 3000); 
                  }
              },
              error: function(xhr) {
                  //$('#mensaje-guardar').html('<div style="color: red;">Error inesperado</div>');
                  //console.error(xhr.responseText);
              }
          });

          input.value = '';
      }
  });
});
</script>

<script>
const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
const myModal2 = new bootstrap.Modal(document.getElementById('textoModal2'));
function prepararYMostrarModal(xtipo,xano,xnro,event) {
    if (event) event.preventDefault(); // Previene recarga

    document.getElementById('tpmov').value=xtipo;
    document.getElementById('anomov').value=xano;
    document.getElementById('nromov').value=xnro;
    myModal.show();
}

function EnviarCarpetas(event) {
    if (event) event.preventDefault(); // Previene recarga
    const tipo = document.getElementById('tpmov').value;
    const ano = document.getElementById('anomov').value;
    const nro = document.getElementById('nromov').value;
    myModal.hide();

        $.ajax({
            url: '{{ route("solicitud.grabaatencion") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tipo_mov: tipo,
                ano_mov: ano,
                nro_mov: nro
            },
            success: function(response) {
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
                alert('Error en proceso de atencion y envio de carpetas.');
            }
        });
}

function prepararYMostrarModal2(xtipo,xano,xnro,event) {
    if (event) event.preventDefault(); // Previene recarga

    document.getElementById('tpmov').value=xtipo;
    document.getElementById('anomov').value=xano;
    document.getElementById('nromov').value=xnro;
    myModal2.show();
}
function rechazarinternamiento(event) {
    if (event) event.preventDefault(); // Previene recarga
    const tipo = document.getElementById('tpmov').value;
    const ano = document.getElementById('anomov').value;
    const nro = document.getElementById('nromov').value;
    const obs = document.getElementById('observacion').value;
    myModal2.hide();

        $.ajax({
            url: '{{ route("internamiento.rechazarecepcion") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tipo_mov: tipo,
                ano_mov: ano,
                nro_mov: nro,
                observacion: obs
            },
            success: function(response) {
                //window.location.href = '{{ route("internamiento.recepcion") }}';
                if (response.success) {
                    // Guardar el mensaje para mostrarlo en la siguiente vista
                    sessionStorage.setItem('successMessage', response.message);

                    // Redirigir manualmente
                    window.location.href = response.redirect_url;
                }

            },
            error: function() {
                alert('Error en proceso de recepcion.');
            }
        });
}
</script>

@endpush


