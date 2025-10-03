@extends('menu.index') 

@section('content')
@php
    $meses = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ];
@endphp
<form id="form-filtros" class="row g-3" autocomplete="off">
    @csrf
    <div class="card " style="margin-bottom:5px;">
        <div class="card-header">
            <div class="card-title">Compresi&oacute;n de Escritos Digitalizados (PDF)</div>
        </div>            
        <div class="card-body">
            <div class="row g-3 align-items-center">

                <div class="col-md-4">
                    <label for="mesunico" class="form-label"><b>Mes a Procesar</b></label>
                    <select name="mesunico" id="mesunico" class="" >
                        <option value="">-- Seleccione --</option>
                        @foreach($fechasunicas as $p)
                        <option value="{{ $p->anio }}-{{ $p->mes }}" >
                            {{ $meses[$p->mes] ." ". $p->anio }} 
                        </option>
                        @endforeach
                    </select>                
                </div>

                <!-- Botón -->
                <div class="col-md-4 mt-4 text-end">
                    <a href="#" onclick="verificaprocesa(event)" class="btn btn-primary">Verificar y Procesar PDF</a>
                </div>
            </div><br>

            <!-- Barra de progreso -->
            <div id="progreso-container" style="width: 100%; background: #eee; border-radius: 4px; margin-bottom: 10px;">
                <div id="progreso-barra" style="width: 0%; height: 20px; background: #4caf50; text-align: center; color: white; border-radius: 4px;">
                    0%
                </div>
            </div>
            <!-- Texto de estado opcional -->
            <div id="progreso-texto">
                <span id="spinner" class="spinner" style="display: none;"></span>
                <span id="progreso-mensaje">Esperando inicio...</span>
            </div>

        </div>
    </div>

</form>

@endsection

<style>
    .card-body {
        overflow: visible !important;
        position: relative; /* asegúrate de que esté definido */
        z-index: 1;
    }

.spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(0, 0, 0, 0.2);
    border-top: 2px solid #4caf50;
    border-radius: 50%;
    animation: girar 0.6s linear infinite;
    margin-right: 8px;
    vertical-align: middle;
}

@keyframes girar {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}    
</style>
@section('scripts')

<script>
    let archivos = [];
    let anio;
    let mes;
    const comprimirUrl = "{{ route('mesapartes.comprime') }}";
    const barra = document.getElementById('progreso-barra');
    const texto = document.getElementById('progreso-mensaje');
    const spinner = document.getElementById('spinner');

    function verificaprocesa() {
        const valor = document.getElementById('mesunico').value;
        if (valor=="") {
            alert("SELECCIONE EL MES A PROCESAR");
            return false;
        }
        [anio, mes] = valor.split('-');
        mes = mes.padStart(2, '0');

        // Mostrar spinner al comenzar
        spinner.style.display = 'inline-block';
        texto.textContent = 'Iniciando compresión...';

        // 1. Obtener lista de archivos a comprimir
        fetch(`/manexp/public/mesapartes/${anio}/${mes}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'ok') {
                archivos = data.archivos;
                procesarArchivos();
            } else {
                console.error('Error:', data.message);
            }
        });
    }

    // 2. Comprimir uno por uno
    async function procesarArchivos() {
    const total = archivos.length;


        for (let i = 0; i < archivos.length; i++) {
            const archivo = archivos[i];

            const formData = new FormData();
            formData.append('anio', anio);
            formData.append('mes', mes);
            formData.append('archivo', archivo);

            try {
                const res = await fetch(comprimirUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const result = await res.json();
                if (result.status === 'ok') {
                    console.log(`✅ (${i+1}/${archivos.length}) Comprimido: ${archivo}`);
                } else {
                    console.warn(`❌ (${i+1}/${archivos.length}) Falló: ${archivo}`);
                }

                // 👇 Aquí puedes actualizar una barra de progreso si quieres
            } catch (e) {
                console.error(`Error en archivo ${archivo}`, e);
            }

            // 👉 Actualizar barra de progreso
            const porcentaje = Math.round(((i + 1) / total) * 100);
            barra.style.width = porcentaje + '%';
            barra.textContent = porcentaje + '%';
            texto.textContent = `Procesando archivo ${i + 1} de ${total}`;            
        }

        console.log('✅ Todo terminado');
        spinner.style.display = 'none';
        texto.textContent = '✅ Todos los archivos han sido procesados.';

    }
</script>

@endsection
