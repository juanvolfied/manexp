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
        <div class="row g-3 align-items-center">

            <div class="col-md-3">
                <label class="form-label"><b>Filtro por Placa</b></label>
                <div class="input-group">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="checkbox" id="check1" style="border: 2px solid #007bff; box-shadow: 0 0 3px rgba(0,123,255,0.5);" {{ !empty($placa) ? 'checked' : '' }}>
                    </div>
                    <input type="text" class="form-control" name="placa" id="placa" value="{{ $placa ?? '' }}" placeholder="XXX-123" {{ empty($placa) ? 'disabled' : '' }}>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label"><b>Filtro por Conductor</b></label>
                <div class="input-group">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="checkbox" id="check2" style="border: 2px solid #007bff; box-shadow: 0 0 3px rgba(0,123,255,0.5);" {{ !empty($idconductor) ? 'checked' : '' }}>
                    </div>
                    <!--
                    <input type="text" class="form-control" name="descripcion" id="descripcion" value="{{ $descripcion ?? '' }}" placeholder="Ingrese descripci&oacute;n" {{ empty($descripcion) ? 'disabled' : '' }}>
-->
                    <select class="form-select" name="id_conductor" id="id_conductor" {{ empty($idconductor) ? 'disabled' : '' }}>
                        <option value="">Seleccione conductor</option>
                        @foreach($conductores as $c)
                            <option
                                value="{{ $c->id_conductor }}"
                                {{ ($idconductor ?? '') == $c->id_conductor ? 'selected' : '' }}>
                                {{ $c->apellido_paterno }}
                                {{ $c->apellido_materno }}
                                {{ $c->nombres }}
                            </option>
                        @endforeach
                    </select>                    
                </div>
            </div>

        </div>

        <div class="col-md-2">
            <label for="fechaini" class="form-label"><b>Fecha Inicial</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="fechaini" name="fechaini" class="form-control text-center" value="{{ old('fechaini', $fechaini ?? date('Y-m-d')) }}" style="width: 120px;" >
            </div>
        </div>
        <div class="col-md-2">
            <label for="fechafin" class="form-label"><b>Fecha Final</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="date" id="fechafin" name="fechafin" class="form-control text-center" value="{{ old('fechafin', $fechafin ?? date('Y-m-d')) }}" style="width: 120px;" >
            </div>
        </div>
        <!--<div class="col-md-2">
            <label for="fechafin" class="form-label"><b>Agrupar por conductor</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="checkbox" id="chkagrupar" name="chkagrupar" >
            </div>
        </div>-->
        <div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="mostrarescritos(event)" class="btn btn-primary w-100">Iniciar Consulta</a>
        </div>        
        <!--<div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="generapdf(event)" class="btn btn-primary w-100">Imprimir Escritos</a>
        </div>-->        
    </form>

    <div class="mt-5">
<!--
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
-->
        <table id="tablamov" class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Placa</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Conductor</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Ruta</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha Salida</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Km Salida</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Fecha Entrada</th>
                    <th style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">Km Entrada</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos ?? [] as $m)
                <tr>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">{{ $m->placa }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">{{ $m->conductor }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">{{ $m->observacion }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">{{ $m->fecha_salida }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">{{ $m->kilometraje_salida }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">{{ $m->fecha_entrada }}</td>
                    <td style="padding: 5px 5px!important; font-size: 11px !important; text-transform:none;">{{ $m->kilometraje_entrada }}</td>
                </tr>
                @empty
                @endforelse
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
        position: relative; 
        z-index: 1;
    }
</style>
@section('scripts')

<script>
    function toggleInput(checkboxId, inputId) {
        const checkbox = document.getElementById(checkboxId);
        const input = document.getElementById(inputId);
        checkbox.addEventListener('change', function () {
            input.disabled = !this.checked;
            if (!this.checked) {
                input.value = ''; // Limpiar el input si se desactiva
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function () {
        toggleInput('check1', 'placa');
        toggleInput('check2', 'id_conductor');
    });

$(document).ready(function () {
    $('#tablamov').DataTable({
      "pageLength": 20,  // Número de filas por página
      "lengthMenu": [10, 25, 50, 100],  // Opciones de paginación
      "searching": false,  // Habilitar búsqueda
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


function mostrarescritos(event) {
            const tableBody = $('#scanned-list tbody');
            const tableBodycel = $('#scanned-listcel tbody');
            tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla
            tableBodycel.empty(); // Limpiar la tabla antes de volver a renderizarla

    if (event) event.preventDefault(); // Previene recarga
    const fechaini = document.getElementById('fechaini').value;
    const fechafin = document.getElementById('fechafin').value;
    //let check = document.getElementById('chkagrupar');
    //let agrupar = check.checked ? 1 : 0;

    let checkplaca = document.getElementById('check1');
    let filtroplaca = checkplaca.checked ? 1 : 0;
    let placa = document.getElementById('placa').value;

    let checkconductor = document.getElementById('check2');
    let filtroconductor = checkconductor.checked ? 1 : 0;
    let idconductor = document.getElementById('id_conductor').value;

    if ( checkplaca.checked ) {
        if ( placa=="" ) {
            alert ("INGRESA LA PLACA A FILTRAR");
            return false;
        }
    }
    if ( checkconductor.checked ) {
        if ( idconductor=="" ) {
            alert ("SELECCIONE EL CONDUCTOR A FILTRAR");
            return false;
        }
    }

    if ( fechaini=="" ) {
        alert ("INGRESA LA FECHA INICIO");
        return false;
    }
    if ( fechafin=="" ) {
        alert ("INGRESA LA FECHA FINAL");
        return false;
    }
agrupar=0;
const form = document.createElement('form');
form.method = 'POST';
form.action = '{{ route("transporte.consultaintervalodetalle") }}';
form.innerHTML = `
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="fechaini" value="${fechaini}">
    <input type="hidden" name="fechafin" value="${fechafin}">
    <input type="hidden" name="filtroplaca" value="${filtroplaca}">
    <input type="hidden" name="placa" value="${placa}">
    <input type="hidden" name="filtroconductor" value="${filtroconductor}">
    <input type="hidden" name="idconductor" value="${idconductor}">
    <input type="hidden" name="agrupar" value="${agrupar}">
`;
document.body.appendChild(form);
form.submit();


return false;

    $.ajax({
        url: '{{ route("transporte.consultaintervalodetalle") }}', 
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            fechaini: fechaini,
            fechafin: fechafin,
            filtroplaca: filtroplaca,
            placa: placa,
            agrupar: agrupar
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
