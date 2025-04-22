@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Lista de Usuarios</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-3">+ Nuevo Registro</a>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID Usuario</th>
                <th>Personal</th>
                <th>Usuario</th>
                <th>Password</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $p)
                <tr>
                    <td>{{ $p->id_usuario }}</td>
                    <td>{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                    <td>{{ $p->usuario }}</td>
                    <td>{{ $p->password }}</td>
                    <td>{{ $p->activo }}</td>
                    <td>
                        <a href="{{ route('usuarios.edit', $p->id_usuario) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('usuarios.destroy', $p->id_usuario) }}" method="POST" class="d-inline">
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
