@extends('menu.index') 

@section('content')

    <!--<h2 class="mb-4">Seguimiento de Expedientes</h2>-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Consulta de escritos por Intervalo de Fechas</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">

    <form id="form-filtros" class="row g-3" autocomplete="off">
        @csrf
        <div class="col-md-2">
            <label for="fechaini" class="form-label"><b>Fecha Inicial</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="fechaini" name="fechaini" class="form-control text-center" value="{{ old('fechaini', date('Y-m-d')) }}" style="width: 120px;" >
            </div>
        </div>
        <div class="col-md-2">
            <label for="fechafin" class="form-label"><b>Fecha Final</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="fechafin" name="fechafin" class="form-control text-center" value="{{ old('fechafin', date('Y-m-d')) }}" style="width: 120px;" >
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="mostrarescritos(event)" class="btn btn-primary w-100">Mostrar Escrito(s)</a>
        </div>        
        <!--<div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="generapdf(event)" class="btn btn-primary w-100">Imprimir Escritos</a>
        </div>-->        
    </form>

    <div class="mt-5">
        <table id="scanned-list" class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 5px!important; font-size:12px !important; text-transform:none;">#</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Fecha</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">C&oacute;digo</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Dependencia</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Despacho</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Fiscal</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Tipo</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Descripci&oacute;n</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Dependencia<br>Origen</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Remitente</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Carpeta<br>Fiscal</th>
                    <th style="padding: 5px 5px!important; font-size: 12px !important; text-transform:none;">Folios</th>
                    <th style="padding: 5px 5px!important; font-size:12px !important; text-transform:none;">Ver</th>			      
                </tr>
            </thead>
            <tbody style="font-size:12px;" >
            </tbody>
        </table>        
    </div>

            </div><!--table responsive-->
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

function mostrarescritos(event) {
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
        url: '{{ route("mesapartes.consultaintervalodetalle") }}', 
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
                    'E': 'Escrito',
                    'O': 'Oficio',
                    'S': 'Solicitud',
                    'C': 'Carta',
                    'I': 'Invitación',
                    'F': 'Informe',
                    'Z': 'OTROS'
                    };
                    const tipoTexto = tipos[registro.tipo] || registro.tipo;

                const fecha = registro.fecharegistro; // "2025-07-08 22:12:54"
                const anio = fecha.substring(0, 4);   // "2025"
                const mes  = fecha.substring(5, 7);   // "07"                    
                const codescrito = registro.codescrito;


                    const iconoDetalle = registro.existepdf
                        ? `<a href="#" onclick="mostrarDetalle('${anio}', '${mes}', '${registro.codescrito}'); return false;">
                            <i class="fas fa-search"></i>
                        </a>`
                        : `<i class="fas fa-search text-muted" title="Documento digital PDF no disponible" style="opacity: 0.5; cursor: not-allowed;"></i>`;


                    tableBody.append(`
                        <tr>
                            <td style="font-size:12px; padding: 5px 5px !important;">${index + 1}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">${registro.fecharegistro || ''}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">${registro.codescrito || ''}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">${registro.abreviado || ''}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">
                            ${registro.despacho ? numeroAOrdinal(registro.despacho) : ''} DESPACHO
                            <td style="font-size:12px; padding: 5px 5px !important;">${registro.apellido_paterno || ''} ${registro.apellido_materno || ''} ${registro.nombres || ''}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">${tipoTexto || ''}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">${registro.descripcionescrito || ''}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">${registro.dependenciapolicial || ''}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">${registro.remitente || ''}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">${registro.carpetafiscal || ''}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">${registro.folios || ''}</td>
                            <td style="font-size:12px; padding: 5px 5px !important;">
                            ${iconoDetalle}
                            </td>
                        </tr>
                    `);


                
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
                alert('HUBO UN ERROR AL CONSULTAR ESCRITOS.');
            }
        }        
    });

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
function mostrarDetalle(anio, mes, codigo) {
    const pdfUrl = `../../storage/app/mesapartes/${anio}/${mes}/${codigo.toUpperCase()}.pdf`;
    $('#pdfViewer').attr('src', pdfUrl);
    $('#pdfModal').modal('show');
}
</script>
@endsection
