<!-- Modal -->
<form method="POST" action="{{ route('branch.store') }}" class="form p-5 pt-3" enctype="multipart/form-data">
    @csrf
    <div class="row mb-3">
        <div class="col-4 mb-2">
            <label for="name" class="form-label is-required">{{ __('modals.branch_data.name') }}: </label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="name" name="name"
                required>
        </div>
        <div class="col-4 mb-2">
            <label for="email" class="form-label">{{ __('modals.branch_data.email') }}: </label>
            <input type="email" class="form-control border-secondary border-opacity-25" id="email" name="email">
        </div>
        <div class="col-4 mb-2">
            <label for="email" class="form-label ">Correo alternativo: </label>
            <input type="email" class="form-control border-secondary border-opacity-25" id="alt-email"
                name="alt_email">
        </div>
        <div class="col-3 mb-2">
            <label for="phone" class="form-label">{{ __('modals.branch_data.phone') }}: </label>
            <input type="number" class="form-control border-secondary border-opacity-25" id="phone" name="phone">
        </div>
        <div class="col-3 mb-2">
            <label for="alt_phone" class="form-label">Teléfono alternativo: </label>
            <input type="number" class="form-control border-secondary border-opacity-25" id="alt_phone"
                name="alt_phone">
        </div>
        <div class="col-2 mb-2">
            <label for="code" class="form-label">Código: </label>
            <input type="number" class="form-control border-secondary border-opacity-25" id="code" name="code">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-4 mb-2">
            <label for="address" class="form-label is-required">{{ __('modals.branch_data.address') }}: </label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="address" name="address"
                placeholder="{{ __('modals.branch_data.address_specify') }}" required>
        </div>
        <div class="col-4 mb-2">
            <label for="colony" class="form-label is-required">{{ __('modals.branch_data.colony') }}: </label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="colony" name="colony"
                required>
            <div class="form-text" id="basic-addon4">Ingresa la colonia donde se encuentra la sucursal.</div>
        </div>
        <div class="col-2 mb-2">
            <label for="zip_code" class="form-label is-required">{{ __('modals.branch_data.zip_code') }}: </label>
            <input type="number" class="form-control border-secondary border-opacity-25" id="zip_code" name="zip_code"
                required>
        </div>
        <div class="col-2 mb-2">
            <label for="country" class="form-label">{{ __('modals.branch_data.country') }}: </label>
            <select class="form-select border-secondary border-opacity-25  bg-secondary-subtle" id="country" name="country" required>
                <option value="Mex">México</option>
            </select>
        </div>

        <div class="col-3 mb-2">
            <label for="state" class="form-label is-required">{{ __('modals.branch_data.state') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " id="state" name="state"
                onchange="load_city()" required>
                <option value="" selected disabled hidden>{{ __('modals.branch_data.state_select') }}</option>
                @foreach ($states as $state)
                    <option value="{{ $state['key'] }}">{{ $state['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-2">
            <label for="city" class="form-label is-required">{{ __('modals.branch_data.city') }}: </label>
            <select type="text" class="form-select border-secondary border-opacity-25 " id="city" name="city"
                required>
            </select>
            <div class="form-text" id="basic-addon4">Selecciona primero el <b>Estado</b> y después la ciudad.</div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6 mb-2">
            <label for="license_number" class="form-label fw-bold is-required">NO. de licencia sanitaria (COFEPRIS): </label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="license_number"
                name="license_number" required>
            <div class="form-text" id="basic-addon4">Ingresa el no de licencia de la sucursal.</div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary"> Crear </button>
</form>

<script type="text/javascript">
    function load_city() {
        var select_state = document.getElementById("state");
        var select_city = document.getElementById("city");
        var state = select_state.value;
        // Borra las opciones actuales de select_city
        select_city.innerHTML =
            '<option value="" selected disabled hidden>Selecciona un municipio</option>';
        if (state != "") {
            // Obtén los municipios correspondientes al city seleccionado del JSON
            var cities = {!! json_encode($cities) !!};
            var city = cities[state];
            city.forEach(function(c) {
                var option = document.createElement("option");
                option.text = c;
                option.value = c;
                select_city.appendChild(option);
            });
        }
    }
</script>

<script src="js/actions/forms/create_branch.min.js"></script>