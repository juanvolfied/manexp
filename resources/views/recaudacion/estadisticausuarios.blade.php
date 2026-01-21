@extends('menu.index') 

@section('content')
<?php
function numeroAOrdinal($numero) {
    $ordinales = [
        0 => '',
        1 => '1er',
        2 => '2do',
        3 => '3er',
        4 => '4to',
        5 => '5to',
        6 => '6to',
        7 => '7mo',
        8 => '8vo',
        9 => '9no',
        10 => '10mo',
        11 => '11er',
    ];    
    return $ordinales[$numero] ?? $numero . 'º';
}
?>
<!--<form id="form-filtros" action="{{ route('mesapartes.estadisticasdetalle') }}" method="POST" class="row g-3" autocomplete="off">-->
<form id="form-filtros" class="row g-3" autocomplete="off">
    @csrf


<!-- Envolvemos los tabs dentro de un card-header -->
<div class="card" style="margin-bottom:5px;">
    <div class="card-header">
        <div class="card-title">Estad&iacute;stica en Intervalo de Fechas</div>
    </div>
    <div class="card-body">       
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="fechainicio" class="form-label"><b>Fecha Inicio</b></label>
                <input type="date" name="fechainicio" id="fechainicio" class="form-control" required
                    value="{{ $fechainicio ?? '' }}">
            </div>

            <div class="col-md-3">
                <label for="fechafin" class="form-label"><b>Fecha Final</b></label>
                <input type="date" name="fechafin" id="fechafin" class="form-control" required
                    value="{{ $fechafin ?? '' }}">
            </div>


            <!-- Botón -->
            <div class="col-md-2 mt-4 text-end">
                <a href="#" onclick="mostrarescritos(event)" class="btn btn-primary">Mostrar Estad&iacute;stica</a>
            </div>            
        </div>
    </div>
</div>


        <div class="card" id="resultados" style="display:none;">
            <div class="card-body">

    <ul class="nav nav-tabs" id="miTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1"
          type="button" role="tab" aria-controls="tab1" aria-selected="true">
          <b>TABLA DE DATOS OBTENIDOS</b>
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2"
          type="button" role="tab" aria-controls="tab2" aria-selected="false">
          <b>GRAFICO GENERADO</b>
        </button>
      </li>
    </ul>

    <!-- Contenido de cada Tab -->
    <div class="tab-content mt-3" id="miTabContent">
      <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">

        <div class="table-responsive">
            <div id="tablaDatos" style="margin-top: 20px;"></div>
        </div><!--table responsive-->

      </div>
      <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">

        <div class="mt-5">
            <canvas id="graficoBarras" height="100"></canvas>
        </div>

      </div>
    </div>





            </div>
        </div>

        
</form>


@endsection

<style>
    .card-body {
        overflow: visible !important;
        position: relative; /* asegúrate de que esté definido */
        z-index: 2;
    }
    #resultados {
        overflow: visible !important;
        position: relative; /* asegúrate de que esté definido */
        z-index: 1;
    }
    .selectize-dropdown {
        z-index: 9999 !important; /* fuerza que se vea por encima de todo */
    }        
</style>

@section('scripts')
<script>
    let grafico;
