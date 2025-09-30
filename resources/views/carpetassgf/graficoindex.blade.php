@extends('menu.index') 

@section('content')
<!--<div class="container">-->
            <div class="row">            
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Gr&aacute;fico - Registro de Carpetas SGF</div>
                  </div>
                  <div class="card-body table-responsive">


    {{-- Formulario para seleccionar filtros --}}
    <form id="form-filtros" class="row g-3">
        <div class="row">

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
<div id="tablaDatos" style="margin-top: 20px;"></div>


    
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

        const fechainicio = document.getElementById('fechainicio').value;
        const fechafin = document.getElementById('fechafin').value;
        var titulo="CARPETAS SGF POR DEPENDENCIA ENTRE FECHAS";
        fetch(`{{ route('carpetassgf.grafico') }}?fechainicio=${fechainicio}&fechafin=${fechafin}`)
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
            yAxes: [{
                ticks: {
                    min: 0,         // 👈 fuerza inicio desde 0
                    stepSize: 1,    // 👈 paso de 1
                    precision: 0,   // 👈 sin decimales
                    callback: function(value) {
                        return Number.isInteger(value) ? value : '';
                    }
                }
            }]
        }
    }
                });

                generarTabla(data.labels, data.data);

            });
    });


    function generarTabla(labels, data) {
        let tablaHTML = `<table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>`;
        var total=0;
        for (let i = 0; i < labels.length; i++) {
            if (data[i]>0) {
            tablaHTML += `
                <tr>
                    <td>${labels[i]}</td>
                    <td>${data[i]}</td>
                </tr>`;
                total=total+data[i];
            }
        }
            tablaHTML += `
                <tr>
                    <td><b>ACUMULADO TOTAL</b></td>
                    <td><b>${total}</b></td>
                </tr>`;

        tablaHTML += `</tbody></table>`;

        document.getElementById('tablaDatos').innerHTML = tablaHTML;
    }    
</script>
@endsection
