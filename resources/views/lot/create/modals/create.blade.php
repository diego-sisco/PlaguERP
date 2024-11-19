<!-- Modal -->
<div class="modal fade" id="createLotModal" tabindex="-1" aria-labelledby="createLotModalLabel" aria-hidden="true">
    <form class="modal-dialog" action="{{ route('lot.store') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createLotModalLabel">Crear Lote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="product_id" class="form-label is-required">Producto</label>
                    <select name="product_id" id="product" class="form-select" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label is-required" for="warehouse">Almacen destino</label>
                    <select class="form-select" name="warehouse_id" id="warehouse" required>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="registration-number" class="form-label is-required">Número de Lote </label>
                    <input type="text" class="form-control" name="registration_number" id="registration-number"
                        required>
                </div>

                <div class="mb-3">
                    <label for="expiration_date" class="form-label is-required">Fecha de Expiración </label>
                    <input type="date" class="form-control" name="expiration_date" id="expiration_date" required>
                </div>

                <div class="mb-3 input-g">
                    <label for="expiration_date" class="form-label is-required">Periodo de uso </label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="start_date" id="start-date" required>
                        <input type="date" class="form-control" name="end_date" id="end-date" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label is-required">Cantidad </label>
                    <input type="number" class="form-control" name="amount" id="amount" min="0" required>
                    <div id="emailHelp" class="form-text">Las unidades como ml, g, kg, uds, entre otras, corresponden a
                        las establecidas para el producto.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('buttons.store') }}</button>
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </div>
    </form>
</div>
