<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body" id="preview">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    onclick="submitContract()">{{ __('buttons.store') }}</button>
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </div>
    </div>
</div>