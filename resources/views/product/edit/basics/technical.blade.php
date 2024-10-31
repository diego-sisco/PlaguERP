<div class="row">
    <div class="col-4 mb-3">
        <label for="manufacturer" class="form-label">{{ __('product.product-data.manufacturer') }}:
        </label>
        <input type="text" class="form-control border-secondary border-opacity-25" name="manufacturer"
            value="{{ $product->manufacturer }}">
    </div>

    <div class="col-4 mb-3">
        <label for="register_number" class="form-label">{{ __('product.product-data.register_number') }}:
        </label>
        <input type="text" class="form-control border-secondary border-opacity-25"
            name="register_number" value="{{ $product->register_number }}">
    </div>
</div>

<div class="row">
    <div class="col-4 mb-3">
        <label for="activin" class="form-label">{{ __('product.product-data.active_ingredient') }}
            :</label>
        <input type="text" class="form-control border-secondary border-opacity-25" name="activin"
            value="{{ $product->active_ingredient }}">
    </div>
    <div class="col-2 mb-3">
        <label for="per_active_ingredient"
            class="form-label">{{ __('product.product-data.peractive_ingredient') }} :</label>
        <input type="number" step="0.0001" class="form-control border-secondary border-opacity-25"
            name="per_active_ingredient" value="{{ $product->per_active_ingredient }}" min=0>
    </div>
    <div class="col-2 mb-3">
        <label for="dosage" class="form-label">{{ __('product.product-data.dos') }} :</label>
        <input type="text" class="form-control border-secondary border-opacity-25" name="dosage"
            value="{{ $product->dosage }}">
    </div>
    <div class="col-2 mb-3">
        <label for="safety_period" class="form-label">{{ __('product.product-data.safety_period') }}
            :</label>
        <input type="text" class="form-control border-secondary border-opacity-25"
            name="safety_period" value="{{ $product->safety_period }}" maxlength="50">
    </div>
    <div class="col-2 mb-3">
        <label for="residual_effect" class="form-label">{{ __('product.product-data.res_ef') }}
            :</label>
        <input type="text" class="form-control border-secondary border-opacity-25"
            name="residual_effect" value="{{ $product->residual_effect }}">
    </div>
</div>
<div class="row">
    <div class="col-3 mb-3">
        <label for="valid_date" class="form-label">{{ __('product.product-data.valid_date') }}</label>
        <input type="date" class="form-control border-secondary border-opacity-25" name="valid_date"
            value="{{ $product->validity_date }}">
    </div>
</div>

<div class="row">
    <div class="col-3 mb-3">
        <label for="purpose"
            class="form-label is-required">Finalidad</label>
        <select class="form-select border-secondary border-opacity-25 " name="purpose" id="purpose">
            @foreach ($purposes as $purpose)
                    <option value="{{ $purpose->id }}" {{ $purpose->id == $product->purpose_id ? 'selected' : ''}}>{{ $purpose->type }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 mb-3">
        <label for="biocide"
            class="form-label is-required">{{ __('product.product-data.type_b') }}</label>
        <select class="form-select border-secondary border-opacity-25 " name="biocide_id" id="biocide" required>
            @foreach ($biocides as $biocide)
                <option value="{{ $biocide->id }}" {{ $product->biocide_id == $biocide->id ? 'selected' : '' }}>
                    ({{ $biocide->group }}) {{ $biocide->type }}
                </option>
            @endforeach

        </select>
    </div>
</div>