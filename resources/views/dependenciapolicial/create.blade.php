@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2>Registrar Nueva Dependencia Policial</h2>
    <form action="{{ route('deppolicial.store') }}" method="POST" autocomplete="off">
        @csrf
        @include('dependenciapolicial.form')
        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="{{ route('deppolicial.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection
