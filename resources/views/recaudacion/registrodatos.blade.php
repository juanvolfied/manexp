@extends('menu.index')

@section('content')
<?php
function numeroAOrdinal($numero) {
    $ordinales = [0 => '',1 => '1er',2 => '2do',3 => '3er',4 => '4to',5 => '5to',6 => '6to',7 => '7mo',8 => '8vo',9 => '9no',10 => '10mo',11 => '11er',];
    return $ordinales[$numero] ?? $numero . 'º';
}
?>
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
    <!-- Mostrar el mensaje de �xito o error -->
    <form id="miFormulario" autocomplete="off">
        @csrf
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">
                    @if(isset($regcab))
                        Actualizar Solicitud de Carpetas : {{ str_pad($regcab->nro_mov, 5, '0', STR_PAD_LEFT) }}-{{ $regcab->ano_mov }}-{{ $regcab->tipo_mov == 'SO' ? 'S' : $regcab->tipo_mov }}
                        <span style='color:red;' > {{ $regcab->estado_mov == 'Z' ? "(RECHAZADO EN ARCHIVO)" : "" }}</span>
                    @else
                        Recaudaci&oacute;n: Registrar Carpetas
                    @endif    
                        
                    </div>
                  </div>
                  <div class="card-body">

                    <div class="row mb-3" >
                        <div class="col-md-3 col-lg-2" style="width:200px !important;">
                        <label for="iddependencia" class="form-label"><b>Id Dependencia Carpeta</b></label>
                        <select name="iddependencia" id="iddependencia" class="" data-live-search="true">
                            <option value=""></option>
                            @foreach ($iddependencias as $iddep)
                            <option value="{{ $iddep->id_dependencia }}">
                                {{ $iddep->id_dependencia }}
                            </option>			    
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-1" style="width:120px !important;">
                        <label for="ano" class="form-label"><b>A&nacute;o</b></label>
                        <input type="text" id="ano" name="ano" class="form-control text-center" maxlength="4" >
                        </div>
                        <div class="col-md-1" style="width:120px !important;">
                        <label for="nro" class="form-label"><b>N&uacute;mero</b></label>
                        <input type="text" id="nro" name="nro" class="form-control text-center" maxlength="6" >
                        </div>
                        <div class="col-md-1">
                        <label for="tipo" class="form-label"><b>Tipo</b></label>
                        <input type="text" id="tipo" name="tipo" class="form-control text-center" maxlength="4" style="width:50px;">
                        </div>
                    </div>



                    <div class="row mb-3" >
                      <div class="col-md-6 col-lg-6" >
                        <div class="form-group" style="padding:0px;">
                          <label for="dependencia"><b>Dependencia:</b></label>
                            <select name="dependencia" id="dependencia" class="" data-live-search="true">
                                <option value=""></option>
                                @foreach ($dependencias as $datos)
                                <option value="{{ $datos->id_dependencia }}">{{ $datos->descripcion }}</option>			    
                                @endforeach
                            </select>
                        </div>
                      </div>
                    </div>

                    <div class="row" >
                      <div class="col-md-3 col-lg-2" style="width:200px !important;" >
                        <div class="form-group" style="padding:0px;">
                          <label for="despacho"><b>Despacho:</b></label>
                            <select name="despacho" id="despacho" class="form-select form-control" >
                                <option value=""></option>
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
                            </select>
                        </div>
                      </div>
                      <div class="col-md-2 col-lg-2" style="width:120px !important;">
                        <div class="form-group" style="padding:0px;">
                          <label for="voucher"><b>Voucher?:</b></label>
                            <select name="voucher" id="voucher" class="form-select form-control" style="width:75px;">
                                <option value=""></option>
                                <option value="S">SÍ</option>
                                <option value="N">NO</option>
                            </select>
                        </div>
                      </div>
                        <div class="col-md-2" id="monto_container" style="display:none;">
                        <label for="monto" class="form-label"><b>Monto</b></label>
                        <input type="text" id="monto" name="monto" class="form-control" maxlength="7" style="width:100px;">
                        </div>
                    </div>




                  </div>

	            <div class="container mt-0">


                </div>
        
                <div class="card-action">
                    <!--<button class="btn btn-success">Grabar</button>
                    <button class="btn btn-danger">Cancel</button>-->
        	    <!--<button id="grabarBtn" class="btn btn-primary">Inventariar c&oacute;digos escaneados</button>-->
        	    <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#textoModal">Inventariar c&oacute;digos escaneados</button>-->
        	    <button type="button" class="btn btn-primary" onclick="prepararYMostrarModal()">
                        Grabar Registro
                </button>
                <a href="{{ route('recaudacion.indexregistro') }}" class="btn btn-secondary">Regresar al Listado de Carpetas</a>

                </div>


                </div>
            </div>

            
<!-- Modal -->
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR GRABACION</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body text-center">
        <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
        <p class="fw-bold">
            ¿DESEA VERIFICAR LOS DATOS ANTES DE GUARDAR?
        </p>
        <p>
            Presione <b>Cancelar</b> para revisar la información.<br>
            Presione <b>Continuar</b> para guardar los datos.
        </p>
      </div>
      
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" onclick="guardarTexto()">Continuar y Grabar Inventario</button>-->
        <button type="button" id="grabarBtn" class="btn btn-primary">Continuar y Grabar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
</div>



    </form>

