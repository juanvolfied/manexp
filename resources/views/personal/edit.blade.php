@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Personal</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('personal.update', $personal->id_personal) }}" method="POST">
        @csrf
        @method('PUT')

        @include('personal.form', ['personal' => $personal])

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('personal.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
