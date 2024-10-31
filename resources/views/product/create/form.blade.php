<form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- Datos generales del producto -->
    <div class="row">
        <h5 class="border-bottom pb-1 fw-bold"> Datos generales </h5>
        <div class="col-4 mb-3">
            <label for="name" class="form-label is-required">{{ __('product.product-data.name') }}: </label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="name" name="name"
                required>
        </div>
        <div class="col-4 mb-3">
            <label for="business_name" class="form-label">Nombre comercial:
            </label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="business-name"
                name="business_name">
        </div>
        <div class="col-4 mb-3">
            <label for="manufacturer" class="form-label">{{ __('product.product-data.manufacturer') }}: </label>
            <input type="text" class="form-control border-secondary border-opacity-25" name="manufacturer"
                id="manufacturer">
        </div>
    </div>
    <div class="row">
        <div class="col-3 mb-3">
            <label for="presentation" class="form-label is-required"> {{ __('product.product-data.pres') }}:
            </label>
            <select class="form-select border-secondary border-opacity-25 " name="presentation_id" id="presentation"
                required>
                @foreach ($presentations as $presentation)
                    <option value="{{ $presentation->id }}">{{ $presentation->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-3">
            <label for="line-business" class="form-label is-required">{{ __('product.product-data.line_b') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " id="linebusiness" name="linebusiness_id"
                required>
                @foreach ($line_business as $line)
                    <option value="{{ $line->id }}">{{ $line->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-3 mb-3">
            <label for="application_method" class="form-label is-required">Métrica: </label>
            <select class="form-select border-secondary border-opacity-25 " name="metric_id" id="metric">
                @foreach ($metrics as $metric)
                    <option value="{{ $metric->id }}">{{ $metric->value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-6 mb-3">
            <label for="description" class="form-label">Descripción:
            </label>
            <textarea class="form-control border-secondary border-opacity-25" name="description" id="description" rows="4"> </textarea>
        </div>
        <div class="col-6 mb-3">
            <label for="execution_indications"
                class="form-label">{{ __('product.product-data.indications_execution') }}: </label>
            <textarea class="form-control border-secondary border-opacity-25" name="execution_indications"
                id="execution_indications" rows="4"> </textarea>
        </div>

        <div class="col-12 mb-3">
            <label for="image" class="form-label">{{ __('product.product-data.image') }}: </label>
            <input type="file" class="form-control-file form-control border-secondary border-opacity-25"
                name="image" id="image">
        </div>
    </div>

    <!-- Detalles del producto -->
    <div class="row">
        <h5 class="border-bottom pb-1 fw-bold"> Detalle del producto </h5>
        <div class="col-auto mb-3">
            <label for="type_b" class="form-label is-required">{{ __('product.product-data.type_b') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " name="biocide_id" id="biocide" required>
                @foreach ($biocides as $biocide)
                    <option value="{{ $biocide->id }}"> ({{ $biocide->group }}) {{ $biocide->type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2 mb-3">
            <label for="purpose" class="form-label is-required">{{ __('product.product-data.porp') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " name="purpose_id" id="purpose" required>
                @foreach ($purposes as $purpose)
                    <option value="{{ $purpose->id }}">{{ $purpose->type }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <h5 class="fw-bold pb-1 border-bottom">{{ __('service.data.pests') }}:</h5>
        <div class="form-text text-danger pb-1" id="basic-addon4">
            * Selecciona al menos 1 plaga.
        </div>
        <div class="accordion accordion-flush row p-0" id="accordionPest">
            @foreach ($pest_categories as $i => $pest_category)
                <div class="accordion-item col-4 border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $i }}" aria-expanded="true"
                            aria-controls="collapse{{ $i }}">
                            {{ $pest_category->category }}
                        </button>
                    </h2>
                    @if (!$pest_category->pests->isEmpty())
                        <div id="collapse{{ $i }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionPest">
                            <div class="accordion-body">
                                @foreach ($pest_category->pests as $pest)
                                    <div class="form-check">
                                        <input class="pest form-check-input" type="checkbox"
                                            value="{{ $pest->id }}" onchange="setPests()" />
                                        <label class="form-check-label" for="pest-{{ $pest->id }}">
                                            {{ $pest->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div id="collapse{{ $i }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionPest">
                            <div class="accordion-body text-danger">
                                No hay plagas asociadas.
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <input type="hidden" id="pest-select" name="pestSelected" value="" />
    </div>
    <div class="row mb-3">
        <h5 class="fw-bold pb-1 border-bottom">{{ __('service.data.app_methods') }}:</h5>
        <div class="form-text text-danger pb-1" id="basic-addon4">
            * Selecciona al menos 1 método de aplicación.
        </div>
        @foreach ($application_methods as $app_method)
            <div class="col-3">
                <div class="form-check">
                    <input class="appMethod form-check-input border-opacity-50" type="checkbox"
                        value="{{ $app_method->id }}" onchange="setAppMethods()"/>
                    <label class="form-check-label" for="app_method-{{ $app_method->id }}">
                        {{ $app_method->name }}
                    </label>
                </div>
            </div>
        @endforeach
        <input type="hidden" id="appMethod-select" name="appMethodSelected" value="" />
    </div>
    <div class="row">
        <h5 class="border-bottom pb-1 fw-bold"> Toxicidad </h5>
        <div class="col-auto mb-3">
            <label class="form-check-label is-required" for="is_basic">¿Es un producto toxico?</label>
            <select class="form-select" id="is-toxic" name="is_toxic" onchange="showToxic(this.value)" required>
                <option value="0" selected>No</option>
                <option value="1">Si</option>
            </select>
        </div>
    </div>
    <div class="row" id="toxic" style="display: none">
        <div class="col-automb-3">
            <select class="form-select border-secondary border-opacity-25 " name="is_toxic" id="is_toxic">
                @foreach ($toxics as $toxic)
                    <option value="{{ $toxic->id }}">{{ $toxic->name }}</option>
                @endforeach
            </select>
        </div>

    </div>

    <button type="submit" class="btn btn-primary my-3"> {{ __('buttons.store') }} </button>
</form>

<script>
    $(document).ready(function() {
    });

    function showToxic(value) {
        const is_toxic = parseInt(value);
        if(is_toxic) {
            $('#toxic').show();
        } else {
            $('#toxic').hide();
        }
    }

    function getCheckedCheckboxIDs(arr) {
        if (arr !== null) {
            return arr
                .toArray()
                .filter((checkbox) => checkbox.checked)
                .map((checkbox) => flush_id(checkbox.id));
        } else {
            return [];
        }
    }

    function storeInputJSON(e) {
        pest_arr = getCheckedCheckboxIDs($(".pest"))
        if (pest_arr.length === 0) {
            e.preventDefault();
            $('#toast-simple-message').html(
                `<span class="">
                    Por favor seleccione al menos 1 plaga
                </span>`
            );
            handle_toast();
        } else {
            $('#pests').val(JSON.stringify(pest_arr));
        }
    }

    function flush_id(str) {
        return str.replace(/\D/g, "");
    }

    function handle_toast() {
        const toastLiveExample = document.getElementById('toast-simple-notification');
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
        toastBootstrap.show();
    }
</script>
