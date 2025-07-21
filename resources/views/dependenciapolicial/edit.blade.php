@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Dependencia Policial</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('deppolicial.update', $deppoli->id_deppolicial) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        @include('dependenciapolicial.form', ['deppoli' => $deppoli])

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('deppolicial.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
