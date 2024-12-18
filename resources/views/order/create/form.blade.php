<form id="order-form" class="form" method="POST" action="{{ route('order.store') }}"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="url_customer" id="url-customer" value="{{ route('order.search.customer') }}">
    <input type="hidden" name="url_service_filter" id="url-service-filter"
        value="{{ route('order.search.service', ['type' => 0]) }}">
    <input type="hidden" name="url_service_input" id="url-service-input"
        value="{{ route('order.search.service', ['type' => 1]) }}">

    <div class="row">
        <h5 class="border-bottom pb-1 fw-bold">{{ __('order.title.basic') }}</h5>
        <div class="col-2 mb-3">
            <label for="start_time" class="form-label is-required">{{ __('order.data.start_time') }}</label>
            <input type="time" class="form-control border-secondary border-opacity-50" id="start-time"
                name="start_time" placeholder="00:00" required>
        </div>
        <div class="col-2 mb-3">
            <label for="end_time" class="form-label">{{ __('order.data.end_time') }}</label>
            <input type="time" class="form-control border-secondary border-opacity-50" id="end-time" name="end_time"
                placeholder="00:00">
        </div>
        <div class="col-2 mb-3">
            <label for="request_date" class="form-label is-required">{{ __('order.data.programmed_date') }}</label>
            <input type="date" class="form-control border-secondary border-opacity-50" id="programmed-date"
                name="programmed_date" required>
        </div>
        <!--div class="col-4 mb-2">
            <label for="request_date" class="form-label">{{ __('order.data.frecuency') }}</label>
            <div class="row">
                <div class="col-3">
                    <input type="number" class="form-control border-secondary border-opacity-50" id="number"
                        name="number" value="0" min=0>
                </div>
                <div class="col-6">
                    <select class="form-select border-secondary border-opacity-50 " id="frequency" name="frequency">
                        <option value="1">Día</option>
                        <option value="2">Semana</option>
                        <option value="3">Mes</option>
                        <option value="4">Año</option>
                    </select>
                </div>
            </div>
        </div-->
    </div>

    <div class="row mb-3">
        <h5 class="border-bottom pb-1 fw-bold"> {{ __('order.data.customer') }}</h5>
        <label for="client" class="form-label m-0">Busca el cliente a traves de los siguientes campos</label>
        <div class="form-text text-danger m-0" id="basic-addon4">* En caso de que no aparezca deberas crearlo.</div>
        <div class="col-12 p-0 m-0">
            <a href="{{ route('customer.create', ['id' => 0, 'type' => 1]) }}" id="form_service_button"
                class="btn btn-link" target="_blank">
                {{ __('customer.title.create') }}
            </a>
        </div>
        <div class="col-3">
            <label for="client" class="form-label">Nombre</label>
            <input class="form-control border-secondary border-opacity-50" name="customer_name" id="customer-name"
                placeholder="Example" value="">
        </div>
        <div class="col-3">
            <label for="client" class="form-label">Teléfono</label>
            <input class="form-control border-secondary border-opacity-50" name="customer_phone" id="customer-phone"
                placeholder="0000000000" value="">
        </div>
        <div class="col-6">
            <label for="client" class="form-label">Dirección</label>
            <input class="form-control border-secondary border-opacity-50" name="customer_address" id="customer-address"
                placeholder="Example #00, Col. Example" value="">
        </div>
        <div class="col-3 mt-2 mb-3">
            <button id="form_service_button" type="button" class="btn btn-primary btn-sm" onclick="searchCustomer()">
                {{ __('buttons.search') }}
            </button>
        </div>

        @include('order.modals.customers')

        <div class="col-12" id="customer-select">
        </div>
    </div>

    <div class="row mb-2" id="select-contract">
        <h5 class="border-bottom pb-1 fw-bold">
            {{ __('contract.title.associate_contract') }}:
        </h5>
        <div class="form-text text-danger m-0 mb-1" id="basic-addon4">
            * Si deseas vincular las órdenes a un contrato que ya ha sido creado, selecciónalo despues de buscar al cliente.
        </div>
        <div class="col-3 mb-3">
            <select class="form-select" id="contract" name="contract_id">
                <option value="0" selected>Sin contrato</option>
            </select>
        </div>
    </div>

    <div class="row mb-2">
        <h5 class="border-bottom pb-1 mb-1 fw-bold"> {{ __('order.data.service') }}</h5>
        <div class="form-text text-danger m-0" id="basic-addon4">
            * Selecciona al menos 1 servicio.
        </div>
        <div class="form-text text-danger m-0" id="basic-addon4">
            * En caso de que no aparezca deberas crearlo.
        </div>
        <div class="col-12 p-0 m-0 mb-1">
            <a href="{{ route('service.create', ['page' => 1]) }}" id="form_service_button" class="btn btn-link"
                target="_blank">
                {{ __('service.button.create') }}
            </a>
        </div>

        <div class="col-12">
            <h6 class="pb-1 mb-1 fw-bold">{{ __('order.title.find_service') }}</h6>
            <div class="input-group mb-3">
                <input type="search" class="form-control border-secondary border-opacity-50"
                    id="search-service-input" name="search_service_input" placeholder="Nombre del servicio">
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
                        <th class="col-3 text-center" scope="col">Nombre</th>
                        <th class="col-2 text-center" scope="col">Tipo de servicio</th>
                        <th class="col-3 text-center" scope="col">Linea de negocio</th>
                        <th class="col-3 text-center" scope="col">{{ __('buttons.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="selected-services"></tbody>
            </table>
        </div>
    </div>

    @include('order.modals.service')

    <div class="row">
        <h5 class="border-bottom pb-1 fw-bold"> Asignacioń de técnicos</h5>
        <div class="form-text text-danger mb-2" id="basic-addon4">
            * Selecciona al menos 1 técnico.
        </div>
        <div class="col-12 mb-3">
            <ul class="list-group">
                <li class="list-group-item  bg-dark text-white">
                    Selecciona los técnicos disponibles que realizarán el servicio:
                </li>
                @if ($technicians->isEmpty())
                    <li class="list-group-item "> </li>
                @endif

                <li class="list-group-item ">
                    <div class="form-check">
                        <input class="technician form-check-input me-1 " type="checkbox" value="0"
                            id="technician-0" onchange="setTechnician()">
                        <label class="form-check-label fw-bold" for="firstCheckbox">
                            Todos los técnicos
                        </label>
                    </div>
                </li>

                @foreach ($technicians as $technician)
                    <li class="list-group-item ">
                        <div class="form-check">
                            <input class="technician form-check-input me-1 " type="checkbox"
                                value="{{ $technician->id }}" id="technician-{{ $technician->id }}"
                                onchange="setTechnician()">
                            <label class="form-check-label" for="firstCheckbox">
                                {{ $technician->name }}
                            </label>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="row">
        <h5 class="border-bottom pb-1 mb-3 fw-bold"> {{ __('order.title.additional') }}</h5>
        <div class="col-12 mb-3">
            <label class="mb-2">{{ __('order.data.execution') }}</label>
            <textarea class="form-control border-secondary border-opacity-50" id="execution" name="execution"
                style="height: 100px"></textarea>
        </div>
        <div class="col-12 mb-3">
            <label class="mb-2">{{ __('order.data.areas') }}</label>
            <textarea class="form-control border-secondary border-opacity-50" id="areas" name="areas"
                style="height: 100px"></textarea>
        </div>
        <div class="col-12 mb-3">
            <label class="mb-2">{{ __('order.data.comments') }}</label>
            <textarea class="form-control border-secondary border-opacity-50" id="additional_comments" name="additional_comments"
                style="height: 100px"></textarea>
        </div>
    </div>

    <div class="row">
        <h5 class="border-bottom pb-1 mb-3 fw-bold"> {{ __('order.data.quotation') }}</h5>
        <div class="col-2 mb-3">
            <label for="cost" class="form-label">{{ __('order.data.cost') }}</label>
            <div class="input-group mb-0">
                <span class="input-group-text bg-secondary">$</span>
                <input type="number" class="form-control" id="cost" name="cost" min="0"
                    placeholder="0" readonly disabled/>
            </div>
        </div>
        <div class="col-2 mb-3">
            <label for="price" class="form-label is-required"> {{ __('order.data.price') }}</label>
            <div class="input-group rounded mb-0">
                <span class="input-group-text bg-success border-success text-white">$</span>
                <input type="number" class="form-control" id="price" name="price" min="0"
                    placeholder="0" />
            </div>
        </div>
    </div>

    <input type="hidden" id="customer-id" name="customer_id" value="">
    <input type="hidden" id="services" name="services" value="">
    <input type="hidden" name="technicians" id="technicians" value="">

    <button type="button" class="btn btn-primary my-3" onclick="generateOrder()">{{ __('buttons.store') }} </button>
</form>

<script>
        var contracts = @json($contracts);
        const new_client_account = false;
</script>