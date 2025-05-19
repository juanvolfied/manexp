@extends('menu.index')

@section('content')
<div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    

<!--<div class="container mt-4">-->
    <!--<h2>Registrar Nuevo Expediente</h2>-->
            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">Registrar Nuevo Expediente</div>
                  </div>
                  <div class="card-body">


    <form id="miFormulario" action="{{ route('expediente.store') }}" method="POST" autocomplete="off">
        @csrf
        @include('expediente.form')
        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="{{ route('expediente.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>

    

                  </div>
                </div>
              </div>
            </div>    
<!--</div>-->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('input[name="codbarras"]');
    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Evita que el formulario se envíe
        }
    });
});
</script>
@endsection