function mostrarescritos(event) {
    if (event) event.preventDefault(); // Previene recarga
    if (document.getElementById('fechainicio').value=="" || document.getElementById('fechafin').value=="") {
        alert("INGRESE EL INTERVALO DE FECHAS");
        return false;
    } 

        const fechainicio = document.getElementById('fechainicio').value;
        const fechafin = document.getElementById('fechafin').value;
        var titulo="ESTADISTICA DE REGISTROS DE RECAUDACION ENTRE FECHAS";

        fetch(`{{ route('recaudacion.estadisticasdetalle') }}?fechainicio=${fechainicio}&fechafin=${fechafin}`)
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                }
                console.log("Respuesta cruda:", response);
                alert (response);
                return;
            })
            //.then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('graficoBarras').getContext('2d');
                if (grafico) {
                    grafico.destroy();
                }
                //const ctx = document.getElementById('grafico').getContext('2d');
                const config = {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: data.datasets
                    },
                    options: {                    
                        responsive: true,
                        title: {
                            display: true,
                            text: titulo
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            display: true,
                            position: 'top' // top | left | bottom | right
                        },                            
                        scales: {
                            xAxes: [{
                                stacked: false
                            }],
                            yAxes: [{
                                beginAtZero: true,
                                ticks: {
                                    min: 0,         // 👈 fuerza inicio desde 0
                                    precision: 0,   // 👈 sin decimales
                                    callback: function(value) {
                                        return Number.isInteger(value) ? value : '';
                                    }
                                }
                            }]
                        }
                    }
                };
                grafico = new Chart(ctx, config);
                generarTabla(data.labels, data.datasets);

            });
    

    document.getElementById('resultados').style.display = 'block';


}

function generarTabla(labels, datasets) {
    const contenedor = document.getElementById('tablaDatos');

    // Limpiar contenido anterior
    contenedor.innerHTML = '';

    // Crear tabla
    const table = document.createElement('table');
    table.classList.add('table', 'table-bordered', 'table-striped', 'table-sm'); // Usa clases si estás con Bootstrap

    // Crear encabezado
    const thead = document.createElement('thead');
    thead.classList.add('thead-dark'); // Usa clases si estás con Bootstrap
    
    const encabezado = document.createElement('tr');

    const thFecha = document.createElement('th');
    thFecha.textContent = 'Fecha';
    thFecha.setAttribute('style', 'padding: 5px 5px!important; font-size:11px !important; text-transform:none;');
    encabezado.appendChild(thFecha);

    datasets.forEach(ds => {
        const th = document.createElement('th');
        th.innerHTML = ds.label;
        th.setAttribute('style', 'padding: 5px 5px!important; font-size:11px !important; text-transform:none;');

        encabezado.appendChild(th);
    });

    thead.appendChild(encabezado);
    table.appendChild(thead);

    // Crear cuerpo de la tabla
    const tbody = document.createElement('tbody');

    let colu=0;
    let total=[];
    labels.forEach((fecha, index) => {
        const fila = document.createElement('tr');

        const tdFecha = document.createElement('td');
        tdFecha.textContent = fecha;
        tdFecha.setAttribute('style', 'padding: 5px 5px!important; font-size:11px !important; text-transform:none;');
        fila.appendChild(tdFecha);
    colu=0;
        datasets.forEach(ds => {
            colu=colu+1;
        if (total[colu] === undefined) {
            total[colu] = 0;
        }            
            const td = document.createElement('td');
            //td.textContent = ds.data[index] ?? 0;
            const valor = ds.data[index] ?? 0;
            td.innerHTML = valor > 0 ? `<strong>${valor}</strong>` : valor;
            td.setAttribute('style', 'padding: 5px 5px!important; font-size:11px !important; text-transform:none;');
            fila.appendChild(td);
            total[colu]=total[colu]+valor;
        });
        
        tbody.appendChild(fila);
    });

        const fila = document.createElement('tr');
        const tdFecha = document.createElement('td');
        tdFecha.setAttribute('style', 'padding: 5px 5px!important; font-size:11px !important; text-transform:none;');
        tdFecha.innerHTML = "<strong>ACUMULADO</strong>";
        fila.appendChild(tdFecha);
        for (let i = 1; i <= colu; i++) {
            const td = document.createElement('td');
            const valor = total[i] ?? 0;
            td.innerHTML = valor > 0 ? `<strong>${valor}</strong>` : valor;
            td.setAttribute('style', 'padding: 5px 5px!important; font-size:11px !important; text-transform:none;');
            fila.appendChild(td);
        };
        tbody.appendChild(fila);


    table.appendChild(tbody);
    contenedor.appendChild(table);
}

</script>


@endsection
