<div class="modal fade" id="editInputModal" tabindex="-1" aria-labelledby="editInputModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" class="form" method="POST" action="{{ route('product.input', [ 'id' => $product->id]) }}">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="inputModalLabel">Editar insumo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="product" class="form-label">Producto</label>
                    <input type="text" class="form-control" id="product" name="product-id"
                        value="{{ $product->name }}" disabled>
                </div>
                <div class="mb-3">
                    <label for="appMethod" class="form-label is-required">Método de aplicación</label>
                    <select class="form-select" id="edit-appMethod" name="application_method_id" required>
                        @foreach ($product->applicationMethods as $appMethod)
                            <option value="{{ $appMethod->id }}">{{ $appMethod->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="zone" class="form-label is-required">Zona o área (m²)</label>
                    <input type="number" class="form-control" id="edit-zone-m2" name="zone_m2" value="0"
                        min="0" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="cant" class="form-label is-required">Cantidad</label>
                    <input type="number" class="form-control" id="edit-cant" name="cant" value="0"
                        min="0" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="cost" class="form-label is-required">Costo</label>
                    <input type="number" class="form-control" id="edit-cost" name="cost" value="0"
                        min="0" step="0.01" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('buttons.update') }}</button>
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </form>
    </div>
</div>
