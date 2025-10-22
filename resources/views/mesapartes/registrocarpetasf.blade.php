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
                <div class="card-title">Creaci&oacute;n de Carpeta Fiscales</div>
                </div>
                <div class="card-body">

                    <div class="row" id="codigoverificar">
                      <div class="col-md-12 col-lg-12">
                        <div class="form-group border p-3 rounded shadow-sm bg-light">                
                        <h5 class="text-primary"><i class="fas fa-tasks"></i> Seleccione Opciones y Presione Bot&oacute;n Continuar</h5>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label for="fecha" class="form-label"><b>Fecha: </b></label>
                                <input type="date" name="fecha" id="fecha" class="form-control" required
                                    value="{{ $fecha ??  date('Y-m-d')  }}">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label"><b>Seleccione Dependencia:</b></label>
                                <select name="dependencia" id="dependencia" class="" >
                                <option value="">-- Seleccione --</option>
                                @foreach($dependencias as $p)
                                <option value="{{ $p->id_dependencia }}" >{{ $p->descripcion }} </option>
                                @endforeach
                                </select>                    
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label for="despacho" class="form-label"><b>Despacho: </b></label>
                                <select name="despacho" id="despacho" class="form-select" >
                                    <option value="">-- Seleccione --</option>
                                    <option value="0">DESPACHO</option>
                                    <option value="1">1er. DESPACHO</option>
                                    <option value="2">2do. DESPACHO</option>
                                    <option value="3">3er. DESPACHO</option>
                                    <option value="4">4to. DESPACHO</option>
                                    <option value="5">5to. DESPACHO</option>
                                    <option value="6">6to. DESPACHO</option>
                                    <option value="7">7mo. DESPACHO</option>
                                    <option value="8">8vo. DESPACHO</option>
                                    <option value="9">9no. DESPACHO</option>
                                    <option value="10">10mo. DESPACHO</option>
                                    <option value="11">11er. DESPACHO</option>
                                    <option value="12">12do. DESPACHO</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="motivo" class="form-label"><b>MOTIVO: </b></label>
                                <select name="motivo" id="motivo" class="form-select" >
                                    <option value="">-- Seleccione --</option>
                                    <option value="1">TURNO CORPORATIVA</option>
                                    <option value="2">TURNO DESPACHO</option>
                                    <option value="3">TURNO CERRO</option>
                                    <option value="4">DERIVACIÓN</option>
                                    <option value="5">DERIVACIÓN APOYO</option>
                                    <option value="6">REASIGNACIÓN</option>
                                    <option value="7">REASIGNACIÓN APOYO</option>
                                    <option value="8">ACUMULACIÓN</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <a href="#" onclick="buscapordetalle(event)" class="btn btn-primary w-100"><i class="fas fa-arrow-right me-1"></i> Continuar</a>
                            </div>
                        </div>                
                      </div>
                    </div>

                  </div>
                </div>

            </div>
          </div>
        </div>
        

            <div class="row" id="datadeta" style="display: none;">            
              <div class="col-md-12">
                <div class="card">
                  
                <div class="card-header">
                <div class="card-title">Creaci&oacute;n de Carpeta Fiscales</div>
                </div>
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-9 col-lg-9">
                        <div class="form-group border p-3 rounded shadow-sm bg-light">
                        <h5 class="text-primary"><i class="fas fa-tasks"></i> Opciones de registro</h5>
                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <b>Fecha:</b> <span id="datafecha">2025/12/12</span> 
                                </div>
                                <div class="col-md-9 col-lg-9">
                                    <b>Dependencia:</b> <span id="datadependencia">Nombre dependencia</span> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <b>Despacho:</b> <span id="datadespacho">12</span> 
                                </div>
                                <div class="col-md-9 col-lg-9">
                                    <b>Motivo:</b> <span id="datamotivo">Turno Corporativa</span> 
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <a href="#" onclick="retornaopciones()" class="btn btn-secondary mt-3"><i class="fas fa-level-up-alt me-1"></i> Seleccionar otras opciones</a>
                        </div>
                    </div><br>

                    <div class="row" id="codigoverificar">
                      <div class="col-md-4 col-lg-4">
                        <div class="form-group border p-3 rounded shadow-sm bg-light">
                            <!--<h5 class="text-primary"><i class="fas fa-barcode"></i> Por Código de Carpeta Fiscal</h5>
                            <p class="text-muted small"><b>Ingrese el código, y presione Enter o el botón.</b></p>-->

                          <label for="codbarras"><b>C&oacute;digo de Carpeta Fiscal</b></label>
                          
                          <div class="input-group">

                          <input type="text" class="form-control" name="codbarras" id="codbarras" placeholder="C&oacute;d. Carpeta Fiscal" onkeydown="verificarEnter(event)" autofocus/>
                          <button class="btn btn-primary" style="padding:0px 1rem!important; z-index: 1;" type="button" onclick="limpiarCodigoBarra()">
                          <i class="fas fa-check me-1"></i> Verificar
                          </button>

                          </div>
                          <small id="msgerr" class="form-text text-muted text-danger" style="display:none;">Escanee con lector o digite el codigo y presione enter.</small>

                        </div>
                        @error('codbarras') <div class="text-danger"><b>{{ $message }}</b></div> @enderror

                      </div>
                      <div class="col-md-5 col-lg-5">
                        <div class="btns-container" style="display: flex; gap: 10px; align-items: center;">

                        <!--<button type="submit" class="btn btn-success mt-3">Guardar</button>-->
                        <button type="button" onclick="grabar()" class="btn btn-success mt-3" id="btngrabar" style="display:none;"><i class="fas fa-save me-1"></i> Guardar Carpeta</button>

                        </div>
                      </div>
                    </div><br>




        

                    <div class="row" id="codigoverificar">
                      <div class="col-md-12 col-lg-12">
                        <div class="form-group border p-3 rounded shadow-sm bg-light">    
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <h5 class="text-primary">Carpetas registradas</h5>
                                </div>
                                <div class="col-md-6 col-lg-6 text-end">
                                    <input type="hidden" id="codigocf" name="codigocf">
                                    <button type="button" onclick="imprimirpdf()" class="btn  " style="background-color: #6c757d; color: white;" id="btnimprimir"><i class="fas fa-print me-1"></i> Imprimir</button>
                                </div>
                            </div>    
        <table id="tablacarpetassgf" class="table table-striped table-bordered" width=100%>
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Nro Carpeta Fiscal</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">C&oacute;digo</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">A&ntilde;o</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">N&uacute;mero</th>
                </tr>
            </thead>
            <tbody style="font-size:11px;">

            </tbody>
        </table>

                        </div>
                      </div>
                    </div>


