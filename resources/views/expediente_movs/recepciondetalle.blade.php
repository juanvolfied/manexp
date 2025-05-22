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
    ];    
    return $ordinales[$numero] ?? $numero . 'º';
}    
?>  
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificaci&oacute;n de Gu&iacute;a de Internamiento a recepcionar</title>    
  </head>
<body>
<form autocomplete="off">
    <!--<div class="container mt-4">-->
    @if(session('messageerr'))
        <div id="messageErr" class="alert alert-danger text-danger">{{ session('messageerr') }}</div>
    @else
        <div id="messageErr" class="alert alert-danger text-danger" style="display:none;"></div>
    @endif
    @if(session('success'))
        <div id="messageOK" class="alert alert-success">{{ session('success') }}</div>
    @else
        <div id="messageOK" class="alert alert-success" style="display:none;"></div>
    @endif

            <div class="row">            
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Gu&iacute;a de Internamiento a recepcionar</div>
                  </div>
                  <div class="card-body">
<!--        <h1 class="mb-4">Seguimiento de Registro de Inventario</h1>-->


                    <div class="row">
                      <div class="col-md-12 col-lg-12">
                          <table width="100%"><tr><td width="100px;"><b>Movimiento:</b></td><td id="datarch">{{ $guiacab->tipo_mov }} {{ $guiacab->ano_mov }}-{{ $guiacab->nro_mov }}</td></tr></table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-lg-12">
                          <table width="100%"><tr><td width="100px;"><b>Fiscal:</b></td><td id="datanaq">{{ $guiacab->apellido_paterno . " " . $guiacab->apellido_materno . " " . $guiacab->nombres }}</td></tr></table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-lg-12">
                          <table width="100%"><tr><td width="100px;"><b>Dependencia:</b></td><td id="datanaq">{{ $guiacab->descripcion }}</td></tr></table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-lg-12">
                          <table width="100%"><tr><td width="100px;"><b>Despacho:</b></td><td id="datanaq">{{ numeroAOrdinal($guiacab->despacho) }} DESPACHO</td></tr></table>
                      </div>
                    </div>
                    

                    <div class="row" style="background-color:#F2F5A9;">
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group">
                          <label for="codbarras"><b>C&oacute;digo de Barras</b></label>
                          <input type="text" class="form-control" name="codbarras" id="codbarras" placeholder="C&oacute;digo de Barras" autofocus/>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group">
                          <br>
                          <a href="#" onclick="prepararYMostrarModal('{{ $guiacab->tipo_mov }}',{{ $guiacab->ano_mov }},{{ $guiacab->nro_mov }},event)" class="btn btn-success">RECEPCIONAR</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <a href="{{ route('internamiento.recepcion') }}" class="btn btn-danger">Cancelar</a>
                        </div>
                      </div>

                    </div>

        <!-- Tabla con clases Bootstrap -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;" width="40">#</th>			      
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">C&oacute;digo de Barras</th>			      
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Dependencia</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">A&ntilde;o</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Nro Exp</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tipo</th>
                  <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none; text-align:center;" width="100">Verificado</th>
                </tr>
            </thead>
            <tbody id="tabla-codigos">
                @foreach ($segdetalle as $datos)
                    <tr data-codigo="{{ $datos->codbarras }}">
                      <td style="font-size:13px; padding: 5px 10px !important;">{{ $loop->iteration }}</td> <!-- Correlativo -->
                      <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->codbarras }}</td>
                      <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->id_dependencia }}</td>
                      <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->ano_expediente }}</td>
                      <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->nro_expediente }}</td>
                      <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->id_tipo }}</td>                        
                      <td style="font-size:13px; padding: 5px 10px !important; text-align:center;" class="estado text-success fw-bold"></td>                        
                    </tr>
                @endforeach
            </tbody>
        </table>

                  </div>
                </div>
              </div>
            </div>

    <!--</div>-->





    
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">CONFIRMAR RECEPCI&Oacute;N</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        DESEA CONTINUAR CON LA RECEPCI&Oacute;N DE LA GU&Iacute;A DE INTERNAMIENTO?
      </div>
      <input type='hidden' id='tpmov' name='tpmov'>
      <input type='hidden' id='anomov' name='anomov'>
      <input type='hidden' id='nromov' name='nromov'>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" onclick="guardarTexto()">Continuar y Grabar Inventario</button>-->
        <a href="#" onclick="recepcionarinternamiento(event)" class="btn btn-primary">Enviar a Archivo</a>
        <!--<button type="button" id="grabarBtn" class="btn btn-primary">Aceptar y enviar gu&iacute;a</button>-->
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    
    </div>
  </div>
</div>


  </form>
</body>
</html>

@endsection

@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('codbarras');
            const filas = document.querySelectorAll('#tabla-codigos tr');

            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const valor = input.value.trim();
                    let encontrado = false;

                    filas.forEach(fila => {
                        const codigo = fila.getAttribute('data-codigo');
                        if (codigo === valor) {
                            encontrado = true;
                            if (!fila.classList.contains('match')) {
                                fila.classList.add('match');
//                                fila.querySelector('.estado').textContent = "✔️ Verificado";
//                                fila.querySelector('.estado').innerHTML = `<i class="bi bi-check-circle-fill text-success me-1"></i><span class="fw-bold text-success">Verificado</span>`;
                                fila.querySelector('.estado').innerHTML = `<i class="fas fa-check-circle text-success me-1"></i><span class="fw-bold text-success">Verificado</span>`;
                            }
                        }
                    });



                    if (!encontrado) {
                      document.getElementById('messageErr').innerHTML = "CODIGO NO ENCONTRADO EN LA GUIA DE INTERNAMIENTO: "  + valor;
                      var messageErr = document.getElementById('messageErr');
                      messageErr.style.opacity = '1';
                      messageErr.style.display = 'block';
                      setTimeout(function() {
                          messageErr.style.opacity = '0';
                          setTimeout(() => {
                              messageErr.style.display = 'none';
                          }, 500);
                      }, 3000); 
                        //alert("Código no encontrado: " + valor);
                    }

                    input.value = '';
                }
            });
        });
    </script>
<script>
const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
function prepararYMostrarModal(xtipo,xano,xnro,event) {
    if (event) event.preventDefault(); // Previene recarga

    document.getElementById('tpmov').value=xtipo;
    document.getElementById('anomov').value=xano;
    document.getElementById('nromov').value=xnro;
    myModal.show();
}

//function recepcionarinternamiento(xtipo,xano,xnro,event) {
function recepcionarinternamiento(event) {
    if (event) event.preventDefault(); // Previene recarga
//    const tipo = xtipo;
//    const ano = xano;
//    const nro = xnro;
    const tipo = document.getElementById('tpmov').value;
    const ano = document.getElementById('anomov').value;
    const nro = document.getElementById('nromov').value;
    myModal.hide();

        $.ajax({
            url: '{{ route("internamiento.grabarecepcion") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tipo_mov: tipo,
                ano_mov: ano,
                nro_mov: nro
            },
            success: function(response) {
                //window.location.href = '{{ route("internamiento.recepcion") }}';
                if (response.success) {
                    // Guardar el mensaje para mostrarlo en la siguiente vista
                    sessionStorage.setItem('successMessage', response.message);

                    // Redirigir manualmente
                    window.location.href = response.redirect_url;
                }

            },
            error: function() {
                alert('Error en proceso de recepcion.');
            }
        });
}
</script>


@endpush


