@extends('menu.index')

@section('content')
<?php
function numeroAOrdinal($numero) {
    $ordinales = [0 => '',1 => '1er',2 => '2do',3 => '3er',4 => '4to',5 => '5to',6 => '6to',7 => '7mo',8 => '8vo',9 => '9no',10 => '10mo',11 => '11er',];
    return $ordinales[$numero] ?? $numero . 'º';
}
?>
    <!-- Mostrar el mensaje de �xito o error -->
    <form id="miFormulario" autocomplete="off">
    @if(session('messageErr'))
        <div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease;"><b>{{ session('messageErr') }}</b></div>
    @else
        <div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    
    @endif
    @if(session('messageOK'))
        <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('messageOK') }}</b></div>
    @else
        <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
    @endif    
        @csrf
            <div class="row" id="datascan">            

            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">
                    @if(isset($regcab))
                        Actualizar Devoluci&oacute;n de Carpetas : {{ str_pad($regcab->nro_mov, 5, '0', STR_PAD_LEFT) }}-{{ $regcab->ano_mov }}-{{ $regcab->tipo_mov == 'DE' ? 'D' : $regcab->tipo_mov }}
                        <span style='color:red;' > {{ $regcab->estado_mov == 'Z' ? "(RECHAZADO EN ARCHIVO)" : "" }}</span>
                    @else
                        Generar nueva Devoluci&oacute;n de Carpetas
                    @endif    
                        
                    </div>
                  </div>
                  <div class="card-body">

                    @if(isset($obsmovimiento))
                    <div class="row">
                      <div class="col-md-12 col-lg-12" >
                        <div class="form-group" style="padding:5px; color:red;" >
                            <b>OBSERVACION DE RECHAZO :  {{ $obsmovimiento->observacion }}</b>
                        </div>
                      </div>
                    </div>
                    @endif    


                    <div class="row">
                      <div class="col-md-6 col-lg-6" >
                        <div class="form-group" style="padding:5px;">
                          <label for="fiscal"><b>Fiscal:</b></label>
                            <select name="fiscal" id="fiscal" class="" data-live-search="true">
                                <option value=""></option>
                                @foreach ($personal as $fiscal)
                                <option value="{{ $fiscal->id_personal }}" {{ old('id_personal', $regcab->fiscal ?? null) == $fiscal->id_personal ? 'selected' : '' }}>
                                    {{ $fiscal->apellido_paterno }} {{ $fiscal->apellido_materno }} {{ $fiscal->nombres }}
                                </option>			    
                                @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6" >
                        <div class="form-group" style="padding:5px;">
                    @if(Auth::user()->personal->fiscal_asistente == "F" || Auth::user()->personal->fiscal_asistente =="A")                            
                            <b>DEPENDENCIA : </b> {{ optional($dependencia)->descripcion }}<br>
                            <b>DESPACHO : </b> {{ numeroAOrdinal(Auth::user()->personal->despacho) }} DESPACHO
                    @else
                            <b>NECESITA SER FISCAL O ASISTENTE<br>
                    @endif    
                        </div>
                      </div>
                    </div>

                  </div>

	          <div class="container mt-0">

<h5>CARPETAS PRESTADAS A ESTA DEPENDENCIA - DESPACHO</h5>
            <table id="carpetas" class="table table-striped table-sm">
                <thead class="thead-dark">
                  <tr>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Nro Expediente</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Imputado</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Agraviado</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Delito</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Folios</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Marca para<br>Devoluci&oacute;n</th>
                  </tr>
                </thead>
                <tbody style="font-size:12px;">
                    @foreach($datos as $index => $p)
                        @if($p->otrasolicitud)
                        <tr>
                            <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->id_dependencia }}-{{ $p->ano_expediente }}-{{ $p->nro_expediente }}-{{ $p->id_tipo }}</td>
                            <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->imputado }}</td>
                            <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->agraviado }}</td>
                            <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->desc_delito }}</td>
                            <td style="padding: 5px 10px!important; font-size: 12px !important;">{{ $p->nro_folios }}</td>
                            <td style="padding: 5px 10px!important; font-size: 12px !important;">
                                <input type="checkbox" 
                                    value="{{ $p->id_expediente }}" 
                                    data-codbarras="{{ $p->codbarras }}"
                                    data-id_dependencia="{{ $p->id_dependencia }}"
                                    data-ano_expediente="{{ $p->ano_expediente }}"
                                    data-nro_expediente="{{ $p->nro_expediente }}"
                                    data-id_tipo="{{ $p->id_tipo }}"
                                    data-imputado="{{ $p->imputado }}"
                                    data-agraviado="{{ $p->agraviado }}"
                                    data-desc_delito="{{ $p->desc_delito }}"
                                    data-nro_folios="{{ $p->nro_folios }}">
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>

            </table>
            <input type="hidden" id="totalregistros" value="{{ $datos->count() }}">


                  </div>
        
                  <div class="card-action">
                    <!--<button class="btn btn-success">Grabar</button>
                    <button class="btn btn-danger">Cancel</button>-->
        	    <!--<button id="grabarBtn" class="btn btn-primary">Inventariar c&oacute;digos escaneados</button>-->
        	    <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#textoModal">Inventariar c&oacute;digos escaneados</button>-->
        	    <button type="button" class="btn btn-primary" onclick="prepararYMostrarModal()">
                    @if(isset($regcab))
                        Actualizar Devoluci&oacute;n de Carpeta
                    @else
                        Generar Devoluci&oacute;n de Carpeta
                    @endif    
                </button>
                <a href="{{ route('devolucion.index') }}" class="btn btn-secondary">Regresar al Listado de Devoluciones</a>

            </div>
                </div>
              </div>
            </div>
            
    </form>
    <form action="{{ isset($regcab) ? route('devolucion.update', ['tipo_mov' => $regcab->tipo_mov, 'ano_mov' => $regcab->ano_mov, 'nro_mov' => $regcab->nro_mov]) : route('devolucion.graba') }}"
      method="POST" id="miFormulario2" autocomplete="off">

    @csrf  <!-- Este campo incluir� el token CSRF autom�ticamente -->
    @if(isset($regcab))
        @method('PUT')
    @endif
    <input type="hidden" id="scannedItemsInput" name="scannedItems">
	          <input type="hidden" id="codfiscal" name="codfiscal">

