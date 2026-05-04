@extends('menu.index') 

@section('content')

    <div class="card">
        <div class="card-header">
        <div class="card-title">Consulta de vouchers por Intervalo de Fechas</div>
        </div>
        <div class="card-body">
<!--            <div class="table-responsive">-->

    <form id="form-filtros" class="row g-3" autocomplete="off">
        @csrf
        <div class="col-md-2">
            <label for="fechaini" class="form-label"><b>Fecha Inicial</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="fechaini" name="fechaini" class="form-control text-center" value="{{ old('fechaini', date('Y-m-d')) }}" style="width: 130px;" >
            </div>
        </div>
        <div class="col-md-2">
            <label for="fechafin" class="form-label"><b>Fecha Final</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="fechafin" name="fechafin" class="form-control text-center" value="{{ old('fechafin', date('Y-m-d')) }}" style="width: 130px;" >
            </div>
        </div>

        <div class="col-md-3 d-flex align-items-center">
            <div class="d-flex align-items-center gap-2">
            <a href="#" onclick="mostrarvouchers(event)" class="btn btn-primary ">Iniciar Consulta</a>
            </div>
        </div>
<!--    <div class="col-md-2 d-flex align-items-center justify-content-end">
            <div class="d-flex align-items-center gap-2">
        <button id="botonimprimir" type="button" onclick="imprimirpdf()" class="btn  " style="background-color: #6c757d; color: white;" id="btnimprimir"><i class="fas fa-print me-1"></i> Imprimir</button>
            </div>
    </div>-->
    </form>



    <div class="mt-5">
        <table id="scanned-list" class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">#</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;" width="5">I</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Tp Voucher</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">N&uacute;mero</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha Operaci&oacute;n</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Monto</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Carpeta Fiscal</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">DNI Solicitante</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Usuario</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Dependencia</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha Registro</th>
                </tr>
            </thead>
            <tbody style="font-size:11px;" >
                @php
                    $tipos = [
                        'BN' => 'VENTANILLA BN',
                        'AG' => 'AGENTE BN',
                        'PA' => 'PAGALO PE',
                    ];
                    $tipos2 = [
                        'BN' => 'ventanillabn.jpg',
                        'AG' => 'agentebn.jpg',
                        'PA' => 'pagalope.jpg',
                    ];
                @endphp                
                @foreach($segdetalle as $index => $item)

                        <tr>
                            <td style="font-size:11px; padding: 5px 5px !important;">{{$index + 1}}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">
                                <img 
                                src="{{ asset('img/' . ($tipos2[$item->tpvoucher] ?? '')) }}" 
                                alt="tipo" 
                                height="20"
                                style="margin-right:5px;">
                            </td>
                            <td style="font-size:11px; padding: 5px 5px !important;">
                                {{$tipos[$item->tpvoucher] ?? ''}}
                            </td>
                                
                            @if($item->codescrito)
                            <td style="font-size:11px; padding: 5px 5px !important; {{ $item->voucherduplicado == 'S' ? 'background-color: red;' : 'background-color: green;' }}">
                                <b><a href="#" style="color: white; text-decoration: none;" onclick="mostrarEscrito('{{ $item->codescrito }}'); return false;">
                                    {{ $item->nrovoucher ?? '' }}
                                </a></b>
                            </td>
                            @else
                            <td style="font-size:11px; padding: 5px 5px !important;">
                                {{ $item->nrovoucher ?? '' }}
                            </td>
                            @endif
                                
                            <td style="font-size:11px; padding: 5px 5px !important;">{{$item->fechaoperacion ?? ''}}</td>
                            <td class="text-end" style="font-size:11px; padding: 5px 5px !important;">{{$item->monto ?? ''}}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">{{$item->carpetafiscal ?? ''}}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">{{$item->dnisolicitante ?? ''}}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">{{$item->usuario ?? ''}}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">{{$item->abreviado ?? ''}}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">{{$item->fechahoraregistro ?? ''}}</td>
                        </tr>

                @endforeach
            </tbody>
        </table>        
    </div>

<!--            </div>--><!--table responsive-->
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
        <iframe id="pdfViewer" src="" width="100%" height="600px" style="border: none;"></iframe>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalescrito" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle Escrito</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="Viewer" src="" width="100%" height="600px" style="border: none;">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

@endsection

<style>
    .card-body {
        overflow: visible !important;
        position: relative; /* asegúrate de que esté definido */
        z-index: 1;
    }
