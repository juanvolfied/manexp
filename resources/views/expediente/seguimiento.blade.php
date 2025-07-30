@extends('menu.index') 

@section('content')

    <!--<h2 class="mb-4">Seguimiento de Expedientes</h2>-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">B&uacute;squeda y Seguimiento de Carpetas Fiscales</div>
        </div>
        <div class="card-body table-responsive">

    <form id="form-filtros" class="row g-3" autocomplete="off">
        @csrf
        <div class="col-md-1">
            <label for="dependencia" class="form-label"><b>A&ntilde;o</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="text" id="ano" name="ano" class="form-control text-center" maxlength="4" style="width: 70px;" >
            </div>
        </div>
        <div class="col-md-2">
            <label for="dependencia" class="form-label"><b>Nro Expediente</b></label>
            <div class="d-flex align-items-center gap-2">
                <input type="text" id="nroexp" name="nroexp" class="form-control text-center" maxlength="6" style="width: 80px;" >
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="#" onclick="mostrarcarpetas(event)" class="btn btn-primary w-100">Mostrar Carpeta(s)</a>
        </div>        
        <div class="col-md-5 d-flex align-items-end" id="nroexpediente" style="font-size:20px;font-weight:bold; color:red;">
            
        </div>        
    </form>

    <div class="mt-5">
        <table id="scanned-list" class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">C&oacute;digo Barras</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Dependencia</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">A&ntilde;o</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Nro expediente</th>			      
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tipo</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Ver Detalle</th>
                </tr>
            </thead>
            <tbody style="font-size:12px;" >
            </tbody>
        </table>        
        <!--
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
            </tbody>
        </table>        
-->
    </div>

        </div>
    </div>
    
    
<!-- Modal -->
<div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content custom-modal-height">
      
      <div class="modal-header">
        <h5 class="modal-title" id="miModalLabel">Detalle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body" id="detalleseguimiento">
          <table id="detalleexp" class="table table-striped table-sm">
              <thead class="thead-dark">
                  <tr>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fecha Movimiento</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Nro Inventario</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Ubicacion</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tipo Ubicacion</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Dependencia</th>			      
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Despacho</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Archivo</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Anaquel</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Nro Paquete</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tomo</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Activo</th>
                  </tr>
              </thead>
              <tbody style="font-size:12px;" >
		<!-- Los datos escaneados se irán añadiendo aquí -->
              </tbody>
          </table>        
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <!--<button type="button" class="btn btn-primary">Guardar cambios</button>-->
      </div>

    </div>
  </div>
</div>
<style>
  .custom-modal-height {
    height: 60vh; /* 60% del alto de la pantalla */
  }
  .custom-modal-height .modal-body {
    overflow-y: auto; /* Scroll interno si el contenido excede el alto */
    flex: 1 1 auto;   /* Hace que crezca en el espacio disponible */
  }
</style>

@endsection

@section('scripts')

<script>
function mostrarcarpetas(event) {
            const tableBody = $('#scanned-list tbody');
            const tableBodycel = $('#scanned-listcel tbody');
            tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla
            tableBodycel.empty(); // Limpiar la tabla antes de volver a renderizarla

    document.getElementById('nroexpediente').innerHTML="";

    if (event) event.preventDefault(); // Previene recarga
    const ano = document.getElementById('ano').value;
    const nroexp = document.getElementById('nroexp').value;
    if ( ano=="" && nroexp=="" ) {
        alert ("EL NRO DE EXPEDIENTE NO ESTA INGRESADO CORRECTAMENTE");
        return false;
    }

    $.ajax({
        url: '{{ route("expediente.buscacarpetas") }}', 
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            ano_expediente: ano,
            nro_expediente: nroexp
        },
        success: function(response) {
            if (response.success) {                                
                //document.getElementById('nroexpediente').innerHTML="Nro Expediente: "+dependencia+"-"+ano+"-"+nroexp+"-"+idtipo;
                var registros = response.registros;
                registros.forEach(function(registro, index) {                
                    var codbarras = registro.codbarras;
                    var dependencia = registro.id_dependencia;
                    var ano = registro.ano_expediente;
                    var nroexpediente = registro.nro_expediente;
                    var tipo = registro.id_tipo;
                
                    tableBody.append(`
                        <tr>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.codbarras}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.id_dependencia}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.ano_expediente}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.nro_expediente}</td>
                            <td style="font-size:12px; padding: 5px 10px !important;">${registro.id_tipo}</td>                        
                            <td style="font-size:12px; padding: 5px 10px !important;">
                            <a href="#" onclick="mostrardetalle('${registro.id_expediente}', event)" title="Ver detalle"><i class="fas fa-search"></i></a> 
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
                alert('Hubo un error al buscar nro inventario.');
            }
        }        
    });

}
function mostrardetalle(idexp, event) {
            const tableBody = $('#detalleexp tbody');
            const tableBodycel = $('#detalleexpcel tbody');
            tableBody.empty(); // Limpiar la tabla antes de volver a renderizarla
            tableBodycel.empty(); // Limpiar la tabla antes de volver a renderizarla

    //document.getElementById('nroexpediente').innerHTML="";

    if (event) event.preventDefault(); // Previene recarga

    $.ajax({
        url: '{{ route("expediente.segdetalle") }}', 
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id_expediente: idexp
        },
        success: function(response) {
            if (response.success) {                                
                //document.getElementById('nroexpediente').innerHTML="Nro Expediente: "+dependencia+"-"+ano+"-"+nroexp+"-"+idtipo;
                var registros = response.registros;
                var nroreg=0;
                var datoreg="";
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
                    nroreg++;
                    if (nroreg==1){
                        datoreg=dependencia+"-"+ano+"-"+nroexpediente+"-"+tipo;
                    }
                    const estiloExtra = index === 0 ? 'font-weight:bold; color:green;' : '';
                
                    tableBody.append(`
                        <tr>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.fecha_movimiento} ${registro.hora_movimiento}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.nro_inventario}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.ubicacion=="D" ? "Despacho" : (registro.ubicacion=="A" ? "Archivo" : "")}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.tipo_ubicacion=="T" ? "Transito" : (registro.tipo_ubicacion=="I" ? "Inventario" :"")}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.abreviado}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${numeroAOrdinal(registro.despacho)} DESPACHO</td>                        
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.archivo ?? ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.anaquel ?? ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.nro_paquete ?? ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.tomo ?? ''}</td>
                            <td style="font-size:12px; padding: 5px 10px !important; ${estiloExtra}">${registro.activo ?? ''}</td>
                        </tr>
                    `);
                
                
                });



                document.getElementById('miModalLabel').innerHTML = 'Detalle de Seguimiento Carpeta Fiscal <span style="color:red">'+datoreg+'</span>';
                var miModal = new bootstrap.Modal(document.getElementById('miModal'));
                miModal.show();


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
        10: '10mo',
        11: '11er'
    };

    return ordinales[numero] || numero + ' ';
}
</script>
@endsection
