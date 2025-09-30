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
                    <div class="card-title">Editar Carpeta SGF {{ $carpetassgf->carpetafiscal }}</div>
                  </div>
                  <div class="card-body">

    <form action="{{ route('carpetassgf.update', ['id_generado' => $carpetassgf->id_generado]) }}" method="POST">
        @csrf
        @method('PUT')

        @include('carpetassgf.form', ['carpetassgf' => $carpetassgf])

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('carpetassgf.carpetassgfindex') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    
                  </div>
                </div>
              </div>
            </div>    

<!--</div>-->
@endsection
