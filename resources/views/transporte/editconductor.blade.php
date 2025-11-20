@extends('menu.index')

@section('content')
<!--<div class="container mt-4">-->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Editar Conductor</div>
        </div>
        <div class="card-body table-responsive">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('transporte.updateconductor', $conductores->id_conductor) }}" method="POST">
        @csrf
        @method('PUT')

        @include('transporte.formconductor', ['personal' => $conductores])

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('transporte.indexconductor') }}" class="btn btn-secondary">Cancelar</a>
    </form>
        </div>
    </div>    
<!--</div>-->
@endsection
