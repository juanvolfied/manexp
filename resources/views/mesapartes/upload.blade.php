@extends('menu.index')

@section('content')

            <div class="row" id="datacabe">

              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <!--<div class="card-title">SUBIR PDF DIGITALIZADOS A SERVIDOR</div>-->
                    <ul class="nav nav-tabs card-header-tabs" id="tabsCard" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1"
                                type="button" role="tab" aria-controls="tab1" aria-selected="true">
                                <b>ANEXAR ESCRITOS DIGITALIZADOS</b>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2"
                                type="button" role="tab" aria-controls="tab2" aria-selected="false">
                                <b>ANEXAR CARGOS DE ENTREGA DIGITALIZADOS</b>
                            </button>
                        </li>
                    </ul>
                  </div>

                  <div class="card-body">
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                            <h5 align="center" class="text-primary"><b>SE ANEXARAN LOS ARCHIVOS DIGITALIZADOS EN FORMATO PDF DE LOS ESCRITOS REGISTRADOS</b></h5>
                            <br>
                            <form id="upload-form" enctype="multipart/form-data">
                                @csrf    
                                <div align="center">
                                <button type="button" id="customBtn" class="btn btn-primary btn-md rounded-pill shadow">
                                    <i class="fas fa-folder-open me-2"></i> <b>SELECCIONAR CARPETA</b>
                                </button>
                                <input type="file" id="directoryPicker" name="files[]" webkitdirectory multiple style="display:none;">
                            <!--    <input type="file" id="directoryPicker" name="files[]" webkitdirectory multiple>-->
                                <button type="submit" class="btn btn-success btn-md rounded-pill shadow">
                                    <i class="fas fa-upload me-2"></i> <b>SUBIR ESCRITOS PDFs</b>
                                </button>
                                </div>
                            </form>
                            <br>
                            <progress id="progressBar" value="0" max="100" style="width: 100%; display: block;"></progress>
                            <div id="status"></div>

                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                            <h5 align="center" ><b>SE ANEXARAN LOS CARGOS DE ENTREGA A FISCALES DIGITALIZADOS EN FORMATO PDF<b></h5>
                            <br>
                            <form id="upload-form2" enctype="multipart/form-data">
                                @csrf    
                                <div align="center">
                                <button type="button" id="customBtn2" class="btn btn-warning btn-md rounded-pill shadow">
                                    <i class="fas fa-folder-open me-2"></i> <b>SELECCIONAR CARPETA</b>
                                </button>
                                <input type="file" id="directoryPicker2" name="files2[]" webkitdirectory multiple style="display:none;">
                            <!--    <input type="file" id="directoryPicker" name="files[]" webkitdirectory multiple>-->
                                <button type="submit" class="btn btn-success btn-md rounded-pill shadow">
                                    <i class="fas fa-upload me-2"></i> <b>SUBIR CARGOS PDFs</b>
                                </button>
                                </div>
                            </form>
                            <br>
                            <progress id="progressBar2" value="0" max="100" style="width: 100%; display: block;"></progress>
                            <div id="status2"></div>

                        </div>
                    </div>


                  </div>        
                </div>
              </div>
            </div>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const customBtn = document.getElementById('customBtn');
  const directoryPicker = document.getElementById('directoryPicker');

  customBtn.addEventListener('click', () => {
    directoryPicker.click();
  });

    const form = document.getElementById('upload-form');
    const status = document.getElementById('status');

    // Crear barra de progreso y añadirla al DOM si no existe
    let progressBar = document.getElementById('progressBar');
    if (!progressBar) {
        progressBar = document.createElement('progress');
        progressBar.id = 'progressBar';
        progressBar.max = 100;
        progressBar.value = 0;
        progressBar.style.width = '100%';
        progressBar.style.display = 'block';
        status.parentNode.insertBefore(progressBar, status);
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const input = document.getElementById('directoryPicker');
        //const files = Array.from(input.files).filter(file => file.name.endsWith('.pdf'));
        const files = Array.from(directoryPicker.files).filter(file => {
        if (!file.name.endsWith('.pdf')) return false;
        const slashCount = (file.webkitRelativePath.match(/\//g) || []).length;
        return slashCount === 1;
        });     


        // Paso 1: obtener lista de nombres
        const fileNames = files.map(file => file.name);
        // Paso 2: consultar al backend cuáles ya existen
        let existingFiles = [];
        try {
            const checkResponse = await fetch("{{ route('upload.checkExisting') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ files: fileNames })
            });
            existingFiles = await checkResponse.json(); // array de nombres que ya existen
        } catch (err) {
            console.error("Error al verificar archivos existentes:", err);
            status.innerHTML = `<p style="color:red;">Error al verificar archivos existentes.</p>`;
            return;
        }
        // Paso 3: filtrar archivos a subir
        const filesToUpload = files.filter(file => !existingFiles.includes(file.name));
        if (filesToUpload.length === 0) {
            status.innerHTML = '<p>Todos los archivos ya han sido subidos previamente.</p>';
            progressBar.value = 100;
            return;
        }
        status.innerHTML = `<p>Subiendo ${filesToUpload.length} archivos nuevos...</p>`;
        // Paso 4: subir archivos en chunks por tamaño total (10MB)
        const maxChunkSizeMB = 20;
        const maxChunkSizeBytes = maxChunkSizeMB * 1024 * 1024;
        let totalUploadedFiles = 0;
        let currentChunk = [];
        let currentChunkSize = 0;
        for (let i = 0; i < filesToUpload.length; i++) {
            const file = filesToUpload[i];
            if (currentChunkSize + file.size > maxChunkSizeBytes && currentChunk.length > 0) {
                await uploadChunk(currentChunk);
                totalUploadedFiles += currentChunk.length;
                const progressPercent = Math.min(100, (totalUploadedFiles / filesToUpload.length) * 100);
                progressBar.value = progressPercent;
                status.innerHTML = `<p>Progreso: ${progressPercent.toFixed(2)}%</p>`;
                currentChunk = [];
                currentChunkSize = 0;
            }
            currentChunk.push(file);
            currentChunkSize += file.size;
        }
        if (currentChunk.length > 0) {
            await uploadChunk(currentChunk);
            totalUploadedFiles += currentChunk.length;
            const progressPercent = Math.min(100, (totalUploadedFiles / filesToUpload.length) * 100);
            progressBar.value = progressPercent;
            status.innerHTML = `<p>Progreso: ${progressPercent.toFixed(2)}%</p>`;
        }
        status.innerHTML += "<p><strong>Todos los archivos han sido procesados.</strong></p>";
        progressBar.value = 100;

        // Función para subir un chunk de archivos
        async function uploadChunk(chunk) {
            const formData = new FormData();
            chunk.forEach(file => formData.append('files[]', file));
            try {
                let response = await fetch("{{ route('upload.chunk') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                let result = await response.json();
            } catch (err) {
                console.error(err);
                status.innerHTML += `<p style="color:red;">Error al subir un grupo de archivos: ${err.message}</p>`;
            }
        }

        /*const chunkSize = 10;
        if(files.length === 0) {
            status.innerHTML = '<p>No se han seleccionado archivos PDF.</p>';
            return;
        }

        progressBar.value = 0;
        status.innerHTML = '';

        for (let i = 0; i < files.length; i += chunkSize) {
            const chunk = files.slice(i, i + chunkSize);
            const formData = new FormData();

            chunk.forEach(file => formData.append('files[]', file));

            try {
                let response = await fetch("{{ route('upload.chunk') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                let result = await response.json();
                //status.innerHTML += `<p>Chunk ${Math.floor(i / chunkSize) + 1} subido: ${result.message}</p>`;

                // Actualizar barra de progreso según archivos subidos
                const progressPercent = Math.min(100, ((i + chunk.length) / files.length) * 100);
                progressBar.value = progressPercent;
                //status.innerHTML += `<p>Progreso: ${progressPercent.toFixed(2)}%</p>`;
                status.innerHTML = `<p>Progreso: ${progressPercent.toFixed(2)}%</p>`;
            } catch (err) {
                console.error(err);
                //status.innerHTML += `<p style="color:red;">Error al subir chunk ${Math.floor(i / chunkSize) + 1}</p>`;
                status.innerHTML = `<p style="color:red;">Error al subir chunk ${Math.floor(i / chunkSize) + 1}</p>`;
            }
        }

        status.innerHTML += "<p><strong>Todos los archivos han sido procesados.</strong></p>";
        progressBar.value = 100;*/

    });





  const customBtn2 = document.getElementById('customBtn2');
  const directoryPicker2 = document.getElementById('directoryPicker2');

  customBtn2.addEventListener('click', () => {
    directoryPicker2.click();
  });

    const form2 = document.getElementById('upload-form2');
    const status2 = document.getElementById('status2');

    // Crear barra de progreso y añadirla al DOM si no existe
    let progressBar2 = document.getElementById('progressBar2');
    if (!progressBar2) {
        progressBar2 = document.createElement('progress2');
        progressBar2.id = 'progressBar2';
        progressBar2.max = 100;
        progressBar2.value = 0;
        progressBar2.style.width = '100%';
        progressBar2.style.display = 'block';
        status.parentNode.insertBefore(progressBar2, status);
    }

    form2.addEventListener('submit', async function(e) {
        e.preventDefault();

        const input = document.getElementById('directoryPicker2');
        //const files = Array.from(input.files).filter(file => file.name.endsWith('.pdf'));
const files = Array.from(directoryPicker2.files).filter(file => {
  if (!file.name.endsWith('.pdf')) return false;
  const slashCount = (file.webkitRelativePath.match(/\//g) || []).length;
  return slashCount === 1;
});     
        const chunkSize = 10;

        if(files.length === 0) {
            status2.innerHTML = '<p>No se han seleccionado archivos PDF.</p>';
            return;
        }

        progressBar2.value = 0;
        status2.innerHTML = '';

        for (let i = 0; i < files.length; i += chunkSize) {
            const chunk = files.slice(i, i + chunkSize);
            const formData = new FormData();

            chunk.forEach(file => formData.append('files[]', file));

            try {
                let response = await fetch("{{ route('upload.chunkcargos') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                let result = await response.json();
                //status.innerHTML += `<p>Chunk ${Math.floor(i / chunkSize) + 1} subido: ${result.message}</p>`;

                // Actualizar barra de progreso según archivos subidos
                const progressPercent = Math.min(100, ((i + chunk.length) / files.length) * 100);
                progressBar2.value = progressPercent;
                //status.innerHTML += `<p>Progreso: ${progressPercent.toFixed(2)}%</p>`;
                status2.innerHTML = `<p>Progreso: ${progressPercent.toFixed(2)}%</p>`;
            } catch (err) {
                console.error(err);
                //status.innerHTML += `<p style="color:red;">Error al subir chunk ${Math.floor(i / chunkSize) + 1}</p>`;
                status2.innerHTML = `<p style="color:red;">Error al subir chunk ${Math.floor(i / chunkSize) + 1}</p>`;
            }
        }

        status2.innerHTML += "<p><strong>Todos los archivos han sido procesados.</strong></p>";
        progressBar2.value = 100;
    });



});
</script>
@endsection
