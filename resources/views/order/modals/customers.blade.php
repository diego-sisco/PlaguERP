<!-- Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="customerModalLabel">Resultados: </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group" id="customer-list"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-customer" data-bs-dismiss="modal">{{ __('buttons.accept')}}</button>
                <button type="button" class="btn btn-danger" id="save-customer" data-bs-dismiss="modal">{{ __('buttons.cancel')}}</button>
            </div>
        </div>
    </div>
</div>