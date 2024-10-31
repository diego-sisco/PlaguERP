

<form method="POST" class="form" action="{{ route('warehouse.store')}}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-6 mb-3">
            <label for="name" class="form-label is-required">Nombre del almacen: </label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="name" name="name" required>
        </div>
        
        <div class="col-auto mb-3">
            <label for="receive_material" class="form-label is-required">Permitir recepciones de material: </label>
            <select class="form-select border-secondary border-opacity-25" id="receive-material" name="receive_material" required>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-4 mb-3">
            <label for="phone" class="form-label">{{ __('customer.data.phone') }}:</label>
            <input type="number" class="form-control border-secondary border-opacity-25" id="phone" name="phone" placeholder="0000000000" autocomplete="off" min="0">
        </div>
        <div class="col-6 mb-3">
            <label for="address" class="form-label">{{ __('customer.data.address') }}:</label>
            <input type="text" class="form-control border-secondary border-opacity-25" id="address" name="address" placeholder="#00 Col. Example">
        </div>
        <div class="col-2 mb-3">
            <label for="zip_code" class="form-label">{{ __('customer.data.zip_code') }}:</label>
            <input type="number" min=10000 max=99999 class="form-control border-secondary border-opacity-25" name="zip_code" placeholder="00000" id="zip_code">
        </div>
        <div class="col-3 mb-3">
            <label for="state" class="form-label">{{ __('customer.data.state') }}: </label>
            <select class="form-select border-secondary border-opacity-25" id="state" name="state" onchange="load_city()">
                <option value="" selected disabled hidden></option>
                @foreach ($states as $state)
                <option value="{{ $state['key'] }}">{{ $state['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-3">
            <label for="city" class="form-label">{{ __('customer.data.city') }}: </label>
            <select class="form-select border-secondary border-opacity-25" id="city" name="city" required>
                <option value="" selected disabled hidden>Selecciona un municipio</option>
            </select>
        </div>
        
        <div class="col-3 mb-3">
            <label for="branch" class="form-label is-required">{{ __('customer.data.branch') }}: </label>
            <select type="text" class="form-select border-secondary border-opacity-25 " name="branch_id" id="branch">
                @foreach ($branches as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <label for="observations">Observaciones: </label>
            <textarea type="text" class="form-control border-secondary border-opacity-25" id="observations" name="observations" rows="4"> </textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-primary my-3">
        {{ __('buttons.store') }}
    </button>
</form>

<script src="{{ asset('js/user/validations.min.js') }}"></script>
<script type="text/javascript">
    function load_city() {
        var select_state = document.getElementById("state");
        var select_city = document.getElementById("city");
        var state = select_state.value;

        // Borra las opciones actuales de select_city
        select_city.innerHTML = '<option value="" selected disabled hidden>Selecciona un municipio</option>';

        if (state !== "") {
            // Obtén los municipios correspondientes al estado seleccionado del JSON
            var cities = @json($cities);
            var stateCities = cities[state];

            if (stateCities) {
                stateCities.forEach(function(c) {
                    var option = document.createElement("option");
                    option.text = c;
                    option.value = c;
                    select_city.appendChild(option);
                });
            }
        }
    }

    function convertToUppercase(id) {
        var element = document.getElementById(id);
        element.value = element.value.toUpperCase();
    }
</script>
