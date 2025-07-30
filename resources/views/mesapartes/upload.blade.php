@extends('menu.index')

@section('content')
            <div class="row" id="datacabe">            
              <div class="col-md-12">
                <div class="card">
                  
                  <div class="card-header">
                    <div class="card-title">SUBIR PDF DIGITALIZADOS A SERVIDOR</div>
                  </div>
                  <div class="card-body">

<form id="upload-form" enctype="multipart/form-data">
    @csrf    
    <button type="button" id="customBtn">Seleccionar carpeta</button>
    <input type="file" id="directoryPicker" name="files[]" webkitdirectory multiple style="display:none;">
<!--    <input type="file" id="directoryPicker" name="files[]" webkitdirectory multiple>-->
    <button type="submit">Subir PDFs</button>
</form>

<progress id="progressBar" value="0" max="100" style="width: 100%; display: block;"></progress>

<div id="status"></div>

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
        const chunkSize = 10;

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
        progressBar.value = 100;
    });
});
</script>
@endsection
