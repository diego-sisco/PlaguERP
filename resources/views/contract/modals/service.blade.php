<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="serviceModalLabel">Resultados: </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="services-list"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="createServicesList();" data-bs-dismiss="modal">{{ __('buttons.accept') }}</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </div>
    </div>
</div>