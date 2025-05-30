@extends('menu.index') 

@section('content')

    <!--<h2 class="mb-4">Seguimiento de Expedientes</h2>-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Seguimiento de Expedientes</div>
        </div>
        <div class="card-body table-responsive">

    <form id="form-filtros" class="row g-3" autocomplete="off">
        @csrf
        <div class="col-md-5">
            <label for="dependencia" class="form-label"><b>Expediente</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="text" id="dependencia" name="dependencia" class="form-control text-center" maxlength="11" style="width: 120px;" >
                <span>-</span>
                <input type="text" id="ano" name="ano" class="form-control text-center" maxlength="4" style="width: 70px;" >
                <span>-</span>
                <input type="text" id="nroexp" name="nroexp" class="form-control text-center" maxlength="6" style="width: 80px;" >
                <span>-</span>
                <input type="text" id="idtipo" name="idtipo" class="form-control text-center" maxlength="4" style="width: 60px;" >
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="mostrardetalle(event)" class="btn btn-primary w-100">Mostrar Seguimiento</a>
        </div>        
        <div class="col-md-5 d-flex align-items-end" id="nroexpediente" style="font-size:20px;font-weight:bold; color:red;">
            
        </div>        
    </form>

    <div class="mt-5">
        <table id="scanned-list" class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fecha Movimiento</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Ubicacion</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tipo Ubicacion</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Dependencia</th>			      
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Despacho</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Archivo</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Anaquel</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Nro Paquete</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Activo</th>
                </tr>
            </thead>
            <tbody style="font-size:12px;" >
    <!-- Los datos escaneados se irán añadiendo aquí -->
            </tbody>
        </table>        

    </div>

        </div>
    </div>    
@endsection

@section('scripts')
<script>
function mostrardetalle(event) {
            const tableBody = $('#scanned-list tbody');
            const tableBodycel = $('#scanned-listcel tbody');
            tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla
            tableBodycel.empty(); // Limpiar la tabla antes de volver a renderizarla

    document.getElementById('nroexpediente').innerHTML="";

    if (event) event.preventDefault(); // Previene recarga
    const dependencia = document.getElementById('dependencia').value;
    const ano = document.getElementById('ano').value;
    const nroexp = document.getElementById('nroexp').value;
    const idtipo = document.getElementById('idtipo').value;
    if (dependencia=="" || ano=="" || nroexp=="" || idtipo=="") {
        alert ("EL NRO DE EXPEDIENTE NO ESTA INGRESADO CORRECTAMENTE");
        return false;
    }

    $.ajax({
        url: '{{ route("expediente.segdetalle") }}', 
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id_dependencia: dependencia,
            ano_expediente: ano,
            nro_expediente: nroexp,
            id_tipo: idtipo
        },
        success: function(response) {
            if (response.success) {                                
                document.getElementById('nroexpediente').innerHTML="Nro Expediente: "+dependencia+"-"+ano+"-"+nroexp+"-"+idtipo;
                var registros = response.registros;
                registros.forEach(function(registro, index) {                
                    var codbarras = registro.codbarras;
                    var dependencia = registro.id_dependencia;
                    var ano = registro.ano_expediente;
                    var nroexpediente = registro.nro_expediente;
                    var tipo = registro.id_tipo;
                    var estado = registro.estado;
                    if (estado=="L") {
                        var lafecha = registro.fecha_lectura;
                        var lahora = registro.hora_lectura;
                    }
                    if (estado=="I") {
                        var lafecha = registro.fecha_inventario;
                        var lahora = registro.hora_inventario;
                    }
                    const estiloExtra = index === 0 ? 'font-weight:bold; color:green;' : '';
                
                    tableBody.append(`
                        <tr>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.fecha_movimiento} ${registro.hora_movimiento}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.ubicacion=="D" ? "Despacho" : (registro.ubicacion=="A" ? "Archivo" : "")}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.tipo_ubicacion=="T" ? "Transito" : (registro.tipo_ubicacion=="I" ? "Inventario" :"")}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.abreviado}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${numeroAOrdinal(registro.despacho)} DESPACHO</td>                        
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.archivo ?? ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.anaquel ?? ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.nro_paquete ?? ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.activo ?? ''}</td>
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
                alert('Hubo un error al buscar nro inventario.');
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
