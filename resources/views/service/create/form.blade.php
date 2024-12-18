
<form method="POST" action="{{ route('service.store') }}"
    enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12 mb-3">
            <label for="name" class="form-label is-required">{{ __('service.data.name') }}</label>
            <div class="input-group">
                <select class="input-group-text bg-secondary-subtle" id="prefix" name="prefix"
                    onchange="set_instructions()">
                    @foreach ($prefixes as $prefix)
                        <option value="{{ $prefix->id }}" {{ $prefix->id == 1 ? 'selected' : '' }}>{{ $prefix->name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" class="form-control border-secondary border-opacity-50 rounded-end " id="name" name="name"
                    placeholder="Control de plagas" value="{{ old('name') }}" autocomplete="off"
                    required />
            </div>
        </div>

        <div class="col-3 mb-3">
            <label for="name" class="form-label is-required">{{ __('service.data.type') }}</label>
            <select class="form-select border-secondary border-opacity-50 " id="service_type_id" name="service_type_id" required>
                @foreach ($service_types as $service_type)
                    <option value="{{ $service_type->id }}">{{ $service_type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-3 mb-3">
            <label for="name" class="form-label is-required">{{ __('service.data.business_line') }}</label>
            <select class="form-select border-secondary border-opacity-50 " id="business_line_id" name="business_line_id" required>
                @foreach ($business_lines as $business_line)
                    <option value="{{ $business_line->id }}" {{ $business_line->id == 2 ? 'selected' : '' }}>{{ $business_line->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-3">
            <div class="row">
                <div class="col-4 mb-1">
                    <label for="name" class="form-label">Duración</label>
                    <input type="number" class="form-control border-secondary border-opacity-50" id="time" name="time"
                        min="0" max="59" placeholder="0" value="0" />
                </div>
                <div class="col-8 mb-1">
                    <label for="name" class="form-label">{{ __('service.data.time_unit') }}</label>
                    <select class="form-select border-secondary border-opacity-50 " id="time_unit" name="time_unit">
                        @for ($i = 0; $i < count($time_types); $i++)
                            <option value="{{ $i + 1 }}">{{ $time_types[$i] }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <h5 class="fw-bold pb-1 border-bottom">{{ __('service.data.pests') }}</h5>
            <div class="form-text text-danger pb-1" id="basic-addon4">
                * Selecciona al menos 1 plaga.
            </div>
            <div class="accordion accordion-flush row p-0" id="accordionPest">
                @foreach ($pest_categories as $i => $pest_category)
                    <div class="accordion-item col-4 border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed border-bottom" type="button" data-bs-toggle="collapse"
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
                                <div class="accordion-body text-danger fw-bold">
                                    No hay plagas asociadas.
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <input type="hidden" id="pest-select" name="pestSelected" value="" />
        </div>

        <div class="row mb-4">
            <h5 class="fw-bold pb-1 border-bottom">{{ __('service.data.application_methods') }}</h5>
            <div class="form-text text-danger pb-1" id="basic-addon4">
                * Selecciona al menos 1 método de aplicación.
            </div>
            @foreach ($application_methods as $app_method)
                <div class="col-3">
                    <div class="form-check">
                        <input class="appMethod form-check-input" type="checkbox"
                            value="{{ $app_method->id }}" onchange="setAppMethods()" />
                        <label class="form-check-label" for="app_method-{{ $app_method->id }}">
                            {{ $app_method->name }}
                        </label>
                    </div>
                </div>
            @endforeach
            <input type="hidden" id="appMethod-select" name="appMethodSelected" value="" />
        </div>

        <div class="col-12 mb-3">
            <h5 class="fw-bold pb-1 mb-3 border-bottom">Descripción del servicio</h5>
            <textarea type="text" class="form-control border-secondary border-opacity-50 p-3" id="description" name="description"
                rows="7" placeholder="Incluye una descripción, directrices o prevenciones sobre el servicio." style="text-align: justify;"></textarea>
        </div>

        <div class="col-auto mb-3">
            <label for="name" class="form-label is-required">{{ __('service.data.cost') }}</label>
            <div class="input-group mb-0">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="cost" name="cost" step="0.01"
                    min="0" placeholder="0" value=0.00 required />
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary my-3">
        {{ __('buttons.store') }} 
    </button>

</form>

<script>
    $(document).ready(function() {
        $('input[type="checkbox"]').prop('checked', false);
    });

    function set_instructions() {
        var description = ``;
        var value = $('#prefix').val();

        if (value == 2) {
            description = `
            ANTES DE LA APLICACIÓN QUÍMICA
                - Identificar la plaga a controlar.
                - No debe encontrarse personal en el área.
                - No debe de haber materia prima expuesta.
                - Asegurar que la aplicación no afecte el proceso, producción o a terceros.

            DURANTE DE LA APLICACIÓN QUÍMICA
                - En el área solo debe de encontrarse el técnico aplicador.

            DESPUÉS DE LA APLICACIÓN QUÍMICA
                - Respetar el tiempo de reentrada conforme a la etiqueta del producto a utilizar.
                - Realizar recolección de plaga o limpieza necesaria al tipo de área.
                
        `;
        }

        $('#description').html(description);
    }
</script>
