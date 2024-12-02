<div class="row">
    <div class="col-4 mb-3">
        <label for="zcom" class="form-label is-required">{{ __('customer.data.branch') }}:
        </label>
        <select class="form-select border-secondary border-opacity-25 " id="branch"
            name="branch_id">
            @foreach ($branches as $branch)
            <option value="{{ $branch->id }}" {{ $customer->branch_id == $branch->id }}>{{ $branch->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto mb-3">
        <label for="meters" class="form-label">Metros cuadrados (m²): </label>
        <input type="number" min="0" class="form-control border-secondary border-opacity-25"
            id="meters" name="meters" value="{{ $customer->meters }}">
    </div>
    <div class="col-auto mb-3">
        <label for="unit" class="form-label is-required">Unidad: </label>
        <select class="form-select border-secondary border-opacity-25 " id="unit" name="unit">
            <option value="1">Cuadrados</option>
            <option value="2">Cubicos</option>
            <option value="3">Indefinido</option>
        </select>
    </div>
    <div class="col-auto mb-3">
        <label for="print_doc" class="form-label is-required">Requiere documentos impresos :</label>
        <select class="form-select border-secondary border-opacity-25 " name="print_doc" id="print_doc">
            <option value="1" @if ($customer->print_doc == 1) selected @endif>Si</option>
            <option value="0" @if ($customer->print_doc == 0) selected @endif>No</option>
        </select>
    </div>
    <div class="col-auto mb-3">
        <label for="validate_certificate" class="form-label">Requiere certificados validados :</label>
        <select class="form-select border-secondary border-opacity-25 " name="validate_certificate"
            id="validate_certificate">
            <option value="1" @if ($customer->validate_certificate == 1) selected @endif>Si</option>
            <option value="0" @if ($customer->validate_certificate == 0) selected @endif>No</option>
        </select>
    </div>
</div>