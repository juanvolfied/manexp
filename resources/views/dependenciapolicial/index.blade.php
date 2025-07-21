@extends('menu.index')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Dependencias Policiales</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('deppolicial.create') }}" class="btn btn-primary mb-3">+ Nuevo Registro</a>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Descripci&oacute;n</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deppoli as $p)
                <tr>
                    <td>{{ $p->id_deppolicial }}</td>
                    <td>{{ $p->descripciondep }}</td>
                    <td>
                        <a href="{{ route('deppolicial.edit', $p->id_deppolicial) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('deppolicial.destroy', $p->id_deppolicial) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Esta seguro de eliminar este registro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
