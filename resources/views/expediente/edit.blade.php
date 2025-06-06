@extends('menu.index')

@section('content')
<!--<div class="container mt-4">-->
    <!--<h2 class="mb-4">Editar Expediente</h2>-->
            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">Editar Expediente</div>
                  </div>
                  <div class="card-body">


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form id="miFormulario" action="{{ route('expediente.update', $expediente->id_expediente) }}" method="POST">
        @csrf
        @method('PUT')

        @include('expediente.form', ['expediente' => $expediente])

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('expediente.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>



                  </div>
                </div>
              </div>
            </div>  


<!--</div>-->
@endsection
