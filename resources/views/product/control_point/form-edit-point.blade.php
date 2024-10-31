<form class="form" action="{{ route('point.update', $point->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row mb-3">
        <div class="col-6">
            <label for="name" class="form-label">{{ __('product.product-data.name') }} punto de control: </label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="name" name="name"
                value="{{ $point->name }}" />
        </div>

        <div class="col-1">
            <label for="colorPicker" class="form-label">Color:</label>
            <input type="color" style="height: 40px;"
                class="form-control-file form-control border-secondary border-opacity-25" id="color" name="color"
                value="{{ $point->color }}">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-5 mb-2">
            <label for="device" class="form-label is-required"> Dispositivo asociado: </label>
            <select class="form-select border-secondary border-opacity-25 " name="associated_device_id"
                id="associated-device-id" required>
                @foreach ($devices as $device)
                    <option value="{{ $device->id }}" {{ $point->product->id == $device->id ? 'selected' : ''}}>{{ $device->name }}</option>
                @endforeach
            </select>
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
                            <input class="product form-check-input " type="checkbox"
                                value="{{ $product->id }}" onchange="setProducts()" {{ $point->hasProduct($product->id) ? 'checked' : ''}}/>
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

    <button type="submit" class="btn btn-primary mt-2">{{ __('modals.button.save') }}</button>

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
</form>