<style>
    .selectize-dropdown, .selectize-input, .selectize-input input {
        font-size: 12px!important;  /* O cualquier valor que desees */
    }
    .selectize-input {
        padding: 4px 4px!important;  
    }    
</style>
@endsection

@section('scripts')
<script>
    $('#iddependencia').selectize();
    $('#dependencia').selectize();
</script>

<script>
    const voucher = document.getElementById('voucher');
    const montoContainer = document.getElementById('monto_container');
    const montoInput = document.getElementById('monto');

    voucher.addEventListener('change', function () {
        if (this.value === 'S') { // SÍ
            montoContainer.style.display = 'block';
            montoInput.disabled = false;
        } else {
            montoContainer.style.display = 'none';
            montoInput.disabled = true;
            montoInput.value = ''; // limpia el valor
        }
    });
</script>
<script>
    var input = document.getElementById('monto');

    var maskOptions = {
        mask: Number,
        scale: 2,              // 2 decimales
        signed: false,
        thousandsSeparator: '', // separador de miles ','
        padFractionalZeros: true,
        normalizeZeros: true,
        radix: '.',             // separador decimal
        mapToRadix: [','],      // permite usar coma
        min: 0,
        max: 9999.99            // 4 enteros + 2 decimales
    };

    var mask = IMask(input, maskOptions);

    IMask(document.getElementById('nro'), {
        mask: Number,
        scale: 0,        // sin decimales
        signed: false,
        thousandsSeparator: '',
        min: 0,
        max: 999999      // 6 dígitos
    });
    IMask(document.getElementById('ano'), {
        mask: Number,
        scale: 0,        // sin decimales
        signed: false,
        thousandsSeparator: '',
        min: 0,
        max: 9999      // 4 dígitos
    });
    IMask(document.getElementById('tipo'), {
        mask: Number,
        scale: 0,        // sin decimales
        signed: false,
        thousandsSeparator: '',
        min: 0,
        max: 9      // 1 dígitos
    });
</script>

<script>
document.getElementById("miFormulario").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Esto previene que el formulario se env�e cuando se presiona Enter
    }
});


function prepararYMostrarModal() {
  if (event) event.preventDefault(); // Previene recarga
  if (document.getElementById('iddependencia').value=="") {
    alert(" Seleccione ID Dependencia");
    return false;
  }
  if (document.getElementById('ano').value=="") {
    alert("Ingrese año");
    return false;
  }
  if (document.getElementById('nro').value=="") {
    alert("ingrese numero");
    return false;
  }
  if (document.getElementById('tipo').value=="") {
    alert("Ingrese tipo");
    return false;
  }
  if (document.getElementById('dependencia').value=="") {
    alert("Seleccione dependencia");
    return false;
  }
  if (document.getElementById('despacho').value=="") {
    alert("Seleccione despacho");
    return false;
  }
  if (document.getElementById('voucher').value=="") {
    alert("Seleccione si existe o no voucher");
    return false;
  } else {
    if (document.getElementById('voucher').value=="S") {
        if (document.getElementById('monto').value=="" || document.getElementById('monto').value==0) {
            alert("Ingrese el monto");
            return false;
        }
    }
  }
    const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
    myModal.show();
}

document.getElementById("grabarBtn").addEventListener("click", function(event) {
    if (event) event.preventDefault(); // Previene recarga

        let iddep = document.getElementById("iddependencia").value;
        let anoexp = document.getElementById("ano").value;
        let nroexp = document.getElementById("nro").value;
        let tipo = document.getElementById("tipo").value;
        let dependencia = document.getElementById("dependencia").value;
        let despacho = document.getElementById("despacho").value;
        let voucher = document.getElementById("voucher").value;
        let monto = document.getElementById("monto").value;

    const modalEl = document.getElementById('textoModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
        
        $.ajax({
            url: '{{ route("recaudacion.registrograba") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                iddep: iddep,
                anoexp: anoexp,
                nroexp: nroexp,
                tipo: tipo,
                dependencia: dependencia,
                despacho: despacho,
                voucher: voucher,
                monto: monto,
            },            
            success: function(response) {
                let mensaje = response.message || 'Respuesta sin mensaje';                
                if (response.success) {
        document.getElementById("iddependencia").value="";
        document.getElementById("ano").value="";
        document.getElementById("nro").value="";
        document.getElementById("tipo").value="";
        document.getElementById("despacho").value="";
        document.getElementById("voucher").value="";
        document.getElementById("monto").value="";
        var selectize = $('#iddependencia')[0].selectize;
        selectize.clear();
        var selectize = $('#dependencia')[0].selectize;
        selectize.clear();

                    $('#messageOK').html('<b>' + mensaje + '</b>');
                    messageOK.style.opacity = '1';
                    messageOK.style.display = 'block';
                    setTimeout(function() {
                        messageOK.style.display = 'none';
                    }, 4000);
                } else {
                    $('#messageErr').html('<b>' + mensaje + '</b>');
                    messageErr.style.opacity = '1';
                    messageErr.style.display = 'block';
                    setTimeout(function() {
                        messageErr.style.display = 'none';
                    }, 4000);                 
                }
            },
            error: function(xhr) {
                //$('#mensaje-guardar').html('<div style="color: red;">Error inesperado</div>');
                //console.error(xhr.responseText);
            }
        });


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

</script>
@endsection
