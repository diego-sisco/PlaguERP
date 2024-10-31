<input type="hidden" name="prod_id" value="{{ $product->id }}">
@foreach ($productsunit as $prodsu)
    @if ($prodsu->measure_type == 'c')
        <div id="datocom" data-valor="<?php echo $prodsu->measure_unit; ?>"></div>
        <div class="row options-group border rounded p-0 m-2">
            <div class="col-12 border-bottom bg-secondary-subtle">
                <h4 class="fw-bold mt-2">Compra</h4>
            </div>
            <div class="row p-3">
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_compra" value="p"
                            <?php echo $prodsu->measure == 'p' ? 'checked' : ''; ?>> Peso</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_compra" value="v"
                            <?php echo $prodsu->measure == 'v' ? 'checked' : ''; ?>> Volumen</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_compra" value="l"
                            <?php echo $prodsu->measure == 'l' ? 'checked' : ''; ?>> Longitud</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_compra" value="s"
                            <?php echo $prodsu->measure == 's' ? 'checked' : ''; ?>> Superficie</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_compra" value="c"
                            <?php echo $prodsu->measure == 'c' ? 'checked' : ''; ?>> contable</label>
                </div>

                <div class="col">
                    <select class="form-select border-secondary border-opacity-25 " name="selectedOption_compra">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <div class="row mt-2">
                    <label for="staticEmail" class="col-sm-3 col-form-label is-required">A cuanto
                        corresponde la
                        unidad?</label>
                    <div class="col-sm-4">
                        <input class="form-control border-secondary border-opacity-25" type="number"
                            name="unidad_compra" id="unidad_compra" value="{{ $prodsu->unit_number }}">
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($prodsu->measure_type == 'a')
        <div id="datoalm" data-valor="<?php echo $prodsu->measure_unit; ?>"></div>
        <div class="row options-group border rounded p-0 m-2">
            <div class="col-12 border-bottom bg-secondary-subtle">
                <h4 class="fw-bold mt-2">Almacenaje</h4>
            </div>
            <div class="row p-3">
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_almacenaje" value="p"
                            <?php echo $prodsu->measure == 'p' ? 'checked' : ''; ?>> Peso</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_almacenaje" value="v"
                            <?php echo $prodsu->measure == 'v' ? 'checked' : ''; ?>> Volumen</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_almacenaje" value="l"
                            <?php echo $prodsu->measure == 'l' ? 'checked' : ''; ?>> Longitud</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_almacenaje" value="s"
                            <?php echo $prodsu->measure == 's' ? 'checked' : ''; ?>> Superficie</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_almacenaje" value="c"
                            <?php echo $prodsu->measure == 'c' ? 'checked' : ''; ?>> contable</label>
                </div>
                <div class="col">
                    <select class="form-select border-secondary border-opacity-25 " id="selectOptions_almacenaje"
                        name="selectedOption_almacenaje">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <div class="row mt-2">
                    <label for="staticEmail" class="col-sm-3 col-form-label is-required">A cuanto
                        corresponde la
                        unidad?</label>
                    <div class="col-sm-4">
                        <input class="form-control border-secondary border-opacity-25" type="number"
                            name="unidad_almacenaje" id="unidad_almacenaje" value="{{ $prodsu->unit_number }}">
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($prodsu->measure_type == 'v')
        <div id="datovent" data-valor="<?php echo $prodsu->measure_unit; ?>"></div>
        <div class="row options-group border rounded p-0 m-2">
            <div class="col-12 border-bottom bg-secondary-subtle">
                <h4 class="fw-bold mt-2">Venta</h4>
            </div>
            <div class="row p-3">
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_venta" value="p"
                            <?php echo $prodsu->measure == 'p' ? 'checked' : ''; ?>> Peso</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_venta" value="v"
                            <?php echo $prodsu->measure == 'v' ? 'checked' : ''; ?>> Volumen</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_venta" value="l"
                            <?php echo $prodsu->measure == 'l' ? 'checked' : ''; ?>> Longitud</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_venta" value="s"
                            <?php echo $prodsu->measure == 's' ? 'checked' : ''; ?>> Superficie</label>
                </div>
                <div class="col">
                    <label class="form-label"><input type="radio" name="option_venta" value="c"
                            <?php echo $prodsu->measure == 'c' ? 'checked' : ''; ?>> contable</label>
                </div>
                <div class="col">
                    <select class="form-select border-secondary border-opacity-25 " id="selectOptions"
                        name="selectedOption_venta">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <div class="row mt-2">
                    <label for="staticEmail" class="col-sm-3 col-form-label is-required">A cuanto
                        corresponde la
                        unidad?</label>
                    <div class="col-sm-4">
                        <input class="form-control border-secondary border-opacity-25" type="number"
                            name="unidad_venta" id="unidad_venta" value="{{ $prodsu->unit_number }}">
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
