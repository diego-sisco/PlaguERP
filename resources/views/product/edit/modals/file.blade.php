<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" class="form" method="POST"
            action="{{ route('product.file.upload', ['id' => $product->id]) }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="fileModalLabel">Archivo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="filename" class="form-label is-required">Tipo de archivo</label>
                    <select class="form-select" id="filename" name="filename_id" required>
                        @foreach ($filenames as $filename)
                            <option value="{{ $filename->id }}">{{ $filename->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Fecha de expiración</label>
                    <input type="date" class="form-control" id="expirated-at" name="expirated_at">
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label is-required">Archivo</label>
                    <input type="file" class="form-control border-secondary border-opacity-25 rounded"
                        accept=".pdf .png, .jpg, .jpeg" name="file" id="file" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('buttons.store') }}</button>
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    function setFile(element) {
        const data = JSON.parse(element.getAttribute("data-file"));
        if ('expirated_at' in data) {
            $('#filename').val(data.filename_id);
            $('#expirated-at').val(data.expirated_at);       
        } else {
            $('#filename').val(data.id);    
            $('#expirated-at').val('');       
        }
    }
</script>