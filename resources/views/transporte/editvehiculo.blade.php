@extends('menu.index')

@section('content')
<!--<div class="container mt-4">-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Editar Veh&iacute;culo</div>
        </div>
        <div class="card-body table-responsive">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('transporte.updatevehiculo', $vehiculos->nroplaca) }}" method="POST">
        @csrf
        @method('PUT')

        @include('transporte.formvehiculo', ['vehiculos' => $vehiculos])

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('transporte.indexvehiculo') }}" class="btn btn-secondary">Cancelar</a>
    </form>
        </div>
    </div>    
<!--</div>-->
@endsection
