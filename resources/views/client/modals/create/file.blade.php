<style>
    .drop-area.highlight {
        border-color: #009688;
    }
</style>

<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('client.file.store') }}"enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="fileModalLabel">Crear archivo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12 bg-secondary-subtle rounded p-5" id="dropArea">
                    <div class="d-flex justify-content-center">
                        <input accept=".pdf,.jpg,.jpeg,.png" type="file" id="fileInput" name="files[]" multiple
                            style="display: none;">
                        <label for="fileInput" class="drop-area" id="input-label">Suelta aquí tus archivos o haz clic
                            para seleccionarlos</label>
                    </div>
                    <ul class="list-group list-group-flush rounded" id="file-list"></ul>
                </div>

                <input type="hidden" name="path" value="{{ $data['root_path'] }}">

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnCrear">{{ __('buttons.store')}}</button>
                <button type="button" class="btn btn-danger"
                     data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        const $dropArea = $('#dropArea');
        const $fileInput = $('#fileInput');
        const $fileList = $('#file-list');

        // Evitar que el navegador abra los archivos al soltarlos
        $dropArea.on('dragenter dragover dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        // Cambiar estilos de la zona de soltar
        $dropArea.on('dragenter', function() {
            $dropArea.addClass('highlight');
        });

        $dropArea.on('dragleave drop', function() {
            $dropArea.removeClass('highlight');
        });

        // Procesar archivos soltados
        $dropArea.on('drop', function(e) {
            const files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
            setFiles(files);
        });

        // Procesar archivos seleccionados desde el input
        $fileInput.on('change', function() {
            const files = this.files;
            handleFiles(files);
            setFiles(files);
        });

        // Función para manejar los archivos
        function handleFiles(files) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                console.log('Archivo seleccionado:', file.name);

                // Validar el tipo de archivo
                if (!isValidFileType(file)) {
                    console.log('Archivo no válido:', file.name);
                    continue;
                }

                // Validar el tamaño del archivo
                if (!isValidFileSize(file)) {
                    console.log('Archivo demasiado grande:', file.name);
                    continue;
                }

                // Agregar archivo a la lista
                addFileToList(file);
            }
        }

        // Función para validar el tipo de archivo
        function isValidFileType(file) {
            const acceptedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            return acceptedTypes.includes(file.type);
        }

        // Función para validar el tamaño del archivo (menor a 5MB)
        function isValidFileSize(file) {
            const maxSizeInBytes = 10 * 1024 * 1024; // 5MB
            return file.size <= maxSizeInBytes;
        }

        // Funcion para truncar el nombre
        function truncateString(str) {
            return (str.length > 30) ? str.substring(0, 30) + "..." : str;
        }

        // Función para agregar archivo a la lista
        function addFileToList(file) {
            const $li = $('<li class="list-group-item"></li>');
            const $name = $('<span></span>').text(truncateString(file.name));
            const $button = $('<button class="btn btn-sm text-danger">X</button>');

            $button.on('click', function() {
                $li.remove();
            });

            $li.append($name, $button);
            $fileList.append($li);
        }

        function setFiles(files) {
            $fileInput[0].files = files; // Asigna los archivos directamente al input
        }

    });
</script>
