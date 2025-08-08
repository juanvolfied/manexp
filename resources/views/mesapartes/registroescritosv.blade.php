@extends('menu.index')

@section('content')
<div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    

@if(session('success'))
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('success') }}</b></div>
@else
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
@endif

<!--<div class="container mt-4">-->
    <!--<h2>Registrar Nuevo Expediente</h2>-->
            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">Registrar Nuevo Escrito - Recepci&oacute;n Virtual</div>
                  </div>
                  <div class="card-body">


    <form id="miFormulario" action="{{ route('mesapartes.storev') }}" method="POST" autocomplete="off">
        @csrf
        @include('mesapartes.formv')
        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="{{ route('mesapartes.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>

    

                  </div>
                </div>
              </div>
            </div>    
<!--</div>-->

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
