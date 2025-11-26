@extends('menu.index') 

@section('content')

    <!--<h2 class="mb-4">Seguimiento de Expedientes</h2>-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Consulta de Ingresos/Salidas de Veh&iacute;culos por Intervalo de Fechas</div>
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
            <a href="#" onclick="mostrarescritos(event)" class="btn btn-primary w-100">Iniciar Consulta</a>
        </div>        
        <!--<div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="generapdf(event)" class="btn btn-primary w-100">Imprimir Escritos</a>
        </div>-->        
    </form>

    <div class="mt-5">
        <table id="scanned-list" class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 5px!important; font-size:11px !important; text-transform:none;">#</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Tipo</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Conductor</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Placa</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Veh&iacute;culo</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Kilometraje</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Observaci&oacute;n</th>
                </tr>
            </thead>
            <tbody style="font-size:11px;" >
            </tbody>
        </table>        
    </div>

            </div><!--table responsive-->
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
        alert ("INGRESA LA FECHA FINAL");
        return false;
    }

    $.ajax({
        url: '{{ route("transporte.consultaintervalodetalle") }}', 
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

                    tableBody.append(`
                        <tr>
                            <td style="font-size:11px; padding: 5px 5px !important;">${index + 1}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.fechahora_registro || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">
                            ${ registro.tipo_mov === "I" ? "Ingreso" : registro.tipo_mov === "S" ? "Salida" : "" }
                            </td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.apellido_paterno || ''} ${registro.apellido_materno || ''} ${registro.nombres || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.placa || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.marca || ''} ${registro.modelo || ''} ${registro.color || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.kilometraje || ''}</td>
                            <td style="font-size:11px; padding: 5px 5px !important;">${registro.observacion || ''}</td>
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
                alert('HUBO UN ERROR AL CONSULTAR MOVIMIENTOS.');
            }
        }        
    });

}
</script>

@endsection
