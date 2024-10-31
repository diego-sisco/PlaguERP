<form method="POST" action="{{ route('customer.store', ['id' => $customer->id ?? 0, 'type' => $type]) }}"
    enctype="multipart/form-data">
    @csrf

    <div class="row mb-3">
        <div class="col-4">
            <label for="name" class="form-label is-required"> {{ __('customer.data.name') }}: </label>
            <input type="text" class="form-control border-secondary border-opacity-25 " id="name" name="name"
                placeholder="Example" required>
        </div>
        <div class="col-4">
            <label for="email" class="form-label">{{ __('customer.data.email') }}: </label>
            <input type="email" class="form-control border-secondary border-opacity-25" id="email" name="email"
                placeholder="example@mail.com" autocomplete="off">
        </div>
        <div class="col-4">
            <label for="phone" class="form-label is-required">{{ __('customer.data.phone') }}</label>
            <input type="number" min=1 class="form-control border-secondary border-opacity-25" id="phone"
                placeholder="0000000000" name="phone" autocomplete="off" min="0" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <label for="address" class="form-label is-required">{{ __('customer.data.address') }}</label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="address" name="address"
                placeholder="#00 Col. Example" required>
        </div>
        <div class="col-2">
            <label for="zip_code" class="form-label is-required">{{ __('customer.data.zip_code') }} :</label>
            <input type="number" min=10000 max=99999 class="form-control border-secondary border-opacity-25"
                name="zip_code" placeholder="00000" id="zip_code" required>
        </div>
        <div class="col-2">
            <label for="state" class="form-label is-required">{{ __('customer.data.state') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " id="state" name="state"
                onchange="load_city()" required>
                <option value="" selected disabled hidden></option>
                @foreach ($states as $state)
                    <option value="{{ $state['key'] }}">{{ $state['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <label for="city" class="form-label is-required">{{ __('customer.data.city') }}: </label>
            <select type="text" class="form-select border-secondary border-opacity-25 " id="city" name="city"
                required>
                @foreach ($states as $state)
                    @foreach ($cities[$state['key']] as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-3">
            <label for="serv" class="form-label is-required">{{ __('customer.data.type') }} :</label>
            <select type="text" class="form-select border-secondary border-opacity-25 " id="service-type"
                name="service_type_id" required>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" {{ !empty($customer) && $customer->service_type_id == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-3" id="company-type">
            <label for="type_com" id="lab"
                class="form-label is-required">{{ __('customer.data.category') }}:</label>
            <select type="text" class="form-select border-secondary border-opacity-25 " id="company_category_id"
                name="company_category_id">
                @foreach ($categs as $cat)
                    <option value="{{ $cat->id }}" {{ !empty($customer) && $customer->company_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->category }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label for="branch" class="form-label is-required">{{ __('customer.data.branch') }}:</label>
            <select type="text" class="form-select border-secondary border-opacity-25 " name="branch_id"
                id="branch">
                @foreach ($branch as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-3">
        @if ($type == 0)
            <div class="col-12 mb-3">
                <label for="url" class="form-label">{{ __('customer.data.reason') }}:</label>
                <textarea class="form-control" placeholder="Describe el motivo/razón por la cual llamaron..." id="reason"
                    rows="3" name="reason"></textarea>
            </div>
        @endif
        <div class="col-12">
            <label for="url" class="form-label">{{ __('customer.data.url_map') }}:</label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="map_location_url"
                name="map_location_url" placeholder="https://www.google.com/maps?q=latitude,longitude&hl=en" />
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">
        {{ __('buttons.store') }}
    </button>

</form>

<script type="text/javascript">
    $(document).ready(function() {
        muestra();
        $("#service-type").change(function() {
            muestra();
        });
    });

    function muestra() {
        if ($("#service-type").val() == 1) {
            $("#company-type").hide();
        } else {
            $("#company-type").show();
        }
    }

    function load_city() {
        var select_state = document.getElementById("state");
        var select_city = document.getElementById("city");
        var state = select_state.value;
        // Borra las opciones actuales de select_city
        select_city.innerHTML =
            '<option value="" selected disabled hidden>Selecciona un municipio</option>';
        if (state != "") {
            // Obtén los municipios correspondientes al city seleccionado del JSON
            var cities = {!! json_encode($cities) !!}
            var city = cities[state];
            city.forEach(function(c) {
                var option = document.createElement("option");
                option.text = c;
                option.value = c;
                select_city.appendChild(option);
            });
        }
    }

    function convertToUppercase(id) {
        $("#" + id).val($("#" + id).val().toUpperCase());
    }
</script>
