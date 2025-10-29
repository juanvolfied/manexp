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
                                <label for="ingresopor" class="form-label"><b>Ingreso por: </b></label>
                                <select name="ingresopor" id="ingresopor" class="form-select" onchange="cambiadependencia(this.value)">
                                    <option value="">-- Seleccione --</option>
                                    <option value="1">TURNO CORPORATIVA</option>
                                    <option value="2">TURNO CERRO</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label"><b>Dependencia:</b></label>
                                <select name="dependencia" id="dependencia" class="" onchange="cambiaenviadoa(this.value)">
                                    <!--<option value="">-- Seleccione --</option>-->
                                @php
                                    $dependenciascorp = '<option value="">-- Seleccione --</option>';
                                    $dependenciascerr = '';
                                    foreach ($dependencias as $p) {
                                        $dependenciascorp .= '<option value="'. $p->id_dependencia .'">'. e($p->descripcion) .'</option>';
                                        if (in_array($p->id_dependencia, [34, 38, 42])) {
                                            $dependenciascerr .= '<option value="'. $p->id_dependencia .'">'. e($p->descripcion) .'</option>';
                                        }
                                    }
                                @endphp                                
                                <!--@foreach($dependencias as $p)
                                <option value="{{ $p->id_dependencia }}" >{{ $p->descripcion }} </option>
                                @endforeach-->
                                </select>                    
                            </div>
                            <div class="col-md-3">
                                <label for="enviadoa" class="form-label"><b>Enviado a: </b></label>
                                <select name="enviadoa" id="enviadoa" class="form-select" onchange="displaymotivo(this.value)">
                                </select>
                            </div>
                        </div>                

                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label for="fecha" class="form-label"><b>Fecha: </b></label>
                                <input type="date" name="fecha" id="fecha" class="form-control" required
                                    value="{{ $fecha ??  date('Y-m-d')  }}">
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
                                <div class="col-md-4 col-lg-4">
                                    <b>Ingreso por:</b> <span id="dataingresopor">Turno Corporativa</span> 
                                </div>
                                <div class="col-md-8 col-lg-8">
                                    <b>Dependencia:</b> <span id="datadependencia">Nombre dependencia</span> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <b>Enviado a:</b> <span id="dataenviadoa">Despacho X</span> 
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <b>Fecha:</b> <span id="datafecha">2025/12/12</span> 
                                </div>
                                <!--<div class="col-md-4 col-lg-4" id="divmotivo">
                                    <b>Motivo:</b> <span id="datamotivo">Derivación/etc</span> 
                                </div>-->
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
                            <div class="col-md-3 col-lg-3">
                                <label id="lblmotivo" for="motivo" class="form-label" style="display:none;"><b>MOTIVO: </b></label>
                                <select name="motivo" id="motivo" class="form-select" style="display:none;">
                                    <option value="">-- Seleccione --</option>
                                    <option value="1">DERIVACIÓN</option>
                                    <option value="2">ACUMULACIÓN</option>
                                    <option value="3">VIRTUAL</option>
                                    <option value="4">NUEVA</option>
                                    <option value="5">REASIGNACIÓN</option>
                                </select>
                            </div>
                      <div class="col-md-2 col-lg-2">
                        <div class="btns-container" style="display: flex; gap: 10px; align-items: center;">

                        <!--<button type="submit" class="btn btn-success mt-3">Guardar</button>-->
                        <button type="button" onclick="grabar()" class="btn btn-success mt-3" id="btngrabar" style="display:none;"><i class="fas fa-save me-1"></i> Guardar Carpeta</button>

                        </div>
                      </div>
                      <div class="col-md-3 col-lg-3 text-center">
                        <h4 class="text-danger" id="canreg"></h4>
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
                                    <button id="botonimprimir" type="button" onclick="imprimirpdf()" class="btn  " style="background-color: #6c757d; color: white;" id="btnimprimir"><i class="fas fa-print me-1"></i> Imprimir Turno Corporativa</button>
                                </div>
                            </div>    
        <table id="tablacarpetassgf" class="table table-striped table-bordered" width=100%>
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">#</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Nro Carpeta Fiscal</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Fecha Registro</th>
                    <th style="padding: 5px 10px!important; font-size: 11px !important; text-transform:none;">Motivo</th>
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
$('#dependencia').selectize({
    allowEmptyOption: true
});

