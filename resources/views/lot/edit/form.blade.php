<form method="POST" class="form" action="{{ route('lot.update', $lot->id) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-3 mb-3">
            <label class="form-label is-required" for="product">Producto</label>
            <select name="product_id" id="product" class="form-select" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $lot->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-3">
            <label class="form-label is-required" for="warehouse">Almacen destino</label>
            <select class="form-select" name="warehouse_id" id="warehouse" required>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ $lot->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2 mb-3">
            <label class="form-label is-required" for="registration_number">Número de Lote</label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="registration-number" name="registration_number" value="{{ $lot->registration_number }}" required>
        </div>
        <div class="col-2 mb-3">
            <label class="form-label is-required" for="product_id">Fecha de expiración</label>
            <input type="date" class="form-control border-secondary border-opacity-25" id="expiration-date" name="expiration_date" value="{{ $lot->expiration_date }}" required>
        </div>
        <div class="col-2 mb-3">
            <label class="form-label is-required" for="amount">Cantidad</label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="amount" name="amount" value="{{ $lot->amount }}" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">{{ __('buttons.update') }}</button>
</form>