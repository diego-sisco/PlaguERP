<form method="POST" action="{{ route('product.update', ['id' => $product->id, 'section' => $section]) }}"
    enctype="multipart/form-data">
    @csrf
    <div class="row mb-3">
        @switch($section)
            @case(1)
                <div class="row">
                    {{ $product->image_path }}
                    <div class="col-3 mb-3">
                        @if ($product->image_path)
                            <img class="border border-1 rounded p-3"
                                src="{{ route('image.show', ['filename' => $product->image_path]) }}" style="width: 15em;">
                        @else
                            <p class="text-danger fw-bold"> Sin imagen selecionada</p>
                        @endif
                    </div>
                    <div class="col-9 mb-3">
                        <label for="image" class="form-label">{{ __('product.data.image') }}</label>
                        <input type="file"
                            class="form-control border-secondary border-opacity-25-file form-control border-secondary border-opacity-25"
                            name="image" id="image">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-3">
                        <label for="name" class="form-label is-required">{{ __('product.data.name') }}</label>
                        <input type="text" class="form-control border-secondary border-opacity-25" name="name"
                            value="{{ $product->name }}">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="business_name" class="form-label">{{ __('product.data.business_name') }}</label>
                        <input type="text" class="form-control border-secondary border-opacity-25" name="business_name"
                            value="{{ $product->business_name }}">
                    </div>
                    <div class="col-2 mb-3">
                        <label for="bar_code" class="form-label">{{ __('product.data.bar_code') }}</label>
                        <input type="number" min="0" class="form-control border-secondary border-opacity-25"
                            name="bar_code" value="{{ $product->bar_code }}">
                    </div>
                    <div class="col-auto mb-3">
                        <label for="obsolete" class="form-label is-required">{{ __('product.data.is_obsolete') }}</label>
                        <select class="form-select border-secondary border-opacity-25 " name="is_obsolete" id="obsolete">
                            <option value="0" {{ !$product->is_obsolete ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $product->is_obsolete ? 'selected' : '' }}>Si</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-2 mb-3">
                        <label for="presentation" class="form-label is-required">{{ __('product.data.presentation') }}</label>
                        <select class="form-select border-secondary border-opacity-25 " name="presentation_id"
                            id="presentation">
                            @foreach ($presentations as $presentation)
                                <option value="{{ $presentation->id }}"
                                    {{ $presentation->id == $product->presentation_id ? 'selected' : '' }}>
                                    {{ $presentation->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3 mb-3">
                        <label for="linebusiness"
                            class="form-label is-required">{{ __('product.data.line_business') }}</label>
                        <select class="form-select border-secondary border-opacity-25 " name="linebusiness_id"
                            id="linebusiness">
                            @foreach ($line_business as $line)
                                <option value="{{ $line->id }}"
                                    {{ $line->id == $product->linebusiness_id ? 'selected' : '' }}>
                                    {{ $line->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3 mb-3">
                        <label for="application_method" class="form-label is-required">{{ __('product.data.metric') }}</label>
                        <select class="form-select border-secondary border-opacity-25 " name="metric_id" id="metric">
                            @foreach ($metrics as $metric)
                                <option value="{{ $metric->id }}"
                                    {{ $metric->id == $product->metric_id ? 'selected' : '' }}>
                                    {{ $metric->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label for="description" class="form-label is-required">{{ __('product.data.description') }}</label>
                        <textarea class="form-control border-secondary border-opacity-25" name="description" rows="5">{{ $product->description }}</textarea>
                    </div>
                    <div class="col mb-3">
                        <label for="indications_execution" class="form-label">{{ __('product.data.execution_indications') }}</label>
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
            @break

            @case(2)
                <div class="row">
                    <div class="col-4 mb-3">
                        <label for="manufacturer" class="form-label">{{ __('product.data.manufacturer') }}
                        </label>
                        <input type="text" class="form-control border-secondary border-opacity-25" name="manufacturer"
                            value="{{ $product->manufacturer }}">
                    </div>

                    <div class="col-4 mb-3">
                        <label for="register_number" class="form-label">{{ __('product.data.register_number') }}:
                        </label>
                        <input type="text" class="form-control border-secondary border-opacity-25" name="register_number"
                            value="{{ $product->register_number }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-3">
                        <label for="activin" class="form-label">{{ __('product.data.active_ingredient') }}
                            :</label>
                        <input type="text" class="form-control border-secondary border-opacity-25" name="activin"
                            value="{{ $product->active_ingredient }}">
                    </div>
                    <div class="col-2 mb-3">
                        <label for="per_active_ingredient"
                            class="form-label">{{ __('product.data.per_active_ingredient') }} :</label>
                        <input type="number" step="0.0001" class="form-control border-secondary border-opacity-25"
                            name="per_active_ingredient" value="{{ $product->per_active_ingredient }}" min=0>
                    </div>
                    <div class="col-2 mb-3">
                        <label for="dosage" class="form-label">{{ __('product.data.dosage') }} :</label>
                        <input type="text" class="form-control border-secondary border-opacity-25" name="dosage"
                            value="{{ $product->dosage }}">
                    </div>
                    <div class="col-2 mb-3">
                        <label for="safety_period" class="form-label">{{ __('product.data.safety_period') }}
                            :</label>
                        <input type="text" class="form-control border-secondary border-opacity-25" name="safety_period"
                            value="{{ $product->safety_period }}" maxlength="50">
                    </div>
                    <div class="col-2 mb-3">
                        <label for="residual_effect" class="form-label">{{ __('product.data.residual_effect') }}
                            :</label>
                        <input type="text" class="form-control border-secondary border-opacity-25" name="residual_effect"
                            value="{{ $product->residual_effect }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 mb-3">
                        <label for="valid_date" class="form-label">{{ __('product.data.validity_date') }}</label>
                        <input type="date" class="form-control border-secondary border-opacity-25" name="validity_date"
                            value="{{ $product->validity_date }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-3 mb-3">
                        <label for="purpose" class="form-label is-required">{{ __('product.data.purpose') }}</label>
                        <select class="form-select border-secondary border-opacity-25 " name="purpose" id="purpose">
                            @foreach ($purposes as $purpose)
                                <option value="{{ $purpose->id }}"
                                    {{ $purpose->id == $product->purpose_id ? 'selected' : '' }}>{{ $purpose->type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="biocide" class="form-label is-required">{{ __('product.data.biocide') }}</label>
                        <select class="form-select border-secondary border-opacity-25 " name="biocide_id" id="biocide"
                            required>
                            @foreach ($biocides as $biocide)
                                <option value="{{ $biocide->id }}"
                                    {{ $product->biocide_id == $biocide->id ? 'selected' : '' }}>
                                    ({{ $biocide->group }})
                                    {{ $biocide->type }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
            @break

            @case(3)
                <div class="row">
                    <div class="col-auto mb-3">
                        <label class="form-label">{{ __('product.data.is_toxic') }}</label>
                        <select class="form-select border-secondary border-opacity-25 " name="toxicity" id="toxicity"
                            onchange="changeProps('#toxicity', this.value, '#toxic-select');">
                            <option value="0" {{ !$product->toxicity ? 'selected' : '' }}> No </option>
                            <option value="1" {{ $product->toxicity ? 'selected' : '' }}> Si </option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-auto mb-3">
                        <label class="form-label">{{ __('product.data.toxicity_category') }}</label>
                        <select class="form-select border-secondary border-opacity-25 " name="toxic" id="toxic-select">
                            <option value="" {{ $product->toxicity_categ_id == null ? 'selected' : '' }}> Libre de toxicidad </option>
                            @foreach ($toxics as $toxic)
                                <option value="{{ $toxic->id }}"
                                    {{ $toxic->id == $product->toxicity_categ_id ? 'selected' : '' }}>{{ $toxic->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @break

            @case(4)
                <div class="row">
                    <div class="col-auto mb-3">
                        <label for="purchase_price"
                            class="form-label">{{ __('product.data.purchase_price') }}</label>
                        <input type="number" class="form-control border-secondary border-opacity-25" name="purchase_price"
                            value="{{ $product->purchase_price }}" min="0" step="0.01">
                    </div>
                    <div class="col-auto mb-3">
                        <label for="selling_price"
                            class="form-label">{{ __('product.data.selling_price') }}</label>
                        <input type="number" class="form-control border-secondary border-opacity-25" name="selling_price"
                            id="selling-price" value="{{ $product->selling_price }}" min="0" step="0.01">
                    </div>
                    <div class="col-auto mb-3">
                        <label for="min_purchase_unit"
                            class="form-label">{{ __('product.data.min_purchase_unit') }}</label>
                        <input type="number" class="form-control border-secondary border-opacity-25"
                            name="min_purchase_unit" value="{{ $product->min_purchase_unit }}" min="0">
                    </div>
                    <div class="col-auto mb-3">
                        <label for="mult_purchase"
                            class="form-label">{{ __('product.data.mult_purchase') }}</label>
                        <input type="number" class="form-control border-secondary border-opacity-25" name="mult_purchase"
                            value="{{ $product->mult_purchase }}" min="0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 mb-3">
                        <label for="supplier" class="form-label">{{ __('product.data.supplier_name') }}</label>
                        <input type="text" class="form-control" id="supplier-name" name="supplier_name"
                            value="{{ $product->supplier_name }}">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="supplier" class="form-label">{{ __('product.data.supplier_email') }}</label>
                        <input type="email" class="form-control" id="supplier-email" name="supplier_email"
                            value="{{ $product->supplier_email }}">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="supplier" class="form-label">{{ __('product.data.supplier_phone') }}</label>
                        <input type="number" class="form-control" id="supplier-phone" name="supplier_phone"
                            value="{{ $product->supplier_number }}" min=0>
                    </div>
                    <div class="col-auto mb-3">
                        <label for="supplier" class="form-label">{{ __('product.data.is_selling') }}</label>
                        <select class="form-select border-secondary border-opacity-25 " name="product_sale" id="product-sale"
                            onchange="changeProps('#product-sale', this.value, '#selling-price')">
                            <option value="1" {{ $product->is_selling ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ !$product->is_selling ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-auto mb-3">
                        <label for="subaccount_purchases"
                            class="form-label">{{ __('product.data.subaccount_purchases') }}</label>
                        <input type="number" class="form-control border-secondary border-opacity-25"
                            name="subaccount_purchases" value="{{ $product->subaccount_purchases }}" min=0>
                    </div>
                    <div class="col-auto mb-3">
                        <label for="subaccount_sales"
                            class="form-label">{{ __('product.data.subaccount_sales') }}</label>
                        <input type="number" class="form-control border-secondary border-opacity-25" name="subaccount_sales"
                            value="{{ $product->subaccount_sales }}" min=0>
                    </div>
                </div>
            @break

            @case(5)
                <div class="row mb-4">
                    <div class="accordion accordion-flush row p-0" id="accordionPest">
                        @foreach ($pest_categories as $i => $pest_category)
                            <div class="accordion-item col-4 border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed border-bottom" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}"
                                        aria-expanded="true" aria-controls="collapse{{ $i }}">
                                        {{ $pest_category->category }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $i }}" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionPest">
                                    <div class="accordion-body">
                                        @if (!$pest_category->pests->isEmpty())
                                            @foreach ($pest_category->pests as $pest)
                                                <div class="form-check">
                                                    <input class="pest form-check-input " type="checkbox"
                                                        value="{{ $pest->id }}" onchange="setPests()"
                                                        {{ $product->hasPest($pest->id) ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="pest-{{ $pest->id }}">
                                                        {{ $pest->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-danger fw-bold"> No hay plagas asociadas </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" id="pest-select" name="pestSelected" value="" />
                </div>
            @break

            @case(6)
                <div class="col-12 mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inputModal" onclick="resetForm()">Crear
                        insumo</button>
                </div>
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col-1">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Método de aplicación</th>
                            <th scope="col">Metros cuadrados (m²)</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Costo ($)</th>
                            <th scope="col"> {{ __('buttons.actions') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inputs as $input)
                            <tr>
                                <th scope="row">{{ $input->id }}</th>
                                <td class="align-middle">{{ $input->product->name }}</td>
                                <td class="align-middle">
                                    {{ $input->appMethod->name }}
                                </td>
                                <td class="align-middle">{{ $input->zone_m2 }}</td>
                                <td class="align-middle">{{ $input->cant }}</td>
                                <td class="align-middle">{{ $input->cost }}</td>
                                <td class="align-middle">
                                    <button type="button" class="btn btn-secondary btn-sm" data-input="{{ $input }}"
                                        data-bs-toggle="modal" data-bs-target="#inputModal" onclick="setInput(this)">
                                        <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                    </button>
                                    <a href="{{ route('product.input.destroy', ['id' => $input->id]) }}"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                                        <i class="bi bi-x-lg"></i> {{ __('buttons.delete') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @break

            @case(7)
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre del archivo</th>
                                    <th scope="col">Archivo</th>
                                    <th scope="col">Fecha de vencimiento</th>
                                    <th scope="col">{{ __('buttons.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filenames as $index => $filename)
                                    <tr>
                                        <th class="align-middle" scope="row">{{ $index + 1 }}</th>
                                        <td class="align-middle">{{ $filename->name }}</td>
                                        <td class="align-middle">
                                            @if ($product->file($filename->id))
                                                <a
                                                    href="{{ route('product.file.download', ['id' => $product->id, 'file' => $filename->id]) }}">
                                                    {{ $filename->name }} </a>
                                            @else
                                                <p class="text-danger fw-bold">Sin archivo</p>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $product->file($filename->id)->expirated_at ?? 'S/A' }}
                                        </td>
                                        <td class="align-middle">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#fileModal" data-file="{{ $product->file($filename->id) ?? $filename }}"
                                                onclick="setFile(this)"><i
                                                    class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}</button>
                                            <a href="{{ route('product.file.destroy', ['id' => $product->id, 'file' => $filename->id]) }}" class="btn btn-danger btn-sm"
                                                onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                                                <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @break

        @endswitch
    </div>

    @if ($section < 6)
        <button type="submit" class="btn btn-primary mb-3">{{ __('modals.button.save') }}</button>
    @endif
</form>

<script src="{{ asset('js/service/control.min.js') }}"></script>
