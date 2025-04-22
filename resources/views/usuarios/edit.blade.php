@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Usuarios</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('usuarios.update', $usuarios->id_usuario) }}" method="POST">
        @csrf
        @method('PUT')

        @include('usuarios.form', ['usuarios' => $usuarios])

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