</style>
@section('scripts')

<script>
    let tabla = $('#scanned-list').DataTable({

      "pageLength": 20,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": true,  // Habilitar búsqueda
      "ordering": false,   // Habilitar ordenación
      "info": true,       // Mostrar información de la tabla
      "autoWidth": false,  // Ajustar automáticamente el ancho de las columnas
      "lengthChange": false,
      "language": {
            "search": "Buscar",                         // Cambia "Search" por "Buscar"
            "lengthMenu": "Mostrar _MENU_ Vouchers",    // Cambia "Show entries" por "Mostrar entradas"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Vouchers", // Cambia el texto de la información
            "zeroRecords": "No se encontraron registros", // Mensaje cuando no hay resultados
            "infoEmpty": "Mostrando 0 a 0 de 0 Vouchers", // Cuando la tabla está vacía
            "infoFiltered": "(filtrado de _MAX_ Vouchers totales)", // Cuando hay filtros activos
      
            // Personaliza "Previous" y "Next" en la paginación
            "paginate": {
              "previous": "Anterior",   // Cambia "Previous" por "Anterior"
              "next": "Siguiente"       // Cambia "Next" por "Siguiente"
            },
      
            // Personaliza el texto de "Showing entries"
            "emptyTable": "No hay datos disponibles en la tabla", // Mensaje si no hay datos
      }      
    });

function generapdf(event) {
    event.preventDefault();

    const fiscal = document.getElementById('fiscal').value;
    const fechareg = document.getElementById('fechareg').value;

    if (!fiscal || !fechareg) {
        alert("SELECCIONE FISCAL Y FECHA.");
        return;
    }
    const basePdfUrl = @json(route('escritosfiscal.pdf', ['fiscal' => '__FISCAL__', 'fecha' => '__FECHA__']));
    const url = basePdfUrl
        .replace('__FISCAL__', encodeURIComponent(fiscal))
        .replace('__FECHA__', encodeURIComponent(fechareg));

    // Construir la URL del PDF (ajústala según cómo esté definida tu ruta Laravel)
    //const url = `/mesapartes/${encodeURIComponent(fiscal)}/${encodeURIComponent(fechareg)}/pdf`;
    if (event) event.preventDefault(); // Previene recarga    
    $('#pdfFrame').attr('src', url);
    $('#pdfModal').modal('show');

    // Asignar al iframe
//    document.getElementById('iframePDF').src = url;
    // Mostrar el modal (usando Bootstrap 5)
//    const modal = new bootstrap.Modal(document.getElementById('modalPDF'));
//    modal.show();
}

function mostrarvouchers(event) {

        if ($.fn.DataTable.isDataTable('#scanned-list')) {
            $('#scanned-list').DataTable().destroy();
        }
            const tableBody = $('#scanned-list tbody');
            const tableBodycel = $('#scanned-listcel tbody');
            tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla
            tableBodycel.empty(); // Limpiar la tabla antes de volver a renderizarla

    if (event) event.preventDefault(); // Previene recarga
    const fechaini = document.getElementById('fechaini').value;
    const fechafin = document.getElementById('fechafin').value;

    if ( fechaini=="" ) {
        alert ("INGRESA LA FECHA INICIO");
        return false;
    }
    if ( fechafin=="" ) {
        alert ("INGRESA LA FECHA FINAL");
        return false;
    }

    $.ajax({
        url: '{{ route("mesapartes.consultavouchersdetalle") }}', 
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            fechaini: fechaini,
            fechafin: fechafin
        },
        success: function(response) {
            if (response.success) {                                
                var registros = response.registros;
                registros.forEach(function(registro, index) {                

                    const tipos = {
                    'BN': 'VENTANILLA BN',
                    'AG': 'AGENTE BN',
                    'PA': 'PAGALO PE',
                    };
                    const tiposImg = {
                        'BN': 'ventanillabn.jpg',
                        'AG': 'agentebn.jpg',
                        'PA': 'pagalope.jpg',
                    };                    
                    const tipoVoucher = tipos[registro.tpvoucher] || registro.tpvoucher;
                    const tipoImagen = tiposImg[registro.tpvoucher] || '';



                    const nrovoucher = registro.codescrito
                        ? `<td style="font-size:11px; padding: 5px 5px !important; background-color: green;"><b><a href="#" style="color: white; text-decoration: none;" onclick="mostrarEscrito('${registro.codescrito}'); return false;">${registro.nrovoucher || ''}</a></b></td>`
                        : `<td style="font-size:11px; padding: 5px 5px !important;">${registro.nrovoucher || ''}</td>`;




                    tableBody.append(`
                        <tr>
                            <td style="font-size:11px; padding: 5px 5px !important;">${index + 1}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">
                            <img 
                                src="/manexp/public/img/${tipoImagen}" 
                                alt="tipo" 
                                style="width:20px; height:20px; object-fit:contain; vertical-align:middle; margin-right:5px;"
                            >
                            </td>
                            <td style="font-size:11px; padding: 5px 5px !important;">
                            ${tipoVoucher || ''}
                            </td>

                            ${nrovoucher}

                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.fechaoperacion || ''}</td>
                            <td class="text-end" style="font-size:11px; padding: 5px 5px !important;">${registro.monto || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.carpetafiscal || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.dnisolicitante || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.usuario || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.abreviado || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.fechahoraregistro || ''}</td>
                        </tr>
                    `);
                
                });

    let tabla = $('#scanned-list').DataTable({

      "pageLength": 20,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": true,  // Habilitar búsqueda
      "ordering": false,   // Habilitar ordenación
      "info": true,       // Mostrar información de la tabla
      "autoWidth": false,  // Ajustar automáticamente el ancho de las columnas
      "lengthChange": false,
      "language": {
            "search": "Buscar",                         // Cambia "Search" por "Buscar"
            "lengthMenu": "Mostrar _MENU_ Vouchers",    // Cambia "Show entries" por "Mostrar entradas"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Vouchers", // Cambia el texto de la información
            "zeroRecords": "No se encontraron registros", // Mensaje cuando no hay resultados
            "infoEmpty": "Mostrando 0 a 0 de 0 Vouchers", // Cuando la tabla está vacía
            "infoFiltered": "(filtrado de _MAX_ Vouchers totales)", // Cuando hay filtros activos
      
            // Personaliza "Previous" y "Next" en la paginación
            "paginate": {
              "previous": "Anterior",   // Cambia "Previous" por "Anterior"
              "next": "Siguiente"       // Cambia "Next" por "Siguiente"
            },
      
            // Personaliza el texto de "Showing entries"
            "emptyTable": "No hay datos disponibles en la tabla", // Mensaje si no hay datos
      }      
    });

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
                alert('HUBO UN ERROR AL CONSULTAR VOUCHERS.');
            }
        }        
    });

}
    async function imprimirpdf() { 
    const fechaini = document.getElementById('fechaini').value;
    const fechafin = document.getElementById('fechafin').value;
    const chksindigi = document.getElementById('chksindigi').checked; 

    const loader = document.getElementById('loader');
    loader.style.display = 'block'; 

        try {
            const response = await fetch("{{ route('mesapartes.imprimeintervalo') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    fechaini: fechaini,
                    fechafin: fechafin,
                    chksindigi: chksindigi ? 1 : 0
                })
            });

            const blob = await response.blob();
            const url = URL.createObjectURL(blob);

        loader.style.display = 'none';

            document.getElementById('pdfViewer').src = url;
            new bootstrap.Modal(document.getElementById('pdfModal')).show();

        } catch (error) {
            console.error(error);
            alert('Ocurrió un error generando el PDF.');
        }
    }

function numeroAOrdinal(numero) {
    const ordinales = {
        1: '1er',
        2: '2do',
        3: '3er',
        4: '4to',
        5: '5to',
        6: '6to',
        7: '7mo',
        8: '8vo',
        9: '9no',
        10: '10mo',
        11: '11er'
    };

    return ordinales[numero] || numero + ' ';
}
</script>

<script>
function mostrarEscrito(codescrito) {
    $.ajax({
        url: '{{ route("mesapartes.consultavouchersescrito") }}', 
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            codescrito: codescrito,
        },
        success: function(response) {
            if (response.success) {     
                document.getElementById('Viewer').innerHTML = response.detescrito;
                new bootstrap.Modal(document.getElementById('modalescrito')).show();
            } else {
                //alert(response.message);
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
                alert('HUBO UN ERROR AL CONSULTAR VOUCHERS.');
            }
        }        
    });
}


function mostrarDetalle(anio, mes, codigo) {
    const pdfUrl = `../../storage/app/mesapartes/${anio}/${mes}/${codigo.toUpperCase()}.pdf`;
    $('#pdfViewer').attr('src', pdfUrl);
    $('#pdfModal').modal('show');
}
</script>
@endsection
