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
                <div class="card-title">Reportes de Carpetas Fiscales por Fecha</div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="fecha" class="form-label"><b>Fecha: </b></label>
                            <input type="date" name="fecha" id="fecha" class="form-control" required
                                value="{{ $lafecha ??  date('Y-m-d')  }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="#" onclick="buscaporfecha(event)" class="btn btn-primary w-100">
                                <i class="fas fa-arrow-right me-1"></i> Continuar</a>
                        </div>
                    </div>        

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
                    </div>    
                    <div class="row">
                        <table id="tablacarpetassgf" class="table table-striped table-bordered" width=100%>
                            <thead class="thead-dark">
                                <tr>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">#</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Nro Carpeta Fiscal</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Ingreso por</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Dependencia</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Enviado a</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Fecha Registro</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Motivo</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">C&oacute;digo</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">A&ntilde;o</th>
                                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">N&uacute;mero</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:11px;">
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
                            @foreach($carpetasf as $index => $item)
                            @php
                                $idde = substr($item->carpetafiscal, 8, 3); 
                                $anio = substr($item->carpetafiscal, 11, 4); 
                                $expe = intval( substr($item->carpetafiscal, 15, 6) ); 
                            @endphp                
                                <tr>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $index + 1 }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->carpetafiscal }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $ingresopor[$item->ingresopor] }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->abreviado }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $tipos[$item->enviadoa] }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $item->fechahora_registro }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $motivo[$item->motivo] }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $idde }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $anio }}</td>
                                    <td style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">{{ $expe }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
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
        let fech = document.getElementById("fecha").value;
        if (fech==""){
            alert("Ingrese la Fecha");
            return false;
        }
        window.location.href =
            '{{ route("mesapartes.reportecarpetasf02") }}?fecha=' + encodeURIComponent(fech);
    }


    function xxximprimirpdf() {
        let fech = document.getElementById("fecha").value;
        let depe = document.getElementById("dependencia").value;
        let ingp = document.getElementById("ingresopor").value;
        let enva = document.getElementById("enviadoa").value;
        let tpre ='';
        if (ingp=="1") { tpre ='TCOF'; } //turno corporativa por fecha
        if (ingp=="3") { tpre ='TDEF'; } //turno despacho por fecha
        //const codigocf = document.getElementById('codigocf').value;
        //if (codigocf=="") {
        //    alert("DATOS NO DISPONIBLES");
        //    return;
        //}
        //const basePdfUrl = @json(route('mesapartes.imprimecarpetasf', ['codigocf' => '__CODIGO__']));
        //const url = basePdfUrl
        //    .replace('__CODIGO__', encodeURIComponent(codigocf))

        const basePdfUrl = @json(route('mesapartes.imprimecarpetasf'));
        const url = `${basePdfUrl}?tpreporte=${encodeURIComponent(tpre)}&fech=${encodeURIComponent(fech)}&depe=${encodeURIComponent(depe)}&ingp=${encodeURIComponent(ingp)}&enva=${encodeURIComponent(enva)}`;


        if (event) event.preventDefault(); // Previene recarga    
        $('#pdfFrame').attr('src', url);
        $('#pdfModal').modal('show');
    }
    async function imprimirpdf() { 
        const carpetasf = @json($carpetasf);
        const lafecha = "{{ $lafecha }}"; // string
        try {
            const response = await fetch("{{ route('mesapartes.imprimecarpetasfec') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    carpetasf: carpetasf,
                    lafecha: lafecha                
                })
            });

            const blob = await response.blob();
            const url = URL.createObjectURL(blob);
            document.getElementById('pdfFrame').src = url;
            new bootstrap.Modal(document.getElementById('pdfModal')).show();

        } catch (error) {
            console.error(error);
            alert('Ocurrió un error generando el PDF.');
        }
    }

</script>


@endsection
