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
            <label for="tpfecha" class="form-label">Fechas a Mostrar</label>
            <select id="tpfecha" class="form-select" name="tpfecha" required>
                <option value="T">TODOS LOS REGISTROS</option>
                <option value="F">POR INTERVALO de Fechas</option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="fechainicio" class="form-label">Fecha Inicio</label>
            <input type="date" id="fechainicio" name="fechainicio" class="form-control" value="{{ date('Y-m-d') }}">
        </div>

        <div class="col-md-3">
            <label for="fechafin" class="form-label">Fecha Fin</label>
            <input type="date" id="fechafin" name="fechafin" class="form-control" value="{{ date('Y-m-d') }}">
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
  const select = document.getElementById('tpfecha');
  const fecha1 = document.getElementById('fechainicio');
  const fecha2 = document.getElementById('fechafin');

  select.addEventListener('change', () => {
    if (select.value === 'T') {
      // Si selecciona opción 1, deshabilitar fechas
      fecha1.disabled = true;
      fecha2.disabled = true;
    } else if (select.value === 'F') {
      // Si selecciona opción 2, habilitar fechas
      fecha1.disabled = false;
      fecha2.disabled = false;
    }
  });

  // Opcional: ejecutar al cargar para que quede en el estado correcto
  window.addEventListener('DOMContentLoaded', () => {
    select.dispatchEvent(new Event('change'));
  });
</script>
<script>
    
    let grafico;

    document.getElementById('form-filtros').addEventListener('submit', function(e) {
        e.preventDefault();
        console.log("Interceptado ");

        const tpfecha = document.getElementById('tpfecha').value;
        const fechainicio = document.getElementById('fechainicio').value;
        const fechafin = document.getElementById('fechafin').value;
        const tpdatos = document.getElementById('tpdatos').value;
        if (tpdatos=="1") {
            var titulo="CARPETAS FISCALES POR D\u00EDA";
        } else {
            var titulo="PAQUETES POR D\u00EDA";
        }
        fetch(`{{ route('graficopie.fecha') }}?tpfecha=${tpfecha}&fechainicio=${fechainicio}&fechafin=${fechafin}&tpdatos=${tpdatos}`)
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


                const graficoPie = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data,
                            backgroundColor: generarColores(data.data.length),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        return data.labels.map((label, i) => {
                                            return {
                                                text: `${label}: ${data.datasets[0].data[i]}`,
                                                fillStyle: chart.data.datasets[0].backgroundColor[i],
                                                index: i
                                            };
                                        });
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.raw || 0;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        }
                    }
                });
                        generarTabla(data.labels, data.data);

            });

    });

    function generarColores(cantidad) {
        const colores = [];
        const step = 360 / cantidad;  // Divide el círculo HSL en partes iguales

        for (let i = 0; i < cantidad; i++) {
            const hue = Math.floor(step * i);
            colores.push(`hsl(${hue}, 70%, 60%)`);
        }
        return colores;
    }

    function generarTabla(labels, data) {
        let tablaHTML = `<table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Dependencia</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>`;
        var total=0;
        for (let i = 0; i < labels.length; i++) {
            tablaHTML += `
                <tr>
                    <td>${labels[i]}</td>
                    <td>${data[i]}</td>
                </tr>`;
                total=total+data[i];
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
