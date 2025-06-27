@extends('menu.index') 

@section('content')
<!--<div class="container">-->
            <div class="row">            
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Gr&aacute;fico - seguimiento de avance del inventario por Usuario</div>
                  </div>
                  <div class="card-body table-responsive">


    {{-- Formulario para seleccionar filtros --}}
    <form id="form-filtros" class="row g-3">
        <div class="row">
        <div class="col-md-4">
            <label for="tpdatos" class="form-label">Datos a Mostrar</label>
            <select id="tpdatos" class="form-select" name="tpdatos" required>
                <option value="1">Por n&uacute;mero de Carpetas Fiscales</option>
                <option value="2">Por n&uacute;mero de Paquetes</option>
            </select>
        </div>
        </div>
        <div class="row">
        <div class="col-md-4">
            <label for="id_usuario" class="form-label">Nombre del personal >>> Usuario</label>
            <select id="id_usuario" class="form-select" name="id_usuario" required>
                <option value="">-- Seleccione --</option>
                @foreach($usuarios as $p)
                    <option value="{{ $p->id_usuario }}">{{ $p->apellido_paterno }} {{ $p->apellido_materno }}, {{ $p->nombres }} >>> {{ $p->usuario }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="fechainicio" class="form-label">Fecha Inicio</label>
            <input type="date" id="fechainicio" name="fechainicio" class="form-control" required>
        </div>

        <div class="col-md-3">
            <label for="fechafin" class="form-label">Fecha Fin</label>
            <input type="date" id="fechafin" name="fechafin" class="form-control" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Generar Gr&aacute;fico</button>
        </div>
        </div>
    </form>

    {{-- Gr�fico --}}
    <div class="mt-5">
        <canvas id="graficoBarras" height="100"></canvas>
    </div>


    
                  </div>
                </div>
              </div>
            </div>

<!--</div>-->
@endsection

@section('scripts')
<!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->
<script>
    let grafico;

    document.getElementById('form-filtros').addEventListener('submit', function(e) {
        e.preventDefault();
        console.log("Interceptado ");

        const id_usuario = document.getElementById('id_usuario').value;
        const fechainicio = document.getElementById('fechainicio').value;
        const fechafin = document.getElementById('fechafin').value;
        const tpdatos = document.getElementById('tpdatos').value;
        if (tpdatos=="1") {
            var titulo="CARPETAS FISCALES POR D\u00EDA";
        } else {
            var titulo="PAQUETES POR D\u00EDA";
        }
        fetch(`{{ route('grafico.usuario') }}?id_usuario=${id_usuario}&fechainicio=${fechainicio}&fechafin=${fechafin}&tpdatos=${tpdatos}`)
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                }
                alert('TU SESION HA EXPIRADO. SERAS REDIRIGIDO AL LOGIN.');
                window.location.href = '{{ route("usuario.login") }}';
                return;
            })
            //.then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('graficoBarras').getContext('2d');

                if (grafico) {
                    grafico.destroy();
                }

                grafico = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: titulo,
                            data: data.data,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,

        ticks: {
            //stepSize: 1,  // Asegura que el paso sea de 1
            callback: function(value) {
                // Solo mostrar n�meros enteros, sin decimales
                return Number.isInteger(value) ? value : '';
            }
        }
                            }
                        }
                    }
                });
            });
    });
</script>
@endsection
