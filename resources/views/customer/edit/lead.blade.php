<form method="POST" action="{{ route('customer.update', ['id' => $client->id, 'type' => 1]) }}"
    enctype="multipart/form-data">
    @csrf
    <!-- FORMULARIO DE DATOS BASICOS PARA LEAD CUSTOMER-->
    <input type="hidden" name="admin_id" id="admin_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="lead_to_customer_id" id="lead-to-customer" value="">
    <input type="hidden" name="customer_url" id="customer_url" value="{{ route('customer.autocomplete') }}">
    <input type="hidden" name="status" id="status" value="{{$client->status}}">

    <div class="row mb-2">
        <div class="col-4">
            <label for="name" class="form-label is-required">{{ __('customer.customer_table.name') }}</label>
            <input type="text" class="form-control border-secondary border-opacity-50 " id="name" name="name"
                value="{{ $client->name }}" onblur="get_lead(this.value, this.id, {{ $customer_type }})" required>
            <div class="form-text" id="basic-addon4">Ingresa el nombre del cliente</div>
        </div>
        <div class="col-4">
            <label for="correo" class="form-label is-required">{{ __('customer.customer_table.correo') }}</label>
            <input type="email" class="form-control border-secondary border-opacity-50" id="email" name="email"
                value="{{ $client->email }}" placeholder="example@mail.com" autocomplete="off"
                onblur="get_lead(this.value, this.id, {{ $customer_type }})">
            <div class="form-text" id="basic-addon4">Ingresa el correo del cliente</div>
        </div>
        <div class="col-4">
            <label for="tel" class="form-label is-required">{{ __('customer.customer_table.phone') }}</label>
            <input type="number" min=1000000000 max=9999999999 class="form-control border-secondary border-opacity-50" id="phone" name="phone"
                value="{{ $client->phone }}" autocomplete="off"
                onblur="get_lead(this.value, this.id, {{ $customer_type }})">
            <div class="form-text" id="basic-addon4">Ingresa el telefono del cliente</div>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-6">
            <label for="addr" class="form-label is-required">{{ __('customer.customer_table.address') }}</label>
            <input type="text" class="form-control border-secondary border-opacity-50" id="address" value="{{ $client->address }}"
                name="address" onblur="get_lead(this.value, this.id, {{ $customer_type }})" required>
            <div class="form-text" id="basic-addon4">Ingresa la direccion del cliente</div>
        </div>
        <div class="col-2">
            <label for="zip_code" class="form-label is-required">{{ __('customer.customer_table.zipcode') }} :</label>
            <input type="number" min=10000 max=99999 class="form-control border-secondary border-opacity-50" name="zip_code"
                id="zip-code" value="{{ $client->zip_code }}" required>
            <div class="form-text" id="basic-addon4">Ingresa el codigo postal del cliente</div>
        </div>

        <div class="col-2">
            <label for="state" class="form-label is-required">{{ __('customer.customer_table.state') }}: </label>
            <select class="form-select border-secondary border-opacity-50 " id="state" name="state" onchange="load_city()" required>
                <option value="" selected disabled hidden>{{ __('modals.branch_data.state_select') }}</option>
                @foreach ($states as $state)
                    @if ($state['key'] == $client->state)
                        <option value="{{ $state['key'] }}" selected>{{ $state['name'] }}</option>
                    @else
                        <option value="{{ $state['key'] }}">{{ $state['name'] }}</option>
                    @endif
                @endforeach
            </select>
            <div class="form-text" id="basic-addon4">{{ __('modals.branch_data.state_select') }}</div>
        </div>
        <div class="col-2">
            <label for="city" class="form-label is-required">{{ __('customer.customer_table.city') }}: </label>
            <select type="text" class="form-select border-secondary border-opacity-50 " id="city" name="city" required>
                @foreach ($states as $state)
                    @if ($state['key'] == $client->state)
                        @foreach ($cities[$state['key']] as $city)
                            @if ($city == $client->city)
                                <option value="{{ $city }}" selected>{{ $city }}</option>
                            @else
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </select>
            <div class="form-text" id="basic-addon4">Selecciona primero el <b>Estado</b> y después la ciudad.</div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-3">
            <label for="serv" class="form-label is-required">{{ __('customer.customer_table.serv') }} :</label>
            <select type="text" class="form-select border-secondary border-opacity-50 " id="service-type" name="service_type"
                onchange="muestra()" required>
                @foreach ($services as $serv)
                    @if ($client->service_type_id == $serv->id)
                        <option value="{{ $serv->id }}" selected> {{ $serv->name }} </option>
                    @else
                        <option value="{{ $serv->id }}"> {{ $serv->name }} </option>
                    @endif
                @endforeach
            </select>
            <div class="form-text" id="basic-addon4">Ingrese el tipo de cliente </div>
        </div>
        <div class="col-3" id="company-type">
            <label for="type_com" id="lab"
                class="form-label is-required">{{ __('customer.customer_table.categs') }}: </label>
            <select type="text" class="form-select border-secondary border-opacity-50 " id="category"
                name="category">
                @foreach ($categs as $cate)
                    <option 
                        value="{{ $cate->id }}"
                        @if($cate->id == $client->company_category_id)
                            selected
                        @endif
                    >
                        {{ $cate->category }}
                    </option>
                @endforeach
            </select>
            <div class="form-text" id="basic-addon4"></div>
        </div>

        <div class="col-12 mb-3">
            <label for="url" class="form-label">{{ __('customer.data.url_map') }}:</label>
            <input type="text" class="form-control border-secondary border-opacity-50" id="url" name="url"
                value="{{ $client->map_location_url }}" />
        </div>
    </div>
    <div class="row">
        <div class="mb-0">
            <button type="submit" class="btn btn-primary">
                {{ __('buttons.edit') }}
            </button>
        </div>
    </div>
</form>

@include('customer.modals.leadtocustomer')
