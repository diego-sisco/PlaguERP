<div class="row">
    <div class="col-3 mb-3">
        <img class="border border-1 rounded p-3" src="{{ route('image.show', ['filename' => $product->image_path]) }}"
            style="width: 15em;">
    </div>
    <div class="col-9 mb-3">
        <label for="image" class="form-label">Imagen</label>
        <input type="file"
            class="form-control border-secondary border-opacity-25-file form-control border-secondary border-opacity-25"
            name="image" id="image">
    </div>
</div>

<div class="row">
    <div class="col-4 mb-3">
        <label for="name" class="form-label is-required">{{ __('product.product-data.name') }}: </label>
        <input type="text" class="form-control border-secondary border-opacity-25" name="name"
            value="{{ $product->name }}">
    </div>
    <div class="col-4 mb-3">
        <label for="business_name" class="form-label">Nombre comercial</label>
        <input type="text" class="form-control border-secondary border-opacity-25" name="business_name"
            value="{{ $product->business_name }}">
    </div>
    <div class="col-2 mb-3">
        <label for="bar_code" class="form-label">{{ __('product.product-data.bar_c') }}</label>
        <input type="number" min="0" class="form-control border-secondary border-opacity-25" name="bar_code"
            value="{{ $product->bar_code }}">
    </div>
    <div class="col-auto mb-3">
        <label for="obsolete" class="form-label is-required">{{ __('product.product-data.obsolete') }}</label>
        <select class="form-select border-secondary border-opacity-25 " name="is_obsolete" id="obsolete">
            <option value="0" {{ !$product->is_obsolete ? 'selected' : '' }}>No</option>
            <option value="1" {{ $product->is_obsolete ? 'selected' : '' }}>Si</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-2 mb-3">
        <label for="presentation" class="form-label is-required"> {{ __('product.product-data.pres') }}</label>
        <select class="form-select border-secondary border-opacity-25 " name="presentation_id" id="presentation">
            @foreach ($presentations as $presentation)
                <option value="{{ $presentation->id }}"
                    {{ $presentation->id == $product->presentation_id ? 'selected' : '' }}>{{ $presentation->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-3 mb-3">
        <label for="linebusiness" class="form-label is-required">{{ __('product.product-data.line_b') }}</label>
        <select class="form-select border-secondary border-opacity-25 " name="linebusiness_id" id="linebusiness">
            @foreach ($line_business as $line)
                <option value="{{ $line->id }}" {{ $line->id == $product->linebusiness_id ? 'selected' : '' }}>
                    {{ $line->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-3 mb-3">
        <label for="application_method" class="form-label is-required">Metricas: </label>
        <select class="form-select border-secondary border-opacity-25 " name="metric_id" id="metric">
            @foreach ($metrics as $metric)
                <option value="{{ $metric->id }}" {{ $metric->id == $product->metric_id ? 'selected' : '' }}>
                    {{ $metric->value }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col mb-3">
        <label for="description" class="form-label is-required">Descripción: </label>
        <textarea class="form-control border-secondary border-opacity-25" name="description" rows="5">{{ $product->description }}</textarea>
    </div>
    <div class="col mb-3">
        <label for="indications_execution" class="form-label">Indicaciones de ejecución: </label>
        <textarea class="form-control border-secondary border-opacity-25" name="indications_execution" rows="5">{{ $product->execution_indications }}</textarea>
    </div>
</div>
<div class="row mb-3">
    <h5 class="fw-bold pb-1 border-bottom">{{ __('service.data.application_methods') }}</h5>
    <div class="form-text text-danger pb-1" id="basic-addon4">
        * Selecciona al menos 1 método de aplicación.
    </div>

    @foreach ($application_methods as $appMethod)
        <div class="col-3">
            <div class="form-check">
                <input class="appMethod form-check-input " type="checkbox" value="{{ $appMethod->id }}"
                    onchange="setAppMethods()" {{ $product->hasAppMethod($appMethod->id) ? 'checked' : '' }} />
                <label class="form-check-label" for="app_method-{{ $appMethod->id }}">
                    {{ $appMethod->name }}
                </label>
            </div>
        </div>
    @endforeach

    <input type="hidden" id="appMethod-select" name="appMethodSelected" value="" />
</div>
