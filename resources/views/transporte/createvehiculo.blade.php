@extends('menu.index')

@section('content')
<!--<div class="container mt-4">-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Registrar Nuevo Veh&iacute;culo</div>
        </div>
        <div class="card-body table-responsive">

    <!--<h2>Registrar Nuevo Personal</h2>-->
    <form action="{{ route('transporte.storevehiculo') }}" method="POST">
        @csrf
        @include('transporte.formvehiculo')
        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="{{ route('transporte.indexvehiculo') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>

        </div>
    </div>    
<!--</div>-->
@endsection
