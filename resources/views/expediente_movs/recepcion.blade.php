@extends('menu.index')

@section('content')
@php
    $estados = ['G' => 'Generado', 'E' => 'Pendiente', 'R' => 'Recepcionado'];
function numeroAOrdinal($numero) {
    $ordinales = [0 => '',1 => '1er',2 => '2do',3 => '3er',4 => '4to',5 => '5to',6 => '6to',7 => '7mo',8 => '8vo',9 => '9no',10 => '10mo',11 => '11er',];
    return $ordinales[$numero] ?? $numero . 'º';
}
@endphp
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
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Dependencia</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Despacho</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Estado</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Cant Exped</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Cant Recep</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha<br>Generaci&oacute;n</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha<br>Envio</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha<br>Recepci&oacute;n</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none; text-align:center;" width="120" colspan="2">Acciones</th>
            </tr>
        </thead>
        <tbody style="font-size:12px;">
            @foreach($guiacab as $p)
                <tr>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ str_pad($p->nro_mov, 5, '0', STR_PAD_LEFT) }}-{{ $p->ano_mov }}-{{ $p->tipo_mov == 'GI' ? 'I' : $p->tipo_mov }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->abreviado }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ numeroAOrdinal($p->despacho) }} DESPACHO</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important; {{ $p->estado_mov == 'E' ? 'color:red;' : ($p->estado_mov == 'R' ? 'color:green;' : '') }}"><b>{{ $estados[$p->estado_mov] ?? $p->estado_mov }}</b></td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->cantidad_exp }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->cantidad_exp_recep }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fechahora_movimiento }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fechahora_envio }}</td>
                    <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fechahora_recepcion }}</td>
                    <td style="padding: 5px 5px!important; font-size: 12px !important; text-align:center;">
                        <!--<a href="#" data-bs-toggle="tooltip" title="Recepcionar Todo" onclick="prepararYMostrarModal('{{ $p->tipo_mov }}',{{ $p->ano_mov }},{{ $p->nro_mov }},event)" class="btn btn-link p-0" style="color: blue;"><i class="fas fa-file-alt fa-lg"></i> Todo</a>-->
                        <a href="{{ route('internamiento.det', ['tipo_mov' => $p->tipo_mov, 'ano_mov' => $p->ano_mov, 'nro_mov' => $p->nro_mov] ) }}" data-bs-toggle="tooltip" title="Mostrar el detalle de la Gu&iacute;a de Internamiento" style="color: blue;"><i class="fas fa-search fa-lg"></i><br>Detalle</a>
                    </td>
                    <td style="padding: 5px 5px!important; font-size: 12px !important; text-align:center;">
                    @if($p->estado_mov == 'E')
                        <a href="{{ route('internamiento.ver', ['tipo_mov' => $p->tipo_mov, 'ano_mov' => $p->ano_mov, 'nro_mov' => $p->nro_mov] ) }}" data-bs-toggle="tooltip" title="Recepcionar Escaneando c&oacute;digos de los expedientes" style="color: green;"><i class="fas fa-download fa-lg"></i><br>Recepcionar</a>
                    @else
                        <a href="#" style="opacity: 0.5; cursor: not-allowed;"><i class="fas fa-download fa-lg text-muted" ></i><br>Recepcionar</a>
                    @endif 
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
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR RECEPCI&Oacute;N</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        SE RECEPCIONAR&Aacute; LA GUIA DE INTERNAMIENTO CON LA TOTALIDAD DE EXPEDIENTES ASIGNADOS<br><br>DESEA CONTINUAR CON LA RECEPCI&Oacute;N?
      </div>
      
      <input type='hidden' id='tpmov' name='tpmov'>
      <input type='hidden' id='anomov' name='anomov'>
      <input type='hidden' id='nromov' name='nromov'>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" onclick="guardarTexto()">Continuar y Grabar Inventario</button>-->
        <a href="#" onclick="recibirinternamiento(event)" class="btn btn-primary">Recepcionar Gu&iacute;a de Internamiento</a>
        <!--<button type="button" id="grabarBtn" class="btn btn-primary">Aceptar y enviar gu&iacute;a</button>-->
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
</div>


<!--</div>-->
@endsection
@push('scripts')
<script>
  $(document).ready(function() {
    $('#tablaexpedientes').DataTable({
  "columnDefs": [
    { "orderable": false, "targets": [9,10] }  // Evitar orden en columnas de acción si no es necesario
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
    
function recibirinternamiento(event) {
    if (event) event.preventDefault(); // Previene recarga
    const tipo = document.getElementById('tpmov').value;
    const ano = document.getElementById('anomov').value;
    const nro = document.getElementById('nromov').value;
    myModal.hide();
    $.ajax({
        url: '{{ route("internamiento.grabarecepcion") }}', 
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
          }
        },
        error: function() {
            alert('Error en proceso de recepcion.');
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
@endpush