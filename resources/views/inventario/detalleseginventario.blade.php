@extends('menu.index')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Inventario</title>    
</head>
<body>

    <div class="container mt-4">

            <div class="row">            
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Detalle Seguimiento de Registro de Inventario</div>
                  </div>
                  <div class="card-body">
<!--        <h1 class="mb-4">Seguimiento de Registro de Inventario</h1>-->

        <!-- Tabla con clases Bootstrap -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size:13px !important; text-transform:none;">C&oacute;digo de Barras</th>			      
                    <th style="padding: 5px 10px!important; font-size:13px !important; text-transform:none;">Dependencia</th>
                    <th style="padding: 5px 10px!important; font-size:13px !important; text-transform:none;">A&ntilde;o</th>
                    <th style="padding: 5px 10px!important; font-size:13px !important; text-transform:none;">Nro Exp</th>
                    <th style="padding: 5px 10px!important; font-size:13px !important; text-transform:none;">Tipo</th>
                    <th style="padding: 5px 10px!important; font-size:13px !important; text-transform:none;">Estado</th>
                    <th style="padding: 5px 10px!important; font-size:13px !important; text-transform:none;">Fecha Lectoreo</th>
                    <th style="padding: 5px 10px!important; font-size:13px !important; text-transform:none;">Fecha Inventariado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($segdetalle as $datos)
                    <tr>
                        <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->codbarras }}</td>
                        <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->id_dependencia }}</td>
                        <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->ano_expediente }}</td>
                        <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->nro_expediente }}</td>
                        <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->id_tipo }}</td>                        
                        <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->estado }}</td>                        
                        <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->fecha_lectura }} {{ $datos->hora_lectura }}</td>
                        <td style="font-size:13px; padding: 5px 10px !important;">{{ $datos->fecha_inventario }} {{ $datos->hora_inventario }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

                  </div>
                </div>
              </div>
            </div>


    </div>

</body>
</html>

@endsection
