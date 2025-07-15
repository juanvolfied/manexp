@extends('menu.index') 

@section('content')

    <!--<h2 class="mb-4">Seguimiento de Expedientes</h2>-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Consulta de escritos</div>
        </div>
        <div class="card-body table-responsive">

    <form id="form-filtros" class="row g-3" autocomplete="off">
        @csrf
        <div class="col-md-2">
            <label for="fechaini" class="form-label"><b>Fecha</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="fechaini" name="fechaini" class="form-control text-center" value="{{ old('fechaini', date('Y-m-d')) }}" style="width: 120px;" >
            </div>
        </div>
        <div class="col-md-2">
            <label for="fechafin" class="form-label"><b>Fecha</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="fechafin" name="fechafin" class="form-control text-center" value="{{ old('fechafin', date('Y-m-d')) }}" style="width: 120px;" >
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="mostrarescritos(event)" class="btn btn-primary w-100">Mostrar Escrito(s)</a>
        </div>        
<!--        <div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="generapdf(event)" class="btn btn-primary w-100">Imprimir Escritos</a>
        </div>        -->
    </form>

    <div class="mt-5">
        <table id="scanned-list" class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">#</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fecha</th>
                    @if(Auth::user()->personal->fiscal_asistente === "A")
                        <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fiscal</th>
                    @endif
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tipo</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Descripci&oacute;n</th>
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
        alert ("INGRESA LA FECHA FIN");
        return false;
    }

    $.ajax({
        url: '{{ route("mesapartes.consultaescritosdetalle") }}', 
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
                    let extraColumn = '';
                    const fiscalAsistente = "{{ Auth::user()->personal->fiscal_asistente }}";
                    if (fiscalAsistente === "A") {
                        extraColumn = `<td style="font-size:12px; padding: 5px 10px !important;">${registro.apellido_paterno || ''} ${registro.apellido_materno || ''} ${registro.nombres || ''}</td>`;
                    }

                    tableBody.append(`
                        <tr>
                            <td style="font-size:12px; padding: 5px 10px !important;">${index + 1}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.fecharegistro || ''}</td>
                            ${extraColumn}
                            <td style="font-size:12px; padding: 5px 10px !important;">${tipoTexto || ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.descripcion || ''}</td>
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
