<form method="POST" class="form" action="{{ route('point.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-4 mb-3">
            <label for="name" class="form-label is-required">Nombre: </label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="name" name="name"
                placeholder="">
        </div>
        <div class="col-4 mb-3">
            <label for="device" class="form-label is-required"> Dispositivo asociado: </label>
            <select class="form-select border-secondary border-opacity-25 " name="device_id"
                id="associated-device-id" required>
                @foreach ($devices as $device)
                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto mb-3">
            <label for="colorPicker" class="form-label is-required">Color: </label>
            <input type="color" style="height: 40px;"
                class="form-control-file form-control border-secondary border-opacity-25" id="color" name="color"
                required>
        </div>
    </div>

    <div class="row mb-3">
        <h5 class="fw-bold pb-1 border-bottom">Productos asociados:</h5>
        @if (!$products->isEmpty())
            <div class="form-text text-danger pb-1" id="basic-addon4">
                * Selecciona al menos 1 producto.
            </div>
            @foreach ($products as $product)
                @if ($product->presentation_id != 1)
                    <div class="col-3">
                        <div class="form-check">
                            <input class="product form-check-input " type="checkbox" value="{{ $product->id }}"
                                onchange="setProducts()" />
                            <label class="form-check-label" for="product-{{ $product->id }}">
                                {{ $product->name }}
                            </label>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="form-text text-danger pb-1" id="basic-addon4">
                Sin productos disponibles
            </div>
        @endif
        <input type="hidden" id="selected-products" name="selectedProducts" value="" />
    </div>

    <button type="submit" class="btn btn-primary my-3">{{ __('buttons.store') }}</button>
</form>


<script>
    function setProducts() {
        var input = '#selected-products'
        var productClass = '.product';
        var checkedProducts = []
        $(productClass).each(function() {
            if ($(this).is(':checked')) {
                checkedProducts.push($(this).val());
            }
        });
        $(input).val(JSON.stringify(checkedProducts));
    }
</script>
