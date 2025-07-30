@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Escrito</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('mesapartes.update', ['anolibro' => $libroescritos->anolibro, 'numero' => $libroescritos->numero]) }}" method="POST">
        @csrf
        @method('PUT')

        @include('mesapartes.form', ['libroescritos' => $libroescritos])

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('mesapartes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
