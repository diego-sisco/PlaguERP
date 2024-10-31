@php
    $selected_services = [];
    $cost = 0;

    foreach ($order->services as $service) {
        $selected_services[] = [
            'id' => $service->id,
            'name' => $service->name,
            'type' => [$service->serviceType->name],
            'line' => [$service->businessLine->name],
            'cost' => $service->cost,
        ];
        $cost += $service->cost;
    }
@endphp
<form id="order-form" class="form p-5 pt-3" method="POST" action="{{ route('order.update', ['id' => $order->id]) }}"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="url_customer" id="url-customer" value="{{ route('order.search.customer') }}">
    <input type="hidden" name="url_service_filter" id="url-service-filter"
        value="{{ route('order.search.service', ['type' => 0]) }}">
    <input type="hidden" name="url_service_input" id="url-service-input"
        value="{{ route('order.search.service', ['type' => 1]) }}">

    <div class="row mb-3">
        <h5 class="border-bottom pb-1 fw-bold"> Datos basicos de la solicitud: </h5>
        <div class="col-2 mb-2">
            <label for="start_time" class="form-label is-required">{{ __('order.data.start_time') }}:</label>
            <input type="time" class="form-control border-secondary border-opacity-25" id="start_time"
                name="start_time" value="{{ $order->start_time }}" onchange="validate_time()" required>
        </div>
        <div class="col-2 mb-2">
            <label for="end_time" class="form-label">{{ __('order.data.end_time') }}:</label>
            <input type="time" class="form-control border-secondary border-opacity-25" id="end_time" name="end_time"
                value="{{ $order->end_time }}">
        </div>
        <div class="col-2 mb-2">
            <label for="request_date" class="form-label is-required">{{ __('order.data.programmed_date') }}:</label>
            <input type="date" class="form-control border-secondary border-opacity-25" id="programmed_date"
                name="programmed_date" value="{{ $order->programmed_date }}" required>
        </div>
        <div class="col-2 mb-2">
            <label for="request_date" class="form-label">Fecha de finalización:</label>
            <input type="date" class="form-control border-secondary border-opacity-25" id="completed_date"
                name="completed_date"" value="{{ $order->completed_date }}">
        </div>
        <div class="col-3 mb-2">
            <label for="request_date" class="form-label is-required">{{ __('order.data.status') }}:</label>
            <select class="form-select border-secondary border-opacity-25 " id="status" name="status_id">
                @foreach ($order_status as $status)
                    <option value="{{ $status->id }}" {{ $status->id == $order->status->id ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <h5 class="border-bottom pb-1 fw-bold"> {{ __('order.data.customer') }}: </h5>
        <div class="col-12 p-2 pb-0">
            <ul id="customer-select">
                <li class="text-break fw-bold">Nombre: <span class="fw-normal text-primary"> {{ $customer->name }}
                    </span></li>
                <li class="text-break fw-bold">Teléfono: <span class="fw-normal"> {{ $customer->phone }} </span></li>
                <li class="text-break fw-bold">Dirección: <span class="fw-normal"> {{ $customer->address }} </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="row mb-2">
        <h5 class="border-bottom pb-1 mb-1 fw-bold"> {{ __('order.data.service') }}: </h5>
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
            <h6 class="pb-1 mb-1 fw-bold">{{ __('order.title.find_service') }}:</h6>
            <div class="input-group mb-3">
                <input type="search" class="form-control border-secondary border-opacity-25" id="search-service-input"
                    name="search_service_input" placeholder="Nombre del servicio">
                <button class="btn btn-primary btn-sm" type="button" id="btn-search-service" onclick="searchService()">
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

    <div class="row mb-3">
        <h5 class="border-bottom pb-1 fw-bold"> {{ __('order.title.set_technicians') }}: </h5>
        <div class="form-text text-danger mb-2" id="basic-addon4">
            * Selecciona al menos 1 técnico.
        </div>
        <div class="col-12 mb-2">
            <ul class="list-group">
                <li class="list-group-item  bg-dark text-white">
                    Selecciona los técnicos disponibles que realizarán el servicio:
                </li>
                @if ($technicians->isEmpty())
                    <li class="list-group-item "> </li>
                @endif
                <li class="list-group-item ">
                    <div class="form-check">
                        <input class="technician form-check-input me-1 border-secondary" type="checkbox"
                            value="0" id="technician-0" onchange="setTechnician()"
                            {{ $order->allTechnicians() ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="firstCheckbox">
                            Todos los técnicos
                        </label>
                    </div>
                </li>

                @foreach ($technicians as $technician)
                    <li class="list-group-item ">
                        <div class="form-check">
                            <input class="technician form-check-input me-1 border-secondary" type="checkbox"
                                value="{{ $technician->id }}" id="technician-{{ $technician->id }}"
                                onchange="setTechnician()" {{ $order->allTechnicians() ? 'disabled' : '' }}
                                {{ !$order->allTechnicians() && $order->hasTechnician($technician->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="technician-{{ $technician->id }}">
                                {{ $technician->user->name }}
                            </label>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="form-text" id="basic-addon4">Asigna 1 o varios técnicos a la orden de trabajo.</div>
        </div>
    </div>

    <div class="row mb-3">
        <h5 class="border-bottom pb-1 mb-3 fw-bold"> Datos técnicos adicionales: </h5>
        <div class="col-12 mb-3">
            <label class="mb-2">{{ __('order.data.execution') }}: </label>
            <textarea class="form-control border-secondary border-opacity-25" id="floatingTextarea" id="execution"
                name="execution" style="height: 100px">{{ $order->execution }}</textarea>

        </div>
        <div class="col-12 mb-3">
            <label class="mb-2">{{ __('order.data.areas') }}: </label>
            <textarea class="form-control border-secondary border-opacity-25" id="areas" name="areas"
                style="height: 100px">{{ $order->areas }}</textarea>
        </div>
        <div class="col-12">
            <label class="mb-2">{{ __('order.data.comments') }}: </label>
            <textarea class="form-control border-secondary border-opacity-25" id="additional_comments" name="additional_comments"
                style="height: 100px">{{ $order->additional_comments }}</textarea>
        </div>
    </div>

    <div class="row mb-3">
        <h5 class="border-bottom pb-1 mb-3 fw-bold"> {{ __('order.data.quotation') }}: </h5>
        <div class="col-3 mb-1">
            <label for="cost" class="form-label">{{ __('order.data.cost') }}:</label>
            <div class="input-group mb-0">
                <span class="input-group-text bg-secondary">$</span>
                <input type="number" class="form-control" id="cost" name="cost" min="0"
                    placeholder="0" value="{{ $cost }}" readonly required />
            </div>
        </div>
        <div class="col-3 mb-1">
            <label for="price" class="form-label is-required"> {{ __('order.data.price') }}: </label>
            <div class="input-group border border-success rounded mb-0">
                <span class="input-group-text bg-success border-success text-white">$</span>
                <input type="number" class="form-control" id="price" name="price" min="0"
                    placeholder="0" value="{{ $order->price ?? $cost * 1.5 }}" />
            </div>
        </div>
    </div>

    <input type="hidden" name="technicians" id="technicians"
        value="{{ $order->allTechnicians() ? '[0]' : json_encode($order->technicians->pluck('id')->toArray()) }}">
    <input type="hidden" id="services" name="services" value="">
    <input type="hidden" id="customer-id" name="customer_id" value="{{ $order->customer_id }}">


    <button type="button" class="btn btn-primary my-3 me-3"
        onclick="generateOrder()">{{ __('buttons.update') }}</button>

    <a class="btn btn-dark" href="{{ route('check.report', ['id' => $order->id]) }}">
        <i class="bi bi-file-pdf-fill"></i> Reporte
    </a>
</form>

<script>
    const stored_services = @json($selected_services);

    $(document).ready(function() {
        showStoreService();

        $('#price').on('blur', function() {
            const costValue = parseFloat($('#cost').val());
            const priceValue = parseFloat($(this).val());

            if (priceValue < costValue) {
                alert('El precio no puede ser menor al costo')
                $(this).val(costValue); // Establece el precio al costo automáticamente
            }
        });
    });

    function showStoreService() {
        stored_services.forEach(service => {
            selected_services.push({
                id: service.id,
                name: service.name,
                type: service.type,
                line: service.line,
                cost: service.cost,
            });
        });

        createServicesList()
    }
</script>
