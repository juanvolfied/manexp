@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Lista de Personal</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('personal.create') }}" class="btn btn-primary mb-3">+ Nuevo Registro</a>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Nombres</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($personal as $p)
                <tr>
                    <td>{{ $p->id_personal }}</td>
                    <td>{{ $p->apellido_paterno }}</td>
                    <td>{{ $p->apellido_materno }}</td>
                    <td>{{ $p->nombres }}</td>
                    <td>{{ $p->activo }}</td>
                    <td>
                        <a href="{{ route('personal.edit', $p->id_personal) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('personal.destroy', $p->id_personal) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este registro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
