@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Lista de Perfiles Asignados</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('perfilusuario.create') }}" class="btn btn-primary mb-3">+ Asignar Nuevo Perfil</a>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Personal</th>
                <th>Usuario</th>
                <th>Perfil Asignado</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perfilusuario as $p)
                <tr>
                    <td>{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                    <td>{{ $p->usuario }}</td>
                    <td>{{ $p->descri_perfil }}</td>
                    <td>{{ $p->activo }}</td>
                    <td>
                        <!--<a href="{{ route('perfilusuario.edit', ['id_usuario' => $p->id_usuario, 'id_perfil' => $p->id_perfil]) }}" class="btn btn-sm btn-warning">Editar</a>-->
                        <form action="{{ route('perfilusuario.destroy', ['id_usuario' => $p->id_usuario, 'id_perfil' => $p->id_perfil]) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Est\u00E1s seguro de eliminar este registro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
