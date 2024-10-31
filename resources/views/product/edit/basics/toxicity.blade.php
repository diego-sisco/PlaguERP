<div class="row">
    <div class="col-2 mb-3">
        <label class="form-label">¿El producto es tóxico? </label>
        <select class="form-select border-secondary border-opacity-25 " name="toxicity" id="toxicity"
            onchange="changeProps('#toxicity', this.value, '#toxic-select');">
            <option value="0" {{ $product->toxicity == 0 ? 'selected' : '' }}> No </option>
            <option value="1" {{ $product->toxicity == 1 ? 'selected' : '' }}> Si </option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-6 mb-3">
        <label class="form-label">Tipo de toxicidad: </label>
        <select class="form-select border-secondary border-opacity-25 " name="toxic" id="toxic-select">
            @foreach ($toxics as $toxic)
                <option value="{{ $toxic->id }}"
                    {{ $toxic->id == $product->toxicity_categ_id ? 'selected' : '' }}>{{ $toxic->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>