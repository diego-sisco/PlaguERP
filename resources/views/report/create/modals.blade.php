<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="product-form" action="{{ route('report.set.product', ['orderId' => $order->id]) }}"
            method="POST">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="productModalLabel">Agregar producto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="service-id" class="form-label is-required">Servicio relacionado: </label>
                    <select class="form-select" id="service-id" name="service_id" required>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product-id" class="form-label">Buscar producto: </label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="product-search" name="product_search"
                            placeholder="Example">
                        <button class="btn btn-success" type="button"
                            onclick="searchProduct()">{{ __('buttons.search') }}</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="product-id" class="form-label is-required">Productos: </label>
                    <select class="form-select" id="product-id" name="product_id" required>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product-id" class="form-label is-required">Método de aplicación: </label>
                    <select class="form-select" id="product-method-id" name="product_method_id" required>
                        @foreach ($application_methods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product-amount" class="form-label is-required">Cantidad usada: </label>
                    <input type="number" class="form-control" id="product-amount" name="product_amount"
                        placeholder="0.00" min="0" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="product-dosage" class="form-label">Dosificación (Por litro): </label>
                    <input type="text" class="form-control" id="product-dosage" name="product_dosage"
                        placeholder="10ml x Litro">
                </div>
                <div class="mb-3">
                    <label for="product-unit" class="form-label is-required">Unidades: </label>
                    <select class="form-select" id="product-metric" name="product_metric" required>
                        <option value="Mililitros (ml)">Mililitros (ml)</option>
                        <option value="Unidades (Uds)">Unidades (Uds)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product-unit" class="form-label is-required">Lote: </label>
                    <select class="form-select" id="product-lot" name="product_lot" required>

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('buttons.add') }}</button>
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    const product_incidents = @json($order->products);
    const lots = @json($lots);
    var products_found = [];

    $(document).ready(function() {
    })


    function searchProduct() {
        var formData = new FormData();
        var product_search = $('#product-search').val();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        formData.append('search', product_search);

        $.ajax({
            url: "{{ route('report.search.product') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function(response) {
                var data = response.data;
                $('#product-id').empty();
                data.products.forEach(elem => {
                    $('#product-id').append(new Option(elem.name, elem.id));
                });

                $('#product-lot').empty();
                data.lots.forEach(elem => {
                    let product_name = data.products.find(item => item.id == elem.product_id).name;
                    let lot_name = `[${elem.lot_number}] ${product_name}`;
                    $('#product-lot').append(new Option(lot_name, elem.id));
                });

                products_found = data.products;
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            },
        });
    }

    function setProduct(id) {
        $('#product-id').empty();
        //$('#product-lot').empty();

        let found_product = product_incidents.find(product => product.id == id);
        let found_lots = lots.filter(lot => lot.product_id == id);
        found_lots = found_lots.length > 0 ? found_lots : lots

        if (found_product) {
            var product = found_product.product;
            $('#service-id').val(found_product.service_id);
            $('#product-id').append(new Option(product.name, product.id));
            $('#product-method-id').val(found_product.application_method_id);
            $('#product-amount').val(found_product.amount);
            $('#product-dosage').val(found_product.dosage);
            $('#product-metric').val(found_product.metric);
        }

        if (found_lots) {
            found_lots.forEach(elem => {
                let found_data = product_incidents.find(item => item.product_id == elem.product_id);
                if(found_data) {
                    let lot_name = `[${elem.lot_number}] ${found_data.product.name}`;
                    $('#product-lot').append(new Option(lot_name, elem.id));
                }
            });
        }
    }

    function clearForm() {
        $('#product-form').find('input[type="text"], input[type="email"], input[type="number"]').val('');
        $('#product-form').find('select').val('');
        //$('#product-form').find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
    }
</script>
