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


<div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    

@if(session('success'))
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('success') }}</b></div>
@else
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
@endif

    <form id="miFormulario" autocomplete="off">
      @csrf
        <div class="row" id="datacabe">            
          <div class="col-md-12">
            <div class="card" style="margin-bottom: 10px;">
                
                <div class="card-header">
                <div class="card-title">Consulta por Carpeta Fiscal</div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label for="codigo" class="form-label"><b>Código: </b></label>
                            <input type="text" name="codigo" id="codigo" class="form-control" required
                                value="{{ $cfcodigo ?? ''  }}" maxlength="11">
                        </div>
                        <div class="col-md-2">
                            <label for="ano" class="form-label"><b>Año: </b></label>
                            <input type="text" name="ano" id="ano" class="form-control" required
                                value="{{ $cfano ?? ''  }}" maxlength="4">
                        </div>
                        <div class="col-md-2">
                            <label for="numero" class="form-label"><b>Número: </b></label>
                            <input type="text" name="numero" id="numero" class="form-control" required
                                value="{{ $cfnumero ?? ''  }}" maxlength="6">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="#" onclick="buscaporfecha(event)" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Buscar Carpeta</a>
                        </div>
                    </div>        
<!--
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <h5 class="text-primary">Carpetas registradas</h5>
                        </div>
                        @if(count($carpetasf) > 0)
                        <div class="col-md-6 col-lg-6 text-end">
                            <input type="hidden" id="codigocf" name="codigocf">
                            <button id="botonimprimir" type="button" onclick="imprimirpdf()" class="btn  " style="background-color: #6c757d; color: white;" id="btnimprimir"><i class="fas fa-print me-1"></i> Imprimir</button>
                        </div>
                        @endif
                    </div>    -->

                    @php
                        $tipos = [
                            'CC' => 'Coordinación',
                            'C1' => 'Coordinación 1ra',
                            'C2' => 'Coordinación 2da',
                            'C3' => 'Coordinación 3ra',
                            '01' => '1er. Despacho',
                            '02' => '2do. Despacho',
                            '03' => '3er. Despacho',
                            '04' => '4to. Despacho',
                            '05' => '5to. Despacho',
                            '06' => '6to. Despacho',
                            '07' => '7mo. Despacho',
                            '08' => '8vo. Despacho',
                            '09' => '9no. Despacho',
                            '10' => '10mo. Despacho',
                            '11' => '11er. Despacho',
                            '12' => '12do. Despacho',
                        ];
                        $ingresopor = [
                            0 => '',
                            1 => 'TURNO CORPORATIVA',
                            2 => 'TURNO CERRO',
                            3 => 'TURNO DESPACHO',
                        ];
                        $motivo = [
                            0 => '',
                            1 => 'DERIVACIÓN',
                            2 => 'ACUMULACIÓN',
                            3 => 'VIRTUAL',
                            4 => 'NUEVA',
                            5 => 'REASIGNACIÓN',
                        ];
                    @endphp
                    <div class="row">
                        @if (!$busco)
                            <p>Ingrese el código, año y número de la carpeta a buscar</p>
                        @elseif ($carpetasf->isEmpty())
                            <p>No se encontró la carpeta fiscal con los datos proporcionados</p>
                        @else
                            @foreach($carpetasf as $index => $item)

                            <div class="col-sm-12 col-md-11 col-lg-10">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <td style="padding:8px 12px!important"><b>CÓDIGO-AÑO-NÚMERO: </b></td>
                                        <td style="padding:8px 12px!important">{{ $cfcodigo }}-{{ $cfano }}-{{ $cfnumero }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 12px!important"><b>FECHA: </b></td>
                                        <td style="padding:8px 12px!important">{{ $item->fecha }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 12px!important"><b>CARPETA FISCAL: </b></td>
                                        <td style="padding:8px 12px!important">{{ $item->carpetafiscal }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 12px!important"><b>INGRESO POR: </b></td>
                                        <td style="padding:8px 12px!important">{{ $ingresopor[$item->ingresopor] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 12px!important"><b>DEPENDENCIA: </b></td>
                                        <td style="padding:8px 12px!important">{{ $item->descripcion }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 12px!important"><b>ENVIADO A: </b></td>
                                        <td style="padding:8px 12px!important">{{ $tipos[$item->enviadoa] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 12px!important"><b>FECHA Y HORA REGISTRO: </b></td>
                                        <td style="padding:8px 12px!important">{{ $item->fechahora_registro }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:8px 12px!important"><b>MOTIVO: </b></td>
                                        <td style="padding:8px 12px!important">{{ $motivo[$item->motivo] }}</td>
                                    </tr>
                                </table>
                            </div>
                            @endforeach

                        @endif
                    </div>


                  </div>
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
@section('scripts')
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




<script>
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
            "lengthMenu": "Mostrar _MENU_ carpetas",    // Cambia "Show entries" por "Mostrar entradas"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ carpetas", // Cambia el texto de la información
            "zeroRecords": "No se encontraron registros", // Mensaje cuando no hay resultados
            "infoEmpty": "Mostrando 0 a 0 de 0 carpetas", // Cuando la tabla está vacía
            "infoFiltered": "(filtrado de _MAX_ carpetas totales)", // Cuando hay filtros activos
      
            // Personaliza "Previous" y "Next" en la paginación
            "paginate": {
              "previous": "Anterior",   // Cambia "Previous" por "Anterior"
              "next": "Siguiente"       // Cambia "Next" por "Siguiente"
            },
      
            // Personaliza el texto de "Showing entries"
            "emptyTable": "No hay datos disponibles en la tabla", // Mensaje si no hay datos
      }      
    });
</script>

<script>
let motivos = ["","DERIVACIÓN", "ACUMULACIÓN", "VIRTUAL", "NUEVA", "REASIGNACIÓN"];


document.getElementById("miFormulario").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
    }
});


    function buscaporfecha() {
        let codi = document.getElementById("codigo").value;
        let ano = document.getElementById("ano").value;
        let nume = document.getElementById("numero").value;
        if (codi==""){
            alert("Ingrese el código");
            return false;
        }
        if (ano==""){
            alert("Ingrese el año");
            return false;
        }
        if (nume==""){
            alert("Ingrese el número");
            return false;
        }
        window.location.href =
            '{{ route("mesapartes.consultacarpeta") }}?codigo=' + encodeURIComponent(codi) + '&ano=' + encodeURIComponent(ano) + '&numero=' + encodeURIComponent(nume);
    }



</script>


@endsection
