@extends('menu.index')

@section('content')
<!--<div class="container mt-4">-->
    <!--<h2 class="mb-4">Expedientes Registrados</h2>-->

    @if(session('success'))
        <div id="messageOK" class="alert alert-success"><b>{{ session('success') }}</b></div>
    @else
        <div id="messageOK" class="alert alert-success" style="display:none;"><b></b></div>
    @endif

    <a href="{{ route('expediente.create') }}" class="btn btn-primary mb-3">+ Nuevo Registro</a>
    <div class="card">
        <div class="card-header">
        <div class="card-title">Expedientes Registrados</div>
        </div>
        <div class="card-body table-responsive">

        <table id="tablaexpedientes" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Cod. Barras</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Fec. Ingreso</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Hora Ingreso</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Imputado</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Agraviado</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">delito</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Nro Oficio</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;">Folios</th>
                    <th style="padding: 5px 10px!important; font-size: 12px !important; text-transform:none;" colspan=2>Acciones</th>
                </tr>
            </thead>
            <tbody style="font-size:12px;">
                @foreach($expediente as $p)
                    <tr>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->codbarras }}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->fecha_ingreso }}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->hora_ingreso }}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->imputado }}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->agraviado }}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->desc_delito }}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->nro_oficio }}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->nro_folios }}</td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important; text-align:center;">
                            <a href="{{ route('expediente.edit', $p->id_expediente) }}"><i class="fas fa-edit fa-lg"></i>Editar</a>
                        </td>
                        <td style="padding: 5px 10px!important; font-size: 12px !important;">
                            <!--<form action="{{ route('expediente.destroy', $p->id_expediente) }}" id="miFormulario2" method="POST" class="d-inline">
                                @csrf @method('DELETE')-->
                                <!--<button type="button" class="btn btn-link p-0 btn-danger" style="font-size: 12px !important;text-decoration: none;" onclick="return confirm('Estas seguro de eliminar este Expediente?')">-->
                                <button type="button" class="btn btn-link p-0 btn-danger" style="font-size: 12px !important;text-decoration: none;" onclick="prepararYMostrarModal('{{ $p->codbarras }}',{{ $p->id_expediente }}, event)">
                                    <i class="fas fa-trash-alt fa-lg"></i>Eliminar
                                </button>
                            <!--</form>-->
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
      <div id="mensajemodal" class="modal-body">
      </div>
      <input type='hidden' id='idexp' name='idexp'>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" onclick="guardarTexto()">Continuar y Grabar Inventario</button>-->
        <a href="#" onclick="eliminarexpediente(event)" class="btn btn-primary">Eliminar Expediente</a>
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

const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
function prepararYMostrarModal(codbar,idexp,event) {
    if (event) event.preventDefault(); // Previene recarga
    document.getElementById('idexp').value=idexp;
    document.getElementById('mensajemodal').innerHTML="SE ELIMINAR&Aacute; EL EXPEDIENTE " + codbar + "<br><br>DESEA CONTINUAR?";
    myModal.show();
}
function eliminarexpediente(event) {
    if (event) event.preventDefault(); // Previene recarga
    myModal.hide();

    var idexp = document.getElementById('idexp').value;
    let url = '{{ route("expediente.destroy", ":rutaid") }}';
    url = url.replace(':rutaid', idexp);

    $.ajax({
        url: url, 
        method: 'POST',
        data: {
            _method: 'DELETE',
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if (response.success) {
              sessionStorage.setItem('successMessage', response.message);
              window.location.href = response.redirect_url;
          }
        },
        error: function() {
            alert('Error en proceso de envio.');
        }
    });
}

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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const msg = sessionStorage.getItem('successMessage');
        if (msg) {
          sessionStorage.removeItem('successMessage');

          document.getElementById('messageOK').innerHTML ="<b>"+ msg + "</b>";
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