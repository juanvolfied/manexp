@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2>Registrar Nuevo Personal</h2>
    <form action="{{ route('personal.store') }}" method="POST">
        @csrf
        @include('personal.form')
        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="{{ route('personal.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection
