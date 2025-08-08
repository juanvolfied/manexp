@extends('menu.index') 

@section('content')

    <!--<h2 class="mb-4">Seguimiento de Expedientes</h2>-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Consulta de escritos por Fecha y Fiscal</div>
        </div>
        <div class="card-body table-responsive">

    <form id="form-filtros" class="row g-3" autocomplete="off">
        @csrf
        <div class="col-md-6 col-lg-6">
            <div class="form-group" style="padding:5px;">
                <label for="fiscal" class="form-label"><b>Fiscal</b></label>

                <select name="fiscal" id="fiscal" class="">
                        <option value="">-- Seleccione --</option>
                        @foreach($fiscales as $p)
                            <option value="{{ $p->id_personal }}" {{ old('fiscal', $libroescritos->id_fiscal ?? '') == $p->id_personal ? 'selected' : '' }}>
                                {{ $p->apellido_paterno ." ". $p->apellido_materno ." ". $p->nombres }} 
                            </option>
                        @endforeach
                            </select>

                @error('fiscal') <div class="text-danger">{{ $message }}</div> @enderror
            </div>       
<!--            <div class="form-group" style="padding:5px;font-size:12px; color:blue;" id="descdependencia">
                {{ isset($libroescritos) ? $libroescritos->descripcion : '' }}
            </div>-->
            <input type="hidden" id="id_dependencia" name="id_dependencia">
            <input type="hidden" id="despacho" name="despacho">
        </div>
        <div class="col-md-2">
            <label for="fechareg" class="form-label"><b>Fecha</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="fechareg" name="fechareg" class="form-control text-center" value="{{ old('fechareg', date('Y-m-d')) }}" style="width: 120px;" >
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="mostrarescritos(event)" class="btn btn-primary w-100">Mostrar Escrito(s)</a>
        </div>        
        <div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="generapdf(event)" class="btn btn-primary w-100">Imprimir Escritos</a>
        </div>        
    </form>

    <div class="mt-5">
        <table id="scanned-list" class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">#</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">C&oacute;digo</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tipo</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Descripci&oacute;n</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Dependencia Origen</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Remitente</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Carpeta Fiscal</th>			      
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Folios</th>			      
                </tr>
            </thead>
            <tbody style="font-size:12px;" >
            </tbody>
        </table>        
    </div>

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
        <iframe id="pdfFrame" src="" width="100%" height="600px" style="border: none;"></iframe>
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

    .selectize-dropdown {
        z-index: 9999 !important; /* fuerza que se vea por encima de todo */
    }    
    /*.selectize-dropdown, .selectize-input, .selectize-input input {
        font-size: 11px!important;  
    }
    .selectize-input {
        padding: 4px 4px!important;  
    }*/    
</style>
@section('scripts')
<script>
/*  const iddependencia = @json($fiscales->pluck('id_dependencia', 'id_personal'));
  const descdependencia = @json($fiscales->pluck('descripcion', 'id_personal'));
  const despacho = @json($fiscales->pluck('despacho', 'id_personal'));

    $('#fiscal').selectize({
        onChange: function(value) {
            // Solo ejecuta la función si hay un valor seleccionado
            if (value) {
                muestradato(value);
            }
        }
    });*/

    $('#fiscal').selectize();
</script>    

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
    const fiscal = document.getElementById('fiscal').value;
    const fechareg = document.getElementById('fechareg').value;
    if ( fiscal=="" ) {
        alert ("SELECCIONE FISCAL");
        return false;
    }
    if ( fechareg=="" ) {
        alert ("INGRESA LA FECH DE REGISTRO");
        return false;
    }

    $.ajax({
        url: '{{ route("mesapartes.consultadetalle") }}', 
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            fiscal: fiscal,
            fechareg: fechareg
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

                    tableBody.append(`
                        <tr>
                            <td style="font-size:12px; padding: 5px 10px !important;">${index + 1}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.codescrito || ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${tipoTexto || ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.descripcion || ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.dependenciapolicial || ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.remitente || ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.carpetafiscal || ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.folios || ''}</td>
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
        10: '10mo'
    };

    return ordinales[numero] || numero + ' ';
}
</script>
@endsection
