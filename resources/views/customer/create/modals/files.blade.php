<div class="modal fade" id="filesModal" tabindex="-1" aria-labelledby="filesModalLabel" aria-hidden="true">
    <form class="modal-dialog" action="{{ route('customer.file.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="filesModalLabel">Editar el archivo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
                <div class="col-12">
                    <input type="hidden" id="file-id" name="file_id" value="">
                    <div class="row border  rounded shadow-sm p-3 m-0 mb-3">
                        <h5 class="fw-bold">Arrastra y suelta o elige tus archivos haciendo clic aquí: </h5>
                        <p class="text-danger mb-0">
                            Solo se permiten archivos con formato .PDF .JPG .JPEG .PNG
                        </p>
                        <p class="text-danger">
                            Los archivos deben ser menores a 5MB.
                        </p>
                        <input type="hidden" name="customer->id" name="customer->id" value="{{ $customer->id }}">
                        <input accept=".png, .jpg, .jpeg, .pdf" type="file" name="file" required>
                    </div>

                    <div class="col m-3">
                        <label for="expirated_date" class="form-label">
                            {{ __('customer.customerfiles.expire_date') }}
                        </label>
                        <div class="col-5">
                            <input type="date" class="form-control border-secondary border-opacity-25" name="expirated_date"
                            id="expirated_date" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>

<script>
    function setFileId(file_id) {
        $('#file-id').val(file_id);
    }
</script>