function cambiadependencia(valor) {
    document.getElementById("lblmotivo").style.display = 'none';
    document.getElementById("motivo").style.display = 'none';
    document.getElementById('botonimprimir').classList.remove('d-inline-block');    
    document.getElementById('botonimprimir').classList.add('d-none');

    const select = document.getElementById("enviadoa");
    select.innerHTML = ``;
        const selectize = $('#dependencia')[0].selectize;
        selectize.clear();
        selectize.clearOptions();
        const opciones = {!! json_encode($dependencias) !!}; // array desde PHP

    if (valor=="1") {
        selectize.addOption({ value: '0', text: '-- Seleccione --' });
        opciones.forEach(opt => {
            selectize.addOption({ value: opt.id_dependencia, text: opt.descripcion });
        });
        selectize.refreshOptions(false);
        setTimeout(() => selectize.setValue('0'), 0);

        document.getElementById('botonimprimir').classList.remove('d-none');
        document.getElementById('botonimprimir').classList.add('d-inline-block');    
        return;
    }

    if (valor=="2") {
        const idsPermitidos = [34, 38, 42];
        opciones.forEach(opt => {
            if (idsPermitidos.includes(opt.id_dependencia)) {
                selectize.addOption({ value: opt.id_dependencia, text: opt.descripcion });
            }
        });
        selectize.refreshOptions(false);
        //setTimeout(() => selectize.setValue('0'), 0);


        select.innerHTML = `
            <option value="">-- Seleccione --</option>
            <option value="C1">Coordinación 1ra</option>
            <option value="C2">Coordinación 2da</option>
            <option value="C3">Coordinación 3ra</option>
        `;
        let codenva = "";
        let canenva = 0;
        let ingp = document.getElementById("ingresopor").value;

        $.ajax({
            url: '{{ route("mesapartes.buscatcerro") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                //depe: depe,
                ingp: ingp,
            },
            success: function(response) {
                codenva = response.codienviadoa;
                canenva = response.cantenviadoa;
                fechain = response.fechainicio;

                if (codenva=="C1") { 
                    select.innerHTML = `<option value="C1" selected>Coordinación 1ra</option>`;
                    setTimeout(() => selectize.setValue('34'), 0); 
                }
                if (codenva=="C2") { 
                    select.innerHTML = `<option value="C2" selected>Coordinación 2da</option>`;
                    setTimeout(() => selectize.setValue('38'), 0); 
                }
                if (codenva=="C3") { 
                    select.innerHTML = `<option value="C3" selected>Coordinación 3ra</option>`;
                    setTimeout(() => selectize.setValue('42'), 0); 
                }
                document.getElementById("fecha").value = fechain;
                document.getElementById("enviadoa").value = codenva;
                displaymotivo(codenva);            


                //document.getElementById('codigocf').value=response.codigo;
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
}
function cambiaenviadoa(valor) {
    let ingp = document.getElementById("ingresopor").value;
    const select = document.getElementById("enviadoa");
    select.innerHTML = ``;
    if (ingp=="1") {
        let depe = document.getElementById("dependencia").value;
        select.innerHTML = `
            <option value="">-- Seleccione --</option>
            <option value="01">1er. Despacho</option>
            <option value="02">2do. Despacho</option>
            <option value="03">3er. Despacho</option>
            <option value="04">4to. Despacho</option>
            <option value="05">5to. Despacho</option>
            <option value="06">6to. Despacho</option>
            <option value="07">7mo. Despacho</option>
            <option value="08">8vo. Despacho</option>
            <option value="09">9no. Despacho</option>
            <option value="10">10mo. Despacho</option>
            <option value="11">11er. Despacho</option>
            <option value="12">12do. Despacho</option>
        `;
        if (depe==34) {
            const option = document.createElement("option");
            option.value = "C1";
            option.text = "Coordinación 1ra";
            select.appendChild(option);
        }        
        if (depe==38) {
            const option = document.createElement("option");
            option.value = "C2";
            option.text = "Coordinación 2da";
            select.appendChild(option);
        }        
        if (depe==42) {
            const option = document.createElement("option");
            option.value = "C3";
            option.text = "Coordinación 3ra";
            select.appendChild(option);
        }        
    }
    if (ingp=="2") {
        if (valor==34) { select.innerHTML = `<option value="C1" selected>Coordinación 1ra</option>`; }
        if (valor==38) { select.innerHTML = `<option value="C2" selected>Coordinación 2da</option>`; }
        if (valor==42) { select.innerHTML = `<option value="C3" selected>Coordinación 3ra</option>`; }
    }
}


function displaymotivo(valor) {
    document.getElementById("lblmotivo").style.display = 'none';
    document.getElementById("motivo").style.display = 'none';
    if (valor=="C1" || valor=="C2" || valor=="C3") {
        document.getElementById("lblmotivo").style.display = 'block';
        document.getElementById("motivo").style.display = 'block';
    }
}
</script>




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
let motivos = ["","DERIVACIÓN", "ACUMULACIÓN", "VIRTUAL", "NUEVA", "REASIGNACIÓN"];

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

        let ingp = document.getElementById("ingresopor").value;
        let enva = document.getElementById("enviadoa").value;
        
        if (fech==""){
            alert("Ingrese la Fecha");
            return false;
        }
        if (depe==""){
            alert("Seleccione la dependencia");
            return false;
        }
        if (ingp==""){
            alert("Seleccione el tipo de ingreso");
            return false;
        }
        if (enva==""){
            alert("Seleccione una opción enviado a");
            return false;
        }

        let xdepe = document.getElementById("dependencia");
        let descdepe = xdepe.options[xdepe.selectedIndex].text;
        let xingp = document.getElementById("ingresopor");
        let descingp = xingp.options[xingp.selectedIndex].text;        
        let xenva = document.getElementById("enviadoa");
        let descenva = xenva.options[xenva.selectedIndex].text;             

        document.getElementById('datafecha').innerHTML=fech;
        document.getElementById('datadependencia').innerHTML=descdepe;
        document.getElementById('dataingresopor').innerHTML=descingp;
        document.getElementById('dataenviadoa').innerHTML=descenva;

        $.ajax({
            url: '{{ route("mesapartes.buscacarpetasf") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                fech: fech,
                depe: depe,
                ingp: ingp,
                enva: enva,
            },
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';
                if (response.success) {
                    var registros = response.registros;

                    tabla.clear(); // Limpiar antes de agregar
                    let contador = 1; // Inicializamos el contador
                    registros.forEach(function(registro) {
                        let carpfiscal=registro.carpetafiscal;
                        let idde = carpfiscal.substring(8, 11); 
                        let anio = carpfiscal.substring(11, 15); 
                        let expe = parseInt(carpfiscal.substring(15, 21)); 
                        let motivo=registro.motivo;
                        let fechareg=registro.fechahora_registro;

                        tabla.row.add([contador, carpfiscal, fechareg, motivos[motivo], idde, anio, expe]);
                        contador++;
                    });
                    tabla.draw();
                    document.getElementById('canreg').innerHTML = response.registros.length + '<br>Carpetas';


                    document.getElementById('datacabe').style.display = 'none';
                    document.getElementById('datadeta').style.display = 'block';
                    document.getElementById("codbarras").value="";
                    document.getElementById("motivo").value="";
                    document.getElementById('btngrabar').style.display = 'none';
                } 
                //document.getElementById('codigocf').value=response.codigo;

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
        let ingp = document.getElementById("ingresopor").value;
        let enva = document.getElementById("enviadoa").value;

        let moti = document.getElementById("motivo").value;
        let codi = document.getElementById("codbarras").value;

        if (enva=="C1" || enva=="C2" || enva=="C3"){
            if (moti==""){
                alert("Seleccione el motivo");
                return false;
            }
        }

        $.ajax({
            url: '{{ route("mesapartes.grabacarpeta") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                fech: fech,
                depe: depe,
                ingp: ingp,
                enva: enva,
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
                    let contador = 1; // Inicializamos el contador
                    registros.forEach(function(registro) {
                        let carpfiscal=registro.carpetafiscal;
                        let idde = carpfiscal.substring(8, 11); 
                        let anio = carpfiscal.substring(11, 15); 
                        let expe = parseInt(carpfiscal.substring(15, 21)); 
                        let motivo=registro.motivo;
                        let fechareg=registro.fechahora_registro;

                        tabla.row.add([contador, carpfiscal, fechareg, motivos[motivo], idde, anio, expe]);
                        contador++;
                    });
                    tabla.draw();
                    document.getElementById('canreg').innerHTML = response.registros.length + '<br>Carpetas';

                    let enviretorno = response.enviretorno;
                    const opciones = {
                        C1: "Coordinación 1ra",
                        C2: "Coordinación 2da",
                        C3: "Coordinación 3ra"
                    };
                    if (enva !== enviretorno && opciones[enviretorno]) {
                        const select = document.getElementById("enviadoa");
                        select.innerHTML = `<option value="${enviretorno}" selected>${opciones[enviretorno]}</option>`;

                        let xenva = document.getElementById("enviadoa");
                        let descenva = xenva.options[xenva.selectedIndex].text;             
                        document.getElementById('dataenviadoa').innerHTML=descenva;
                    }



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
        let fech = document.getElementById("fecha").value;
        let depe = document.getElementById("dependencia").value;
        let ingp = document.getElementById("ingresopor").value;
        let enva = document.getElementById("enviadoa").value;
        let tpre ='TCOF';
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

</script>


@endsection
