<form id="create_service_form" class="form" method="POST"
    action="{{ route('service.update', ['id' => $service->id]) }}" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-12 mb-3">
            <label for="name" class="form-label is-required">{{ __('service.data.name') }}</label>
            <div class="input-group">
                <select class="input-group-text bg-secondary-subtle" id="prefix" name="prefix"
                    onchange="set_instructions()">
                    @foreach ($prefixes as $prefix)
                        <option value="{{ $prefix->id }}" {{ $prefix->id == $service->prefix ? 'selected' : '' }}>
                            {{ $prefix->name }}</option>
                    @endforeach
                </select>
                <input type="text" class="form-control border-secondary border-opacity-50 rounded-end" id="name"
                    name="name" placeholder="{{ __('service.data.input.name') }}" value="{{ $service->name }}"
                    autocomplete="off" required />
            </div>
        </div>

        <div class="col-3 mb-3">
            <label for="name" class="form-label is-required">{{ __('service.data.service_type') }} </label>
            <select class="form-select border-secondary border-opacity-50" id="service_type" name="service_type_id"
                required>
                @foreach ($service_types as $service_type)
                    <option value="{{ $service_type->id }}"
                        {{ $service_type->id == $service->service_type_id ? 'selected' : '' }}>
                        {{ $service_type->name }}
                    </option>
                @endforeach

            </select>
        </div>

        <div class="col-3 mb-3">
            <label for="name" class="form-label is-required">{{ __('service.data.business_line') }}</label>
            <select class="form-select border-secondary border-opacity-50 " id="business_line" name="business_line_id"
                required>
                <option value="" selected disabled>Selecciona una opción</option>
                @foreach ($business_lines as $business_line)
                    <option value="{{ $business_line->id }}" @if ($business_line->id == $service->business_line_id) selected @endif>
                        {{ $business_line->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-3 mb-3">
            <div class="row">
                <div class="col-4 mb-1">
                    <label for="name" class="form-label">{{ __('service.data.time') }}</label>
                    <input type="number" class="form-control border-secondary border-opacity-50" id="time"
                        $pos_pest_cat=Array(); name="time" value="{{ $service->time }}" min="0"
                        max="59" placeholder="00" />
                </div>
                <div class="col-8 mb-1">
                    <label for="name" class="form-label">{{ __('service.data.time_unit') }}</label>
                    <select class="form-select border-secondary border-opacity-50 " id="time_unit" name="time_unit"
                        required>
                        @for ($i = 0; $i < count($time_types); $i++)
                            <option value="{{ $i + 1 }}"  @if ($i + 1 == $service->time_unit) selected @endif>
                                {{ $time_types[$i] }} </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <div class="col-2 mb-3">
            <label for="name" class="form-label is-required">{{ __('service.data.status') }}</label>
            <select class="form-select border-secondary border-opacity-50" id="status" name="status_id" required>
                @foreach ($service_status as $status)
                    <option value="{{ $status->id }}" {{$status->id == $service->status_id ? 'selected' : '' }} >
                        {{ $status->name }} </option>
                @endforeach
            </select>
        </div>

        <div class="row mb-3">
            <h5 class="fw-bold pb-1 border-bottom">{{ __('service.data.pests') }}</h5>
            <div class="form-text text-danger pb-1" id="basic-addon4">
                * Selecciona al menos 1 plaga.
            </div>
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
                                            <input class="pest form-check-input border-secondary" type="checkbox"
                                                value="{{ $pest->id }}" onchange="setPests()"
                                                {{ $service->hasPest($pest->id) ? 'checked' : '' }} />
                                            <label class="form-check-label" for="pest-{{ $pest->id }}">
                                                {{ $pest->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="fw-bold text-danger">
                                        No hay plagas asociadas.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <input type="hidden" id="pest-select" name="pestSelected" value="" />
        </div>

        <div class="row mb-3">
            <h5 class="fw-bold pb-1 border-bottom">{{ __('service.data.app_methods') }}</h5>
            <div class="form-text text-danger pb-1" id="basic-addon4">
                * Selecciona al menos 1 método de aplicación.
            </div>
            @foreach ($application_methods as $app_method)
                <div class="col-3">
                    <div class="form-check">
                        <input class="appMethod form-check-input" type="checkbox"
                            value="{{ $app_method->id }}" onchange="setAppMethods()"
                            {{ $service->hasAppMethods($app_method->id) ? 'checked' : '' }} />
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
            <textarea type="text" class="form-control border-secondary border-opacity-50 p-3" id="description"
                name="description" rows="7" placeholder="Incluye una descripción, directrices o prevenciones sobre el servicio."
                style="text-align: justify;">{{ $service->description }}</textarea>
        </div>


        <div class="col-auto mb-3">
            <label for="name" class="form-label is-required">Costo del servicio</label>
            <div class="input-group mb-0">
                <span class="input-group-text bg-success">$</span>
                <input type="number" class="form-control" id="cost" name="cost"
                    value="{{ $service->cost }}" min="0" placeholder="0" step="0.01" required />
            </div>
            <div class="form-text mb-2" id="basic-addon4">
                Costo del servicio a la empresa.
            </div>
        </div>
    </div>
    <button id="form_service_button" type="submit" class="btn btn-primary my-3">
        {{ __('buttons.update') }}
    </button>
</form>
