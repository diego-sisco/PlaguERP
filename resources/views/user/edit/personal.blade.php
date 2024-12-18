<form class="form" method="POST" action="{{ route('user.update', ['id' => $user->id]) }}" enctype="multipart/form-data"
    onsubmit="return submitForm()">
    @csrf

    <input type="hidden" name="url_customer" id="url-customer" value="{{ route('order.search.customer') }}">

    <div class="row mb-3">
        <div class="col-5">
            <label for="name" class="form-label is-required">{{ __('user.data.name') }}: </label>
            <input type="text" class="form-control border-secondary border-opacity-50" id="name" name="name"
                value="{{ $user->name }}" autocomplete="off" maxlength="50" required>
        </div>
        <div class="col-5">
            <label for="email" class="form-label is-required">{{ __('user.data.email') }}: </label>
            <input type="email" class="form-control border-secondary border-opacity-50" id="email" name="email"
                value="{{ $user->email }}" autocomplete="off" maxlength="50" required>
        </div>
        <div class="col-2">
            <label for="password" class="form-label is-required">{{ __('user.data.password') }}: </label>
            <input type="text" class="form-control border-secondary border-opacity-50" id="password" name="password"
                value="{{ $user->nickname }}" autocomplete="off" maxlength="50" required>
        </div>
    </div>
    @if ($user->type_id == 1)
        <div class="row mb-3">
            <div class="col-2">
                <label for="birthdate" class="form-label is-required">{{ __('user.data.birthdate') }}: </label>
                <input type="date" class="form-control border-secondary border-opacity-50" id="birthdate"
                    name="birthdate" value="{{ !empty($user->roleData->birthdate) ? $user->roleData->birthdate : '' }}"
                    required>
            </div>
            <div class="col-2">
                <label for="phone" class="form-label">{{ __('user.data.phone') }}: </label>
                <input type="number" class="form-control border-secondary border-opacity-50" id="phone"
                    name="phone" value="{{ !empty($user->roleData->phone) ? $user->roleData->phone : '' }}"
                    autocomplete="off" maxlength="10" placeholder="0000000000">
            </div>

            <div class="col-2">
                <label for="company_phone" class="form-label">{{ __('user.data.company_phone') }}: </label>
                <input type="number" class="form-control border-secondary border-opacity-50" id="company_phone"
                    name="company_phone" autocomplete="off" maxlength="10"
                    value="{{ !empty($user->roleData->company_phone) ? $user->roleData->company_phone : '' }}"
                    placeholder="0000000000">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <label for="address" class="form-label is-required">{{ __('user.data.address') }}: </label>
                <input type="text" class="form-control border-secondary border-opacity-50" id="address"
                    name="address" placeholder="#00 Example"
                    value="{{ !empty($user->roleData->address) ? $user->roleData->address : '' }}" autocomplete="off"
                    required>
            </div>
            <div class="col-4 mb-3">
                <label for="colony" class="form-label is-required">{{ __('user.data.colony') }}: </label>
                <input type="text" class="form-control border-secondary border-opacity-50" id="colony"
                    name="colony" value="{{ !empty($user->roleData->colony) ? $user->roleData->colony : '' }}"
                    placeholder="Example" required>
            </div>
            <div class="col-2 mb-3">
                <label for="zip_code" class="form-label is-required">{{ __('user.data.zip_code') }}: </label>
                <input type="number" class="form-control border-secondary border-opacity-50" id="zip_code"
                    name="zip_code" min=10000 placeholder="00000"
                    value="{{ !empty($user->roleData->zip_code) ? $user->roleData->zip_code : '' }}" required>
            </div>
            <div class="col-2 mb-3">
                <label for="country" class="form-label is-required">{{ __('user.data.country') }}: </label>
                <input type="text" class="form-control border-secondary border-opacity-50 " value="México"
                    disabled>
                <input type="hidden" id="country" name="country" value="México" required>
            </div>
            <div class="col-3 mb-3">
                <label for="state" class="form-label is-required">{{ __('user.data.state') }}: </label>
                <select class="form-select border-secondary border-opacity-50 " id="state" name="state"
                    onchange="load_city()" required>
                    <option value="" selected disabled hidden>{{ __('user.data.state') }}</option>
                    @foreach ($states as $state)
                        @if (!is_null($user->roleData) && isset($user->roleData->state) && $state['key'] == $user->roleData->state)
                            <option value="{{ $state['key'] }}" selected>{{ $state['name'] }}</option>
                        @else
                            <option value="{{ $state['key'] }}">{{ $state['name'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-3 mb-3">
                <label for="city" class="form-label is-required">{{ __('user.data.city') }}: </label>
                <select type="text" class="form-select border-secondary border-opacity-50 " id="city"
                    name="city" required>
                    <option value="" selected disabled hidden>{{ __('user.data.city') }}</option>
                    @foreach ($states as $state)
                        @if (!is_null($user->roleData) && isset($user->roleData->state) && $state['key'] == $user->roleData->state)
                            @foreach ($cities[$state['key']] as $city)
                                @if (!is_null($user->roleData->city) && $city == $user->roleData->city)
                                    <option value="{{ $city }}" selected>{{ $city }}</option>
                                @else
                                    <option value="{{ $city }}">{{ $city }}</option>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4 mb-3">
                <label for="curp" class="form-label is-required">{{ __('user.data.curp') }}: </label>
                <input type="text" class="form-control border-secondary border-opacity-50" id="curp"
                    name="curp" value="{{ !empty($user->roleData->curp) ? $user->roleData->curp : '' }}"
                    autocomplete="off" minlength="18" maxlength="18" placeholder="ABCD010203HDFGHI01"
                    oninput="this.value = this.value.toUpperCase()" required />
            </div>
            <div class="col-4 mb-3">
                <label for="nss" class="form-label is-required">{{ __('user.data.nss') }}: </label>
                <input type="number" class="form-control border-secondary border-opacity-50" id="nss"
                    name="nss" value="{{ !empty($user->roleData->nss) ? $user->roleData->nss : '' }}"
                    min="0" autocomplete="off" placeholder="12345678900"
                    oninput="this.value = this.value.toUpperCase()" required>
            </div>
            <div class="col-4 mb-3">
                <label for="rfc" class="form-label is-required">{{ __('user.data.rfc') }}: </label>
                <input type="text" class="form-control border-secondary border-opacity-50" id="rfc"
                    name="rfc" value="{{ !empty($user->roleData->rfc) ? $user->roleData->rfc : '' }}"
                    autocomplete="off" maxlength="13" placeholder="ABCD010203XYZ"
                    oninput="this.value = this.value.toUpperCase()" required>
            </div>
        </div>
    @else
        <div class="row mb-3">
            <div class="col-12">
                <label for="accordionDirectory"
                    class="form-label fw-bold is-required">{{ __('user.title.clients') }}: </label>
                    <ul class="list-group">
                        @foreach ($clients as $client)
                            <li class="list-group-item">
                                <div class="form-check">
                                    <input class="form-check-input customer" type="checkbox" value="{{ $client->id }}" onchange="setClientId(this.value)" {{ $user->hasCustomer($client->id) ? 'checked' : ''}} >
                                    <label class="form-check-label" for="">
                                        {{ $client->name }}
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="accordionDirectory"
                    class="form-label fw-bold is-required">{{ __('user.title.permissions') }}: </label>
                <ul class="list-group" id="tree-dir">
                    @foreach ($directories as $i => $directory)
                    @endforeach
                </ul>
            </div>
        </div>

        <input type="hidden" name="directories" id="directories" value="">
        <input type="hidden" id="customers" name="customers" value="">

        <script>
            const directories = @json($directories);
            const paths = @json($user->directories->pluck('path')->toArray());
            const type = @json($user->type_id);
            const new_client_account = false;
        </script>
    @endif
    <button type="submit" id="personal-form-btn" class="btn btn-primary mt-3">{{ __('buttons.update') }}</button>
</form>


<script type="text/javascript">
    function load_city() {
        var select_state = $("#state");
        var select_city = $("#city");
        var state = select_state.val();

        select_city.html('<option value="" selected disabled hidden>Selecciona un municipio</option>');

        if (state !== "") {
            var cities = @json($cities);
            var cityOptions = cities[state].map(city => `<option value="${city}">${city}</option>`);

            select_city.append(cityOptions.join(''));
        }
    }
</script>
