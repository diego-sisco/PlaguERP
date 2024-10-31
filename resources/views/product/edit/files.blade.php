<div id="edit_files" class="row p-3 pt-1">
    <ul class="list-group col-4 mb-5">
        <li class="list-group-item">
            <div class="row">
                <div class="col-4">
                    <strong>Especificación RP:</strong>
                </div>
                <div class="col-4">
                    <p class="text-center">{{ $product->files->rp_specification }}</p>
                </div>
                <div class="col-4">
                    @if ($product->files->rp_specification)
                        <a onclick="return confirm('¿Estás seguro de eliminar la Especificación RP?')"
                            href="{{ route('product.file.delete', ['id' => $product->id, 'file' => 'rp_specification', 'section' => $section]) }}"
                            class="btn btn-danger btn-sm float-end">Eliminar</a>
                    @else
                        <a href="#!" class="btn btn-secondary btn-sm float-end disabled">Eliminar</a>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-4">
                    <strong>Especificación técnica:</strong>
                </div>
                <div class="col-4">
                    <p>{{ $product->files->techical_specification }}</p>
                </div>
                <div class="col-4">
                    @if ($product->files->techical_specification)
                        <a onclick="return confirm('¿Estás seguro de eliminar la Especificación técnica?')"
                            href="{{ route('product.file.delete', ['id' => $product->id, 'file' => 'techical_specification', 'section' => $section]) }}"
                            class="btn btn-danger btn-sm float-end">Eliminar</a>
                    @else
                        <a href="#!" class="btn btn-secondary btn-sm float-end disabled">Eliminar</a>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-4">
                    <strong>Especificaciones de seguridad:</strong>
                </div>
                <div class="col-4">
                    <p>{{ $product->files->segurity_specification }}</p>
                </div>
                <div class="col-4">
                    @if ($product->files->segurity_specification)
                        <a onclick="return confirm('¿Estás seguro de eliminar las Especificaciones de seguridad?')"
                            href="{{ route('product.file.delete', ['id' => $product->id, 'file' => 'segurity_specification', 'section' => $section]) }}"
                            class="btn btn-danger btn-sm float-end">Eliminar</a>
                    @else
                        <a href="#!" class="btn btn-secondary btn-sm float-end disabled">Eliminar</a>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-4">
                    <strong>Especificación de registro:</strong>
                </div>
                <div class="col-4">
                    <p>{{ $product->files->register_specification }}</p>
                </div>
                <div class="col-4">
                    @if ($product->files->register_specification)
                        <a onclick="return confirm('¿Estás seguro de eliminar la Especificación de registro?')"
                            href="{{ route('product.file.delete', ['id' => $product->id, 'file' => 'register_specification', 'section' => $section]) }}"
                            class="btn btn-danger btn-sm float-end">Eliminar</a>
                    @else
                        <a href="#!" class="btn btn-secondary btn-sm float-end disabled">Eliminar</a>
                    @endif
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-4">
                    <strong>Registro sanitario:</strong>
                </div>
                <div class="col-4">
                    <p>{{ $product->files->sanitary_register }}</p>
                </div>
                <div class="col-4">
                    @if ($product->files->sanitary_register)
                        <a onclick="return confirm('¿Estás seguro de eliminar el Registro sanitario?')"
                            href="{{ route('product.file.delete', ['id' => $product->id, 'file' => 'sanitary_register', 'section' => $section]) }}"
                            class="btn btn-danger btn-sm float-end">Eliminar</a>
                    @else
                        <a href="#!" class="btn btn-secondary btn-sm float-end disabled">Eliminar</a>
                    @endif
                </div>
            </div>
        </li>
    </ul>
    <div class="col-8">
        <input type="hidden" id="files_url" value="{{ route('product.file.upload') }}" />
        <div class="row mb-4">
            <select class="form-select border-secondary border-opacity-25" id="type"
                aria-label="Default select example">
                <option selected disabled>Selecciona el tipo de archivo.</option>
                <option value="rp_specification">Especificación RP</option>
                <option value="techical_specification">Especificación técnica</option>
                <option value="segurity_specification">Especificaciones de seguridad</option>
                <option value="register_specification">Especificación de registro</option>
                <option value="sanitary_register">Registro sanitario</option>
            </select>
        </div>
        <form id="form-drop-area" class="row border shadow rounded p-3" method="POST"
            action="{{ route('product.update', ['id' => $product->id, 'section' => 5]) }}"
            enctype="multipart/form-data">
            @csrf
            <div id="drop-area" class="border rounded shadow-sm mb-3 p-3">
                <h5 class="fw-bold">Arrastra y suelta o elige tu archivo haciendo clic aquí:</h5>
                <p class="text-danger mb-0">Solo se permiten archivos con formato .PDF .JPG .JPEG .PNG</p>
                <p class="text-danger">Los archivos deben ser menores a 3MB.</p>
                <div id="output" class="m-2"></div>
                <input type="hidden" name="prod_id" id="prod_id" value="" />
                <input type="hidden" name="file_type" id="file_type" value="" />
                <input type="file" accept=".png, .jpg, .jpeg, .pdf" id="file-input" name="files[]"
                    multiple required />
            </div>
            <button type="submit" class="btn btn-primary w-25">Subir</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var prodId = '{{ $product->id }}'; // Asumiendo que este valor se pasa desde el servidor
        document.getElementById('prod_id').value = prodId;

        document.getElementById('type').addEventListener('change', function() {
            document.getElementById('file_type').value = this.value;
        });
    });
</script>