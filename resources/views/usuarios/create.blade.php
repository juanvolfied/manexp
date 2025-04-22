@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2>Registrar Nuevo Usuario</h2>
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        @include('usuarios.form')
        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection
