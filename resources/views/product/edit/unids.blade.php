<div class="row">
    <div class="col-3 mb-3">
        <label for="purchase_price"
            class="form-label">{{ __('product.econom-data-product.purchase_price') }}:</label>
        <input type="number" class="form-control border-secondary border-opacity-50" name="econ_price"
            value="{{ $data_economic->purchase_price }}">
    </div>
    <div class="col-2 mb-3">
        <label for="min_purchase_unit"
            class="form-label">{{ __('product.econom-data-product.min_purchase_unit') }}:</label>
        <input type="number" class="form-control border-secondary border-opacity-50"
            name="mult_purchase_unit" value="{{ $data_economic->min_purchase_unit }}">
    </div>
    <div class="col-3 mb-3">
        <label for="mult_purchase"
            class="form-label">{{ __('product.econom-data-product.mult_purchase') }}:</label>
        <input type="number" class="form-control border-secondary border-opacity-50"
            name="mult_purchase" value="{{ $data_economic->mult_purchase }}">
    </div>
    <div class="col-4 mb-3">
        <label for="supplier" class="form-label">{{ __('product.econom-data-product.supplier') }}
            :</label>
        <select class="form-control border-secondary border-opacity-50" name="supplier_id"
            id="supplier_id">
            @foreach ($suppliers as $item)
                @if ($item->id == $data_economic->supplier_id)
                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                @else
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class="col-2 mb-3">
        <label for="supplier" class="form-label">¿Disponible para venta?</label>
        <select class="form-select border-secondary border-opacity-50 " name="product_sale"
            id="product-sale" onchange="changeProps('#product-sale', this.value, '#selling-price')">
            <option value="1" @if ($data_economic->selling == true) selected @endif>Sí</option>
            <option value="0" @if ($data_economic->selling == false) selected @endif>No</option>
        </select>
    </div>

    <div class="col-3 mb-3">
        <label for="selling_price"
            class="form-label">{{ __('product.econom-data-product.selling_price') }}:</label>
        <input type="number" class="form-control border-secondary border-opacity-50"
            name="selling_price" id="selling-price" value="{{ $data_economic->selling_price }}">
    </div>
</div>

<div class="row">
    <div class="col-4 mb-3">
        <label for="subaccount_purchases"
            class="form-label">{{ __('product.econom-data-product.subaccount_purchases') }} :</label>
        <input type="number" class="form-control border-secondary border-opacity-50"
            name="subaccount_purchases" value="{{ $data_economic->subaccount_purchases }}" min=0>
    </div>
    <div class="col-4 mb-3">
        <label for="subaccount_sales"
            class="form-label">{{ __('product.econom-data-product.subaccount_sales') }}
            :</label>
        <input type="number" class="form-control border-secondary border-opacity-50"
            name="subaccount_sales" value="{{ $data_economic->subaccount_sales }}" min=0>
    </div>
</div>