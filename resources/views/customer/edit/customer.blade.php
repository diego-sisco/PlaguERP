<div class="row">
    <div class="col-4 mb-3">
        <label for="name" class="form-label is-required"
            >{{ __('customer.data.name') }}:
        </label>
        <input
            type="text"
            class="form-control border-secondary border-opacity-50"
            id="name"
            name="name"
            value="{{ $customer->name }}"
            required
        />
    </div>
    <div class="col-4 mb-3">
        <label for="email" class="form-label is-required"
            >{{ __('customer.data.email') }}:
        </label>
        <input
            type="text"
            class="form-control border-secondary border-opacity-50"
            id="email"
            name="email"
            value="{{ $customer->email }}"
        />
    </div>
    <div class="col-2 mb-3">
        <label for="phone" class="form-label is-required"
            >{{ __('customer.data.phone') }}:
        </label>
        <input
            type="number"
            min="1"
            class="form-control border-secondary border-opacity-50"
            id="phone"
            name="phone"
            value="{{ $customer->phone }}"
        />
    </div>
</div>
<div class="row">
    <div class="col-2 mb-3">
        <label for="status" class="form-label">
            {{ __('customer.data.status') }}:
        </label>
        <select
            class="form-select border-{{ $customer->status == 1 ? 'success' : 'danger' }}"
            name="status"
            id="status"
        >
            <option value="1" {{ $customer->
                status == 1 ? 'selected' : '' }}>Activo
            </option>
            <option value="0" {{ $customer->
                status == 0 ? 'selected' : '' }}>Inactivo
            </option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-6 mb-3">
        <label for="add" class="form-label is-required"
            >{{ __('customer.data.address') }}:
        </label>
        <input
            type="text"
            class="form-control border-secondary border-opacity-50"
            id="address"
            name="address"
            value="{{ $customer->address }}"
            required
        />
    </div>
    <div class="col-2 mb-3">
        <label for="zip_code" class="form-label is-required"
            >{{ __('customer.data.zip_code') }}:
        </label>
        <input
            type="number"
            class="form-control border-secondary border-opacity-50"
            id="zip_code"
            name="zip_code"
            value="{{ $customer->zip_code }}"
            required
        />
    </div>
    <div class="col-2 mb-3">
        <label for="state" class="form-label is-required"
            >{{ __('customer.data.state') }}:
        </label>
        <select
            class="form-select border-secondary border-opacity-50"
            id="state"
            name="state"
            onchange="load_city()"
            required
        >
            <option value="" selected disabled hidden>
                {{ __('modals.branch_data.state_select') }}
            </option>
            @foreach ($states as $state) @if ($state['key'] == $customer->state)
            <option value="{{ $state['key'] }}" selected>
                {{ $state['name'] }}
            </option>
            @else
            <option value="{{ $state['key'] }}">{{ $state['name'] }}</option>
            @endif @endforeach
        </select>
    </div>
    <div class="col-2 mb-3">
        <label for="city" class="form-label is-required"
            >{{ __('customer.data.city') }}:
        </label>
        <select
            class="form-select border-secondary border-opacity-50"
            id="city"
            name="city"
            required
        >
            @foreach ($states as $state) @if ($state['key'] == $customer->state)
            @foreach ($cities[$state['key']] as $city)
            <option value="{{ $city }}" {{ $city == $customer->city ? 'selected' : '' }}> {{ $city }}
            </option>
            @endforeach @endif @endforeach
        </select>
    </div>

    @if ($type == 0)
    <div class="col-12 mb-3">
        <label for="url" class="form-label"
            >{{ __('customer.data.reason') }}:</label
        >
        <textarea
            class="form-control"
            placeholder="Describe el motivo/razón por la cual llamaron..."
            id="reason"
            rows="3"
            name="reason"
        >
{{$customer->reason}}</textarea
        >
    </div>
    @endif
    <div class="col-12 mb-3">
        <label for="url" class="form-label"
            >{{ __('customer.data.url_map') }}:</label
        >
        <input
            type="text"
            class="form-control border-secondary border-opacity-50"
            id="url"
            name="map_location_url"
            value="{{ $customer->map_location_url }}"
        />
    </div>
</div>
<!-- Categoria -->
<div class="row">
    <div class="col-4 mb-3">
        <label for="servicetype" class="form-label is-required"
            >Tipo de servicio:</label
        >
        <select
            class="form-select border-secondary border-opacity-50"
            id="servicetype"
            name="service_type_id"
        >
            @foreach ($serviceTypes as $serv)
            <option value="{{ $serv->id }}" {{ $serv->
                id == $customer->service_type_id ? 'selected' : '' }}> {{
                $serv->name }}
            </option>
            @endforeach
        </select>
    </div>

    @if ($customer->service_type_id == 3)
    <div class="col-4 mb-3">
        <label for="category" class="form-label"
            >{{ __('customer.data.category') }}:</label
        >
        <select
            class="form-select border-secondary border-opacity-50"
            id="category"
            name="company_category_id"
        >
            @foreach ($categs as $cate)
            <option value="{{ $cate->id }}" {{ $cate->
                id == $customer->company_category_id ? 'selected' : '' }}> {{
                $cate->category }}
            </option>
            @endforeach
        </select>
    </div>
    @endif
</div>
<!-- Horarios -->
<div class="row">
    <div class="col-2 mb-3">
        <label for="stime" class="form-label"
            >{{ __('customer.data.meeting_interval_start') }}:
        </label>
        <input
            type="time"
            min="08:00"
            max="19:00"
            class="form-control border-secondary border-opacity-50"
            id="start_time"
            name="start_time"
            value="{{ $customer->start_time }}"
        />
    </div>
    <div class="col-2 mb-3">
        <label for="ftime" class="form-label"
            >{{ __('customer.data.meeting_interval_end') }}:
        </label>
        <input
            type="time"
            min="08:00"
            max="19:00"
            class="form-control border-secondary border-opacity-50"
            id="end_time"
            name="end_time"
            value="{{ $customer->end_time }}"
        />
    </div>
</div>
<!-- Información fiscal -->
<div class="row">
    <div class="col-4 mb-3">
        <label for="buss" class="form-label"
            >Nombre fiscal de la empresa:
        </label>
        <input
            type="text"
            class="form-control border-secondary border-opacity-50"
            id="tax-name"
            name="tax_name"
            value="{{ $customer->tax_name }}"
        />
    </div>
    <div class="col-4 mb-3">
        <label for="rfc" class="form-label"
            >{{ __('customer.data.rfc') }}:
        </label>
        <input
            type="text"
            class="form-control border-secondary border-opacity-50"
            id="rfc"
            name="rfc"
            value="{{ $customer->rfc }}"
        />
    </div>
    <div class="col-4 mb-3">
        <label for="tax" class="form-label"
            >{{ __('customer.data.tax') }}:
        </label>
        <select
            class="form-select border-secondary border-opacity-50"
            id="tax-regime"
            name="tax_regime"
        >
            @foreach ($tax_regimes as $item) @if ($item->id ==
            $customer->tax_regime_id)
            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
            @else
            <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endif @endforeach
        </select>
    </div>
</div>
