<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <form class="modal-content" id="product-form" action="{{ route('report.set.product', ['orderId' => $order->id]) }}"
            method="POST">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="productModalLabel">Producto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="service-id" class="form-label is-required">Servicio relacionado</label>
                    <select class="form-select" id="service" name="service_id" required>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product-id" class="form-label is-required">Método de aplicación</label>
                    <select class="form-select" id="application-method" name="application_method_id" required>
                        @foreach ($application_methods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product-id" class="form-label is-required">Producto</label>
                    <select class="form-select" id="product" name="product_id" onchange="setMetric(this.value)"
                        required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product-amount" class="form-label is-required">Cantidad usada</label>
                    <div class="input-group">
                    <input type="number" class="form-control" id="amount" name="amount" placeholder="0.00"
                        min="0" step="0.01" required>
                        <select class="form-select" id="metric" name="metric_id" disabled>
                            @foreach ($metrics as $metric)
                                <option value="{{ $metric->id }}">{{ $metric->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="product-dosage" class="form-label">Dosificación (Por litro)</label>
                    <input type="text" class="form-control" id="dosage" name="dosage" placeholder="10ml x Litro">
                </div>
                <div class="mb-3">
                    <label for="product-unit" class="form-label is-required">Lote: </label>
                    <select class="form-select" id="lot" name="lot_id" required>
                        @foreach ($lots as $lot)
                            <option value="{{ $lot->id }}">[ {{ $lot->registration_number }} ]
                                {{ $lot->product->name }}</option>
                        @endforeach
                    </select>
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
    const products = @json($products);
    const lots = @json($lots);
    var products_found = [];

    function setProduct(element) {
        const data = JSON.parse(element.getAttribute("data-product"));
        console.log(data);

        $('#service').val(data.service_id);
        $('#application-method').val(data.application_method_id ?? 1);
        $('#product').val(data.product_id);
        $('#metric').val(data.product.metric_id);
        $('#lot').val(data.lot_id);
        $('#amount').val(data.amount)
        $('#dosage').val(data.product.dosage)
    }

    function cleanForm() {
        $('#product-form').find('input[type="text"], input[type="email"], input[type="number"]').val('');
        $('#product-form').find('select').val(1);
        $('#product-form').find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
    }
</script>
