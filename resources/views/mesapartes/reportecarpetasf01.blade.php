@extends('menu.index')

@section('content')
<div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    

@if(session('success'))
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('success') }}</b></div>
@else
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
@endif

    <form id="miFormulario" autocomplete="off">
      @csrf

            <div class="row" id="datadeta">            
              <div class="col-md-12">
                <div class="card">
                  
                <div class="card-header">
                <div class="card-title">Imprime Reportes de Carpetas Fiscales Turno Cerro Colorado</div>
                </div>
                  <div class="card-body">
                    <div class="row" id="codigoverificar">
                      <div class="col-md-12 col-lg-12">
                        <table id="tablacarpetassgf" class="table table-striped table-bordered" width=100%>
                            <thead class="thead-dark">
                                <tr>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">#</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Fecha Inicio</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Dependencia</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Ingreso por</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Enviado a</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">C&oacute;digo<br>Asignado</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Cant.<br>Carpetas</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Completo</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Imprimir</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:11px;">
                            @php
                                $tipos = [
                                    'C1' => 'Coordinación 1ra',
                                    'C2' => 'Coordinación 2da',
                                    'C3' => 'Coordinación 3ra',
                                ];
                            @endphp
                            @foreach($carpetastcerro as $index => $item)
                            @php
                                $idcodbar = $item->id_codbarras;
                                $codigo = $item->codigo;
                                $iconoDetalle = '<a href="#" onclick="imprimirpdf(\'' . $idcodbar . '\', \'' . $codigo . '\'); return false;">
                                            <i class="fas fa-search"></i> Imprimir
                                    </a>';
                            @endphp                
                                <tr>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $index + 1 }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->fecha }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->descripcion }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">TURNO CERRO</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $tipos[$item->enviadoa] }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->codigo }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->cantidad }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->completo }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{!! $iconoDetalle !!}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                      </div>
                    </div>


<style>
#tablacarpetassgf tbody td {
  padding: 5px 10px !important;
  font-size: 11px !important;
  text-transform: none !important;
}
</style>








    

                  </div>
                </div>
              </div>
            </div>    

            
    </form>


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

@endsection

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



@section('scripts')
<script>
//  $(document).ready(function() {
    let tabla = $('#tablacarpetassgf').DataTable({

      "pageLength": 10,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": false,  // Habilitar búsqueda
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


    

//  });
</script>
<script>

document.getElementById("miFormulario").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
    }
});

    function imprimirpdf(idcodbar, codigo) {
        let tpre ='TCE';
        const basePdfUrl = @json(route('mesapartes.imprimecarpetasf'));
        const url = `${basePdfUrl}?tpreporte=${encodeURIComponent(tpre)}&idcodbar=${encodeURIComponent(idcodbar)}&codigo=${encodeURIComponent(codigo)}`;
        if (event) event.preventDefault(); // Previene recarga    
        $('#pdfFrame').attr('src', url);
        $('#pdfModal').modal('show');
    }

</script>


@endsection