<style>
    .selectize-dropdown, .selectize-input, .selectize-input input {
        font-size: 11px!important;  /* O cualquier valor que desees */
    }
    .selectize-input {
        padding: 4px 4px!important;  
    }    

#tablacarpetassgf tbody td {
  padding: 5px 10px !important;
  font-size: 11px !important;
  text-transform: none !important;
}

</style>








    

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



@section('scripts')
<script>
//  $(document).ready(function() {
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
            "lengthMenu": "Mostrar _MENU_ paquetes",    // Cambia "Show entries" por "Mostrar entradas"
            "info": "Mostrando _START_ a _END_ de _TOTAL_ paquetes", // Cambia el texto de la información
            "zeroRecords": "No se encontraron registros", // Mensaje cuando no hay resultados
            "infoEmpty": "Mostrando 0 a 0 de 0 paquetes", // Cuando la tabla está vacía
            "infoFiltered": "(filtrado de _MAX_ paquetes totales)", // Cuando hay filtros activos
      
            // Personaliza "Previous" y "Next" en la paginación
            "paginate": {
              "previous": "Anterior",   // Cambia "Previous" por "Anterior"
              "next": "Siguiente"       // Cambia "Next" por "Siguiente"
            },
      
            // Personaliza el texto de "Showing entries"
            "emptyTable": "No hay datos disponibles en la tabla", // Mensaje si no hay datos
      }      
    });


    

