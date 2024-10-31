<form id="contract-form" class="form" method="POST" action="{{ route('contract.store') }}" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="url_customer" id="url-customer" value="{{ route('order.search.customer') }}" />
    <input type="hidden" name="url_service_filter" id="url-service-filter"
        value="{{ route('order.search.service', ['type' => 0]) }}" />
    <input type="hidden" name="url_service_input" id="url-service-input"
        value="{{ route('order.search.service', ['type' => 1]) }}" />
    <input type="hidden" name="url_selected_service" id="url-selected-service"
        value="{{ route('ajax.contract.service') }}" />

    <div id="service-contract-form" class="row">
        <div class="row mb-3">
            <h5 class="border-bottom pb-1 fw-bold">
                {{ __('contract.title.set_customer') }}:
            </h5>
            <label for="client" class="form-label m-0">Busca el cliente a traves de los siguientes campos:
            </label>
            <div class="form-text text-danger m-0" id="basic-addon4">
                * Selecciona al menos 1 servicio.
            </div>
            <div class="form-text text-danger m-0" id="basic-addon4">
                * En caso de que no aparezca deberas crearlo.
            </div>
            <div class="col-12 p-0 m-0">
                <a href="{{ route('customer.create', ['id' => 0, 'type' => 1]) }}" id="form_service_button"
                    class="btn btn-link" target="_blank">
                    {{ __('customer.title.create') }}
                </a>
            </div>
            <div class="col-3">
                <label for="client" class="form-label">Nombre: </label>
                <input class="form-control border-secondary border-opacity-25" name="customer_name" id="customer-name"
                    placeholder="Example" value="" />
            </div>
            <div class="col-3">
                <label for="client" class="form-label">Teléfono: </label>
                <input class="form-control border-secondary border-opacity-25" name="customer_phone" id="customer-phone"
                    placeholder="0000000000" value="" />
            </div>
            <div class="col-6">
                <label for="client" class="form-label">Dirección: </label>
                <input class="form-control border-secondary border-opacity-25" name="customer_address"
                    id="customer-address" placeholder="Example #00, Col. Example" value="" />
            </div>
            <div class="col-3 mt-2 mb-3">
                <button id="form_service_button" type="button" class="btn btn-primary btn-sm"
                    onclick="searchCustomer()">
                    {{ __('buttons.search') }}
                </button>
            </div>

            @include('order.modals.customers')

            <div class="col-12" id="customer-select"></div>
        </div>

        <div class="row mb-3" id="select-contract">
            <h5 class="border-bottom pb-1 fw-bold">
                {{ __('contract.title.associate_contract') }}:
            </h5>
            <div class="form-text text-danger m-0 mb-1" id="basic-addon4">
                * Si deseas vincular las órdenes a un contrato que ya ha sido creado, selecciónalo despues de buscar al
                cliente.
            </div>
            <div class="col-3 mb-3">
                <select class="form-select" id="contract" name="contract_id">
                    <option value="0" selected>Sin contrato</option>
                </select>
            </div>
        </div>

        <div class="row mb-3" id="duration">
            <h5 class="border-bottom pb-1 fw-bold">
                {{ __('contract.title.duration') }}:
            </h5>
            <div class="form-text text-danger m-0 mb-1" id="basic-addon4">
                * En caso de haber seleccionado un contrato, se toman como intervalo de fechas para las ordenes.
            </div>
            <div class="col-3 mb-3">
                <label for="client" class="form-label is-required">
                    {{ __('contract.data.start_date') }}:
                </label>
                <input type="date" class="form-control border-secondary border-opacity-25" name="startdate"
                    id="startdate" oninput="set_endDate()" required />
            </div>

            <div class="col-3 mb-3">
                <label for="client" class="form-label">
                    {{ __('contract.data.end_date') }}:
                </label>
                <input type="date" class="form-control border-secondary border-opacity-25" name="enddate"
                    id="enddate" />
            </div>
        </div>

        <div class="row mb-3">
            <h5 class="border-bottom pb-1 mb-1 fw-bold">
                {{ __('contract.title.set_services') }}:
            </h5>
            <div class="form-text text-danger m-0" id="basic-addon4">
                * Selecciona al menos 1 servicio.
            </div>
            <div class="form-text text-danger m-0" id="basic-addon4">
                * En caso de que no aparezca deberas crearlo.
            </div>
            <div class="form-text text-danger m-0" id="basic-addon4">
                * Para añadir días, solo se permite la primera letra en
                mayúscula: (L) Lunes, (M) Martes, (Mi) Miércoles, (J) Jueves,
                (V) Viernes, (S) Sábado, (D) Domingo.
            </div>
            <div class="col-12 p-0 m-0 mb-1">
                <a href="{{ route('service.create') }}" id="form_service_button" class="btn btn-link"
                    target="_blank">
                    {{ __('service.button.create') }}
                </a>
            </div>

            <div class="col-12">
                <h6 class="pb-1 mb-1 fw-bold">
                    {{ __('contract.title.find_service') }}:
                </h6>
                <div class="input-group mb-3">
                    <input type="search" class="form-control border-secondary border-opacity-25"
                        id="search-service-input" name="search_service_input" placeholder="Nombre del servicio" />
                    <button class="btn btn-primary btn-sm" type="button" id="btn-search-service"
                        onclick="searchService()">
                        <i class="bi bi-search"></i> {{ __('buttons.search') }}
                    </button>
                </div>
            </div>

            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="col-1 text-center" scope="col">#</th>
                            <th class="col-2 text-center" scope="col">
                                Nombre
                            </th>
                            <th class="col-2 text-center" scope="col">
                                Frecuencia
                            </th>
                            <th class="col-2 text-center" scope="col">
                                Invervalo
                            </th>
                            <th class="col-3 text-center" scope="col">Días</th>
                            <th class="col-2 text-center" scope="col">
                                {{ __('buttons.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody id="selected-services"></tbody>
                </table>
            </div>
        </div>

        <div class="row mb-3">
            <h5 class="border-bottom pb-1 fw-bold">
                {{ __('contract.title.set_technicians') }}
            </h5>
            <div class="form-text text-danger mb-2" id="basic-addon4">
                * Selecciona al menos 1 técnico.
            </div>
            <div class="col-12 mb-2">
                <ul class="list-group">
                    <li class="list-group-item bg-dark text-white">
                        Selecciona los técnicos disponibles que realizarán el
                        servicio:
                    </li>
                    @if ($technicians->isEmpty())
                        <li class="list-group-item"></li>
                    @endif
                    <li class="list-group-item">
                        <div class="form-check">
                            <input class="technician form-check-input me-1" type="checkbox" value="0"
                                id="technician-0" onchange="set_technicians()" />
                            <label class="form-check-label fw-bold" for="firstCheckbox">
                                Todos los técnicos
                            </label>
                        </div>
                    </li>

                    @foreach ($technicians as $technician)
                        <li class="list-group-item">
                            <div class="form-check">
                                <input class="technician form-check-input me-1" type="checkbox"
                                    value="{{ $technician->id }}" id="technician-{{ $technician->id }}"
                                    onchange="setTechnician()" />
                                <label class="form-check-label" for="technician-{{ $technician->id }}">
                                    {{ $technician->user->name }}
                                </label>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="form-text" id="basic-addon4">
                    Asigna 1 o varios técnicos a la orden de trabajo.
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-primary my-3" onclick="createContract()">
        {{ __('buttons.verify') }}
    </button>
    <input type="hidden" id="customer-id" name="customer_id" value="0" />
    <input type="hidden" id="contract-data" name="contract_data" value="[]" />
    <input type="hidden" name="technicians" id="technicians" value="[]" />

    @include('contract.modals.preview') @include('contract.modals.service')
</form>

<script>
    var execution_frecuencies = @json($exec_frecuencies);
    var days = @json($days);
    var contracts = @json($contracts);
    const new_client_account = false;
</script>
