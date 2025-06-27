@extends('menu.index') 

@section('content')
@if(session('success'))
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease;"><b>{{ session('success') }}</b></div>
@else
    <div id="messageOK" class="alert alert-success text-success" style="transition: opacity 0.5s ease; display:none;"></div>
@endif
@if(session('error'))
    <div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease;"><b>{{ session('error') }}</b></div>
@else
    <div id="messageErr" class="alert alert-danger text-danger" style="transition: opacity 0.5s ease; display:none;"></div>    
@endif

            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">BACKUP DE BASE DE DATOS</div>
                  </div>
                  <div class="card-body">

<div class="row">
    <div class="col-md-4 text-center">
        <div class="mb-3">
            <strong class="text-primary">
                ASEG&Uacute;RESE DE TENER UNA COPIA DE RESPALDO RECIENTE DE SU BASE DE DATOS
            </strong>
        </div>
        <form action="{{ route('backup.generar') }}" method="GET">
        @csrf  <!-- Este campo incluir� el token CSRF autom�ticamente -->    
            <button type="submit" class="btn btn-primary">
                Generar Backup
            </button>
        </form>
    </div>

    <div class="col-md-8">
        <h5>Últimos Backups</h5>
        <table class="table table-bordered table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Archivo</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Fecha</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Tamaño</th>
                    <th style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">Descargar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backupFiles as $file)
                    <tr>
                        <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $file['name'] }}</td>
                        <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ $file['date'] }}</td>
                        <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;">{{ number_format($file['size'] / 1024, 2) }} KB</td>
                        <td style="padding: 5px 10px!important; font-size:12px !important; text-transform:none;"><a href="{{ route('backup.descargar', ['filename' => $file['name']]) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-download"></i> Descargar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4">No hay backups disponibles</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


                  </div>        
                </div>
              </div>
            </div>

@endsection

<script>
window.onload = function() {
    var messageErr = document.getElementById('messageErr');
    var messageOK = document.getElementById('messageOK');
    if (messageErr) {
        setTimeout(function() {
            messageErr.style.opacity = '0';
            setTimeout(() => {
                messageErr.style.display = 'none';
            }, 500);
        }, 3000); 
    }
    if (messageOK) {
        setTimeout(function() {
            messageOK.style.opacity = '0';
            setTimeout(() => {
                messageOK.style.display = 'none';
            }, 500);
        }, 3000); 
    }
};
</script>