//  });
</script>
<script>

var element = document.getElementById('codbarras');
var maskOptions = {
  mask: '0000000000000000000000000'
};
var mask = IMask(element, maskOptions);



document.getElementById("miFormulario").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
    }
});
document.getElementById('codbarras').addEventListener('input', function () {
    document.getElementById('btngrabar').style.display = 'none';
});

    $('#dependencia').selectize();
    let ultimaLectura = '';
    let tiempoUltimaLectura = 0;    
    function verificarEnter(event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter

            const ahora = Date.now();
            let codigo = document.getElementById("codbarras").value;
            // Si el mismo código fue enviado hace menos de 1000ms, ignorar
            if (codigo === ultimaLectura && (ahora - tiempoUltimaLectura) < 1000) {
            document.getElementById("codbarras").value='';
            document.getElementById('codbarras').focus();   
            return;
            }
            ultimaLectura = codigo;
            tiempoUltimaLectura = ahora;

            limpiarCodigoBarra();
        }
    }

    function limpiarCodigoBarra() {
        let valor = document.getElementById("codbarras").value;
        valor = valor.trim();
        document.getElementById("codbarras").value = valor;
        if (valor.length !== 25) {
            alert("El c\u00F3digo de barras " + valor + " no es v\u00E1lido. Solo tiene "+ valor.length +" caracteres.");
            return false;
        }
        const codbarras = valor;

        $.ajax({
            url: '{{ route("mesapartes.buscacarpeta") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                codbarras: codbarras
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {

                    document.getElementById('btngrabar').style.display = 'block';

                } else {
                    document.getElementById("codbarras").value = "";
                    document.getElementById('codbarras').focus();   

                    document.getElementById('messageErr').innerHTML ="<b>"+ mensaje + "</b>";
                    var messageErr = document.getElementById('messageErr');
                    messageErr.style.opacity = '1';
                    messageErr.style.display = 'block';
                    setTimeout(function() {
                        messageErr.style.opacity = '0';
                        setTimeout(() => {
                            messageErr.style.display = 'none';
                        }, 500);
                    }, 3000); 

                }
            },
            error: function(xhr) {
                //$('#mensaje-guardar').html('<div style="color: red;">Error inesperado</div>');
                //console.error(xhr.responseText);
            }
        });

    }

    function buscapordetalle() {

        let fech = document.getElementById("fecha").value;
        let depe = document.getElementById("dependencia").value;
        let desp = document.getElementById("despacho").value;
        let moti = document.getElementById("motivo").value;
        
        if (fech==""){
            alert("Ingrese la Fecha");
            return false;
        }
        if (depe==""){
            alert("Seleccione la dependencia");
            return false;
        }
        if (desp==""){
            alert("Seleccione el despacho");
            return false;
        }
        if (moti==""){
            alert("Seleccione el motivo");
            return false;
        }

        let xdepe = document.getElementById("dependencia");
        let descdepe = xdepe.options[xdepe.selectedIndex].text;
        let xdesp = document.getElementById("despacho");
        let descdesp = xdesp.options[xdesp.selectedIndex].text;        
        let xmoti = document.getElementById("motivo");
        let descmoti = xmoti.options[xmoti.selectedIndex].text;        

        document.getElementById('datafecha').innerHTML=fech;
        document.getElementById('datadependencia').innerHTML=descdepe;
        document.getElementById('datadespacho').innerHTML=descdesp;
        document.getElementById('datamotivo').innerHTML=descmoti;


        $.ajax({
            url: '{{ route("mesapartes.buscacarpetasf") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                fech: fech,
                depe: depe,
                desp: desp,
                moti: moti
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {
                    var registros = response.registros;

                    tabla.clear(); // Limpiar antes de agregar
                    registros.forEach(function(registro) {
                        let carpfiscal=registro.carpetafiscal;
                        let idde = carpfiscal.substring(8, 11); 
                        let anio = carpfiscal.substring(11, 15); 
                        let expe = parseInt(carpfiscal.substring(15, 21)); 

                        tabla.row.add([carpfiscal, idde, anio, expe]);
                    });
                    tabla.draw();

                    document.getElementById('datacabe').style.display = 'none';
                    document.getElementById('datadeta').style.display = 'block';
                } 
                document.getElementById('codigocf').value=response.codigo;

            },
            error: function(xhr, status, error) {
                        if (xhr.status === 419) {
                            // No autorizado - probablemente sesi�n expirada
                            alert('TU SESION HA EXPIRADO. SERAS REDIRIGIDO AL LOGIN.');
                            window.location.href = '{{ route("usuario.login") }}';
                        } else {
                            // Otro tipo de error
                            console.error('Error en la petici�n:', xhr.status);
                            alert('Hubo un error al grabar.');
                        }
            }
        });

    }
    function retornaopciones() {
        document.getElementById('datacabe').style.display = 'block';
        document.getElementById('datadeta').style.display = 'none';
    }
    function grabar() {
        let fech = document.getElementById("fecha").value;
        let depe = document.getElementById("dependencia").value;
        let desp = document.getElementById("despacho").value;
        let moti = document.getElementById("motivo").value;
        let codi = document.getElementById("codbarras").value;

        $.ajax({
            url: '{{ route("mesapartes.grabacarpeta") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                fech: fech,
                depe: depe,
                desp: desp,
                moti: moti,
                codi: codi
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {

                    document.getElementById('messageOK').innerHTML ="<b>"+ mensaje + "</b>";
                    var messageOK = document.getElementById('messageOK');
                    messageOK.style.opacity = '1';
                    messageOK.style.display = 'block';
                    setTimeout(function() {
                        messageOK.style.opacity = '0';
                        setTimeout(() => {
                            messageOK.style.display = 'none';
                        }, 500);
                    }, 3000); 
                    var registros = response.registros;

                    tabla.clear(); // Limpiar antes de agregar
                    registros.forEach(function(registro) {
                        let carpfiscal=registro.carpetafiscal;
                        let idde = carpfiscal.substring(8, 11); 
                        let anio = carpfiscal.substring(11, 15); 
                        let expe = parseInt(carpfiscal.substring(15, 21)); 

                        tabla.row.add([carpfiscal, idde, anio, expe]);
                    });
                    tabla.draw();

                    document.getElementById('btngrabar').style.display = 'none';
                    document.getElementById("codbarras").value = "";
                    document.getElementById('codbarras').focus();   

                    document.getElementById('codigocf').value=response.codigo;
                    
                } else {
                    document.getElementById('messageErr').innerHTML ="<b>"+ mensaje + "</b>";
                    var messageErr = document.getElementById('messageErr');
                    messageErr.style.opacity = '1';
                    messageErr.style.display = 'block';
                    setTimeout(function() {
                        messageErr.style.opacity = '0';
                        setTimeout(() => {
                            messageErr.style.display = 'none';
                        }, 500);
                    }, 3000); 
                }
            },
            error: function(xhr, status, error) {
                        if (xhr.status === 419) {
                            // No autorizado - probablemente sesi�n expirada
                            alert('TU SESION HA EXPIRADO. SERAS REDIRIGIDO AL LOGIN.');
                            window.location.href = '{{ route("usuario.login") }}';
                        } else {
                            // Otro tipo de error
                            console.error('Error en la petici�n:', xhr.status);
                            alert('Hubo un error al grabar.');
                        }
            }

        });

    }
    function imprimirpdf() {
        const codigocf = document.getElementById('codigocf').value;
        if (codigocf=="") {
            alert("DATOS NO DISPONIBLES");
            return;
        }
        const basePdfUrl = @json(route('mesapartes.imprimecarpetasf', ['codigocf' => '__CODIGO__']));
        const url = basePdfUrl
            .replace('__CODIGO__', encodeURIComponent(codigocf))

        if (event) event.preventDefault(); // Previene recarga    
        $('#pdfFrame').attr('src', url);
        $('#pdfModal').modal('show');
    }

</script>


@endsection
