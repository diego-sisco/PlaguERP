<div class="modal fade" id="filesModal" tabindex="-1" aria-labelledby="filesModalLabel" aria-hidden="true">
    <form class="modal-dialog" action="{{ route('user.file.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="filesModalLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
                <div class="col-12 mb-3">
                    <label for="end_time" class="form-label">Fecha de expiración:</label>
                    <input type="date" class="form-control border-secondary border-opacity-25" id="expirated-at"
                        name="expirated_at">
                </div>
                <div class="col-12">
                    <input type="hidden" id="file-id" name="file_id" value="">
                    <div class="row border  rounded shadow-sm p-3 m-0 mb-2">
                        <h5 class="fw-bold">Arrastra y suelta o elige tus archivos haciendo clic aquí: </h5>
                        <p class="text-danger mb-0">
                            Solo se permiten archivos con formato .PDF .JPG .JPEG .PNG
                        </p>
                        <p class="text-danger">
                            Los archivos deben ser menores a 5MB.
                        </p>
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input accept=".png, .jpg, .jpeg, .pdf" type="file" name="file" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"> {{ __('buttons.update') }} </button>
            </div>
        </div>
    </form>
</div>

<script>
    function setFileId(id, filename) {
        $('#filesModalLabel').text('Editar ' + filename);
        $('#file-id').val(id);
    }
</script>