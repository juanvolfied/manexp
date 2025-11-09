@extends('menu.index')

@section('content')
<!--<div class="container mt-4">
    <h2 class="mb-4">Editar Escrito</h2>-->

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">Editar Escrito {{ $libroescritos->codescrito }}</div>
                  </div>
                  <div class="card-body">

    <form action="{{ route('mesapartes.update', ['codescrito' => $libroescritos->codescrito]) }}" method="POST">
        @csrf
        @method('PUT')

        @include('mesapartes.form', ['libroescritos' => $libroescritos])

            <!-- Contenedor flex para los botones -->
            <div class="d-flex justify-content-between mt-3">
                <!-- Botones principales a la izquierda -->
                <div>
                    <button type="submit" class="btn btn-success">Actualizar</button>
                    <a href="{{ route('mesapartes.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>

                <!-- Botón especial a la derecha -->
@auth
    @php
        $perfil = optional(Auth::user()->perfil)->descri_perfil;        
    @endphp
@endauth
                @if( ($perfil=="Admin") || ($perfil=="MesaPartesAdmin") )
                <div>
                    <button type="button" class="btn btn-warning" onclick="confirmar()"><b>Anular el Registro</b></button>
                </div>
                @endif
            </div>        
    </form>

    
                  </div>
                </div>
              </div>
            </div>    

@if( ($perfil=="Admin") || ($perfil=="MesaPartesAdmin") )
<!-- Modal -->
<div class="modal fade" id="textoModal" tabindex="-1" aria-labelledby="textoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="textoModalLabel">ANULACION DE ESCRITO {{ $libroescritos->codescrito }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body">
        <label for="observacion"><b>SE ANULAR&Aacute; EL REGISTRO DEL ESCRITO DE C&Oacute;DIGO {{ $libroescritos->codescrito }}<br>DESPU&Eacute;S DE ANULADO EL C&Oacute;DIGO PODR&Aacute; VOLVERSE A REGISTRAR<br><br>DESEA CONTINUAR CON LA ANULACI&Oacute;N ??</b></label>
      </div>
      
      <div class="modal-footer">
        <form action="{{ route('mesapartes.anular', ['codescrito' => $libroescritos->codescrito]) }}" method="POST" style="display:inline;" id="anularForm">
            @csrf
            <button type="button" class="btn btn-danger" onclick="Anular()"><b>Continuar con la Anulaci&oacute;n</b></button>
        </form>
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancelar Anulaci&oacute;n</button>
      </div>
    
    </div>
  </div>
</div>
@endif


<!--</div>-->
@endsection

<script>
    function confirmar() {
      const myModal = new bootstrap.Modal(document.getElementById('textoModal'));
      myModal.show();
    }
    function Anular() {
      document.getElementById('anularForm').submit();
    }    
</script>