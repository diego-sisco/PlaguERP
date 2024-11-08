@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    @php
        function isPDF($filePath)
        {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $extension = strtolower($extension);
            return $extension === 'pdf' || $extension == 'PDF';
        }
    @endphp

    <style>
        .sidebar {
            color: white;
            text-decoration: none
        }

        .sidebar:hover {
            background-color: #e9ecef;
            color: #212529;
        }
    </style>

    <div class="row w-100 justify-content-between m-0 h-100">
        <div class="col-1 m-0" style="background-color: #343a40;">
            <div class="row">
                <a href="{{ Route('branch.edit', ['id' => $branch->id, 'section' => 1]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Datos Generales
                </a>
                <a href="{{ Route('branch.edit', ['id' => $branch->id, 'section' => 2]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Datos de Contacto
                </a>
            </div>
        </div>

        <div class="col-11">
            <div class="row p-3 border-bottom">
                <a href="{{ route('branch.index') }}" class="col-auto btn-primary p-0 fs-3"><i
                        class="bi bi-arrow-left m-3"></i></a>
                <h1 class="col-auto fs-2 fw-bold m-0">
                    Editar delegación [{{ $branch->name }}]
                </h1>
            </div>
            <form method="POST" action="{{ route('branch.update', ['id' => $branch->id]) }}" class="form p-5 pt-3"
                enctype="multipart/form-data">
                @csrf
                @if ($section == 1)
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label for="name" class="form-label is-required">{{ __('modals.branch_data.name') }}:
                            </label>
                            <input type="text" class="form-control border-secondary border-opacity-25" id="name"
                                name="name" value="{{ $branch->name }}" required>
                        </div>
                        <div class="col-2 mb-3">
                            <label for="code" class="form-label">Código: </label>
                            <input type="number" class="form-control border-secondary border-opacity-25" id="code"
                                name="code" value="{{ $branch->code }}">
                        </div>

                        <div class="col-2 mb-3">
                            <label for="status" class="form-label">Status: </label>
                            <select class="form-select border-secondary border-opacity-25 " id="status" name="status_id">
                                @foreach ($status as $s)
                                    <option value="{{ $s->id }}" {{ $branch->status_id == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4 mb-3">
                            <label for="address" class="form-label is-required">{{ __('modals.branch_data.address') }}:
                            </label>
                            <input type="text" class="form-control border-secondary border-opacity-25" id="address"
                                name="address" placeholder="{{ __('modals.branch_data.address_specify') }}"
                                value="{{ $branch->address }}" required>
                        </div>
                        <div class="col-4 mb-3">
                            <label for="colony" class="form-label is-required">{{ __('modals.branch_data.colony') }}:
                            </label>
                            <input type="text" class="form-control border-secondary border-opacity-25" id="colony"
                                name="colony" value="{{ $branch->colony }}" required>
                        </div>
                        <div class="col-2 mb-3">
                            <label for="zip_code" class="form-label is-required">{{ __('modals.branch_data.zip_code') }}:
                            </label>
                            <input type="number" class="form-control border-secondary border-opacity-25" id="zip_code"
                                name="zip_code" value="{{ $branch->zip_code }}" required>
                        </div>
                        <div class="col-2 mb-3">
                            <label for="country" class="form-label">{{ __('modals.branch_data.country') }}: </label>
                            <select class="form-select border-secondary border-opacity-25  bg-secondary-subtle"
                                id="country" name="country" required>
                                <option value="Mex">México</option>
                            </select>
                        </div>

                        <div class="col-3 mb-3">
                            <label for="state" class="form-label is-required">{{ __('modals.branch_data.state') }}:
                            </label>
                            <select class="form-select border-secondary border-opacity-25 " id="state" name="state"
                                onchange="load_city()" required>
                                @foreach ($states as $state)
                                    @if ($state['key'] === $branch->state)
                                        <option value="{{ $state['key'] }}" selected>{{ $state['name'] }}</option>
                                    @else
                                        <option value="{{ $state['key'] }}">{{ $state['name'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 mb-3">
                            <label for="city" class="form-label is-required">{{ __('modals.branch_data.city') }}:
                            </label>
                            <select type="text" class="form-select border-secondary border-opacity-25 " id="city"
                                name="city" required>
                                @foreach ($states as $state)
                                    @if ($state['key'] == $branch->state)
                                        @foreach ($cities[$state['key']] as $city)
                                            @if ($city == $branch->city)
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
                        <div class="col-6 mb-3">
                            <label for="license_number" class="form-label fw-bold is-required">NO. de licencia sanitaria
                                (COFEPRIS): </label>
                            <input type="text" class="form-control border-secondary border-opacity-25"
                                id="license_number" name="license_number" value="{{ $branch->license_number }}"
                                required>
                        </div>
                    </div>
                @endif

                @if ($section == 2)
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label for="email" class="form-label">{{ __('modals.branch_data.email') }}: </label>
                            <input type="email" class="form-control border-secondary border-opacity-25" id="email"
                                name="email" value="{{ $branch->email }}">
                        </div>
                        <div class="col-4 mb-3">
                            <label for="email" class="form-label ">Correo alternativo: </label>
                            <input type="email" class="form-control border-secondary border-opacity-25" id="alt-email"
                                name="alt_email" value="{{ $branch->alt_email }}">
                        </div>
                        <div class="col-3 mb-3">
                            <label for="phone" class="form-label">{{ __('modals.branch_data.phone') }}: </label>
                            <input type="number" class="form-control border-secondary border-opacity-25" id="phone"
                                name="phone" value="{{ $branch->phone }}">
                        </div>
                        <div class="col-3 mb-3">
                            <label for="alt_phone" class="form-label">Teléfono alternativo: </label>
                            <input type="number" class="form-control border-secondary border-opacity-25" id="alt_phone"
                                name="alt_phone" value="{{ $branch->alt_phone }}">
                        </div>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary my-3"> {{ __('buttons.store') }} </button>
            </form>
        </div>
    </div>

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


        function convertToUppercase(id) {
            $("#" + id).val($("#" + id).val().toUpperCase());
        }
    </script>

@endsection
