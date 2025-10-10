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
@php
    $tipos = [
    'E' => 'Escrito',
    'O' => 'Oficio',
    'S' => 'Solicitud',
    'C' => 'Carta',
    'I' => 'Invitación',
    'F' => 'Informe',
    'Z' => 'OTROS',
    ];
@endphp
@auth
    @php
        $perfil = optional(Auth::user()->perfil)->descri_perfil;        
    @endphp
@endauth

<!--<div class="container mt-4">-->
    <!--<h2 class="mb-4">Expedientes Registrados</h2>-->

    @if(session('success'))
        <div id="messageOK" class="alert alert-success"><b>{{ session('success') }}</b></div>
    @else
        <div id="messageOK" class="alert alert-success" style="display:none;"><b></b></div>
    @endif
    <div id="messageErr" class="alert alert-danger" style="display:none;"><b></b></div>

    <a href="{{ route('carpetassgf.registrocarpetassgf') }}" class="btn btn-primary mb-3">+ Nuevo Registro</a>
    <div class="card">
        <div class="card-header">
        <form action="{{ route('carpetassgf.carpetassgfindex') }}" method="POST">
            @csrf

            <div class="card-title">Carpetas SGF registrados : 
            <input type="date" name="fecharegistro" id="fecharegistro" value="{{ $fecha ?? date('Y-m-d') }}">
            <button type="submit">Refrescar</button></div>

        </form>

        </div>
        <div class="card-body table-responsive">

        <table id="tablacarpetassgf" class="table table-striped table-bordered" width=100%>
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Carpeta Fiscal</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Dependencia</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Despacho</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Fecha</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Usuario</th>
                    <!--<th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Editar</th>-->
                </tr>
            </thead>
            <tbody style="font-size:11px;">
                @foreach($carpetassgf as $p)
                @php
                    $esHoy = date('Y-m-d') == date('Y-m-d', strtotime($p->fechahora_registro));
                @endphp

                    <tr>
                        <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->carpetafiscal }}</td>
                        <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->abreviado }}</td>
                        <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ numeroAOrdinal($p->despacho) . " DESPACHO" }}</td>
                        <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->fechahora_registro }}</td>
                        <td style="padding: 5px 5px!important; font-size: 11px !important;">{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                        <!--<td style="padding: 5px 5px!important; font-size: 11px !important; text-align:center;">-->
                            
                            @if($esHoy || ($perfil=="Admin") )
                                <!--<a href="{{ route('carpetassgf.edit', ['id_generado' => $p->id_generado]) }}"
                                data-bs-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>-->
                            @else
                                <!--<a href="#" style="opacity: 0.5; cursor: not-allowed;"
                                data-bs-toggle="tooltip" title="Solo puede editarse el mismo día de registro">
                                    <i class="fas fa-edit fa-lg text-muted"></i>
                                </a>-->
                            @endif
                        <!--</td>-->
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
  "columnDefs": [
    { "orderable": false, "targets": [11,12] }  // Evitar orden en columnas de acción si no es necesario
  ],
        "pageLength": 10,  // Número de filas por página
        "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
        "searching": true,  // Habilitar búsqueda
        "ordering": true,   // Habilitar ordenación
    order: [[0, 'desc']], 
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