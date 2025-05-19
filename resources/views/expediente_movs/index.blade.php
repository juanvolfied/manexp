@extends('menu.index')

@section('content')
<!--<div class="container mt-4">-->
    <!--<h2 class="mb-4">Expedientes Registrados</h2>-->

    @if(session('success'))
        <div id="messageOK" class="alert alert-success">{{ session('success') }}</div>
    @else
        <div id="messageOK" class="alert alert-success" style="display:none;"></div>
    @endif

    <a href="{{ route('internamiento.create') }}" class="btn btn-primary mb-3">+ Nueva Gu&iacute;a de Internamiento</a>
    <div class="card">
        <div class="card-header">
        <div class="card-title">Gu&iacute;as de Internamiento Generadas</div>
        </div>
        <div class="card-body table-responsive">

    <table id="tablaexpedientes" class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Movimiento</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fiscal</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Estado</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha Generada</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha Envio</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fecha Recepci&oacute;n</th>
                <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Acciones</th>
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
                    @if($p->estado_mov == 'G')
                        <a href="#" onclick="prepararYMostrarModal('{{ $p->tipo_mov }}',{{ $p->ano_mov }},{{ $p->nro_mov }},event)" class="btn btn-sm btn-warning">Enviar a Archivo</a>
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
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR ENVIO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        DESEA CONTINUAR CON EL ENVIO DE LA GUIA DE INTERNAMIENTO ?
      </div>
      
      <input type='hidden' id='tpmov' name='tpmov'>
      <input type='hidden' id='anomov' name='anomov'>
      <input type='hidden' id='nromov' name='nromov'>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" onclick="guardarTexto()">Continuar y Grabar Inventario</button>-->
        <a href="#" onclick="enviarinternamiento(event)" class="btn btn-primary">Enviar a Archivo</a>
        <!--<button type="button" id="grabarBtn" class="btn btn-primary">Aceptar y enviar gu&iacute;a</button>-->
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
<!--</div>-->


</div>
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
const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
function prepararYMostrarModal(xtipo,xano,xnro,event) {
    if (event) event.preventDefault(); // Previene recarga

    document.getElementById('tpmov').value=xtipo;
    document.getElementById('anomov').value=xano;
    document.getElementById('nromov').value=xnro;
    myModal.show();
}

//function enviarinternamiento(xtipo,xano,xnro,event) {
function enviarinternamiento(event) {
    if (event) event.preventDefault(); // Previene recarga
//    const tipo = xtipo;
//    const ano = xano;
//    const nro = xnro;
    const tipo = document.getElementById('tpmov').value;
    const ano = document.getElementById('anomov').value;
    const nro = document.getElementById('nromov').value;
    myModal.hide();

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
            //window.location.href = '{{ route("internamiento.index") }}';
          if (response.success) {
              // Guardar el mensaje para mostrarlo en la siguiente vista
              sessionStorage.setItem('successMessage', response.message);

              // Redirigir manualmente
              window.location.href = response.redirect_url;
          }
        },
        error: function() {
            alert('Error en proceso de envio.');
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