<!-- Modal -->
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR GRABACION</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        SE VA A GRABAR EL MOVIMIENTO DE DEVOLUCI&Oacute;N DE CARPETAS FISCALES, CONFIRMA LA GRABACI&Oacute;N ?
      </div>      
      <div class="modal-footer">
        <button type="button" id="grabarBtn" class="btn btn-primary">Continuar y Grabar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
</div>






    </form>




<style>
    .selectize-dropdown, .selectize-input, .selectize-input input {
        font-size: 11px!important;  /* O cualquier valor que desees */
    }
    .selectize-input {
        padding: 4px 4px!important;  
    }    
</style>
@endsection

@section('scripts')
<script>
    $('#fiscal').selectize();
</script>



<script>
document.getElementById("miFormulario").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
    }
});
    
let scannedItems = []; // Array para almacenar los c�digos escaneados


$(document).ready(function () {
    $('#carpetas tbody input[type="checkbox"]').on('change', function () {
        obtenerSeleccionados();
    });
});
function obtenerSeleccionados() {
    scannedItems = [];
    document.querySelectorAll('#carpetas tbody input[type="checkbox"]:checked').forEach(function(checkbox) {
        let codbarras = checkbox.dataset.codbarras;
        let dependencia = checkbox.dataset.id_dependencia;
        let ano = checkbox.dataset.ano_expediente;
        let nroexpediente = checkbox.dataset.nro_expediente;
        let tipo = checkbox.dataset.id_tipo;
        let id_expediente = checkbox.value;
        let imputado = checkbox.dataset.imputado;
        let agraviado = checkbox.dataset.agraviado;
        let desc_delito = checkbox.dataset.desc_delito;
        nro_folios = checkbox.dataset.nro_folios;
        scannedItems.push({ codbarras, dependencia, ano, nroexpediente, tipo, id_expediente, imputado, agraviado, desc_delito, nro_folios});
    });
    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);
}



    
function prepararYMostrarModal() {
  if (event) event.preventDefault(); // Previene recarga
  if (document.getElementById('fiscal').value=="") {
    alert("SELECCIONE FISCAL");
    return false;
  }
  document.getElementById('codfiscal').value=document.getElementById('fiscal').value;
  if (scannedItems.length > 0) {
      const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
      myModal.show();
  } else {
      document.getElementById('messageErr').innerHTML = '<b>NO PUEDES GENERAR MOVIMIENTO DE DEVOLUCION, SELECCIONA LAS CARPETAS A DEVOLVER</b>';
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
}

document.getElementById("grabarBtn").addEventListener("click", function(event) {
    if (event) event.preventDefault(); // Previene recarga
    if (scannedItems.length > 0) {
        event.preventDefault();  // Prevenir el comportamiento por defecto
        document.getElementById("miFormulario2").submit();
    } else {
        document.getElementById('messageErr').innerHTML = '<b>NO PUEDES GENERAR MOVIMIENTO DE DEVOLUCION, SELECCIONA LAS CARPETAS A DEVOLVER</b>';
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

});
            

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


    const detalles = @json($regdet ?? []);
    detalles.forEach(function(registro) {
        var codbarras = registro.codbarras;
        var dependencia = registro.id_dependencia;
        var ano = registro.ano_expediente;
        var nroexpediente = registro.nro_expediente;
        var tipo = registro.id_tipo;
        var id_expediente = registro.id_expediente;
        var imputado = registro.imputado;
        var agraviado = registro.agraviado;
        var desc_delito = registro.desc_delito;
        var nro_folios = registro.nro_folios;

        scannedItems.unshift({ codbarras, dependencia, ano, nroexpediente, tipo, id_expediente, imputado, agraviado, desc_delito, nro_folios});
    });
    document.getElementById('scannedItemsInput').value = JSON.stringify(scannedItems);

</script>
@endsection
