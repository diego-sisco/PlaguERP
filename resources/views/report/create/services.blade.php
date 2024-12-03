@php
    $pests_data = [];

    function shortenText($text, $limit = 20)
    {
        return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
    }
@endphp
<div class="row">
    @foreach ($order->services as $service)
        @if ($service->prefix == 1)
            @foreach ($order->customer->floorplans as $floorplan)
                @if (!$floorplan->versions->isEmpty() && $floorplan->service_id == $service->id)
                    @php $version = $floorplan->version($order->programmed_date); @endphp
                    <h5 class="border-bottom pb-1 mb-3 fw-bold">{{ $service->name }} [
                        Plano: {{ $floorplan->filename }} ]</h5>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-secondary-subtle">Versión del plano</span>
                        <input type="text" class="form-control bg-white"
                            value="{{ $floorplan->version($order->programmed_date) ?? '0' }} - ({{ $floorplan->created_at }})"
                            disabled>
                        <a href="{{ route('report.autoreview', ['orderId' => $order->id, 'floorplanId' => $floorplan->id]) }}"
                            class="btn btn-warning"
                            onclick="return confirm('{{ __('messages.are_you_sure_autoreview') }}')"><i
                                class="bi bi-check-circle-fill"></i> {{ __('buttons.autorewiew') }}</a>
                    </div>

                    <div class="col-12">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th scope="col"># (Número)</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Zona</th>
                                    <th scope="col">Escaneado</th>
                                    <th scope="col">Revisado</th>
                                    <th scope="col">Producto sustituido</th>
                                    <th scope="col">Dispositivo reemplazado</th>
                                    <th scope="col">Plagas</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($floorplan->devices($version)->get() as $device)
                                    <tr>
                                        <th scope="row" class="align-middle">{{ $device->nplan }}</th>
                                        <td class="align-middle">
                                            {{ $device->controlPoint->name ?? '' }}
                                        </td>
                                        <td class="align-middle">{{ $device->applicationArea->name }}</td>
                                        <td class="align-middle"><i
                                                class="{{ $device->states($order->id) && $device->states($order->id)->is_scanned ? 'bi bi-check-circle-fill text-success' : 'bi bi-exclamation-triangle-fill text-warning' }}"></i>
                                        </td>
                                        <td class="align-middle" id="status-device{{ $device->id }}"><i
                                                class="{{ $order->incidents($device->id)->exists() ? 'bi bi-check-circle-fill text-success' : 'bi bi-exclamation-triangle-fill text-warning' }}"></i>
                                        </td>
                                        <td class="align-middle" id="change-product-device{{ $device->id }}"><i
                                                class="{{ $device->states($order->id) && $device->states($order->id)->is_product_changed ? 'bi bi-check-circle-fill text-success' : 'bi bi-exclamation-triangle-fill text-warning' }}"></i>
                                        </td>
                                        <td class="align-middle"><i
                                                class="{{ $device->states($order->id) && $device->states($order->id)->is_device_changed ? 'bi bi-check-circle-fill text-success' : 'bi bi-exclamation-triangle-fill text-warning' }}"></i>
                                        </td>
                                        <td class="align-middle" id="device{{ $device->id }}-pests-label">
                                            @php
                                                $reviews = [];
                                                $device_pests = $device->pests($order->id);
                                                foreach ($device_pests as $device_pest) {
                                                    $reviews[] = "({$device_pest->total}) {$device_pest->pest->name}";
                                                }
                                            @endphp

                                            {{ implode(', ', $reviews) }}
                                        </td>
                                        <td class="align-middle">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reviewModal{{ $device->id }}">
                                                <i class="bi bi-pencil-square"></i> Revisión
                                            </button>
                                        </td>

                                        <div class="modal modal-dialog-scrollable" id="reviewModal{{ $device->id }}"
                                            tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="reviewModalLabel">Revisión:
                                                            {{ $device->controlPoint->name }}
                                                            {{ $device->nplan }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body row">
                                                        @php $question_ids = []; @endphp
                                                        <div class="mb-3">
                                                            <h5 class="fw-bold border-bottom pb-1">Preguntas
                                                            </h5>
                                                            @foreach ($device->questions as $question)
                                                                @php
                                                                    $question_ids[] = $question->id;
                                                                    $incident = $order
                                                                        ->incident($device->id, $question->id)
                                                                        ->first();
                                                                @endphp
                                                                <div class="col-12 mb-3">
                                                                    <label for="exampleInputEmail1"
                                                                        class="form-label">{{ $question->question }}</label>
                                                                    @if ($question->question_option_id == 5 || $question->question_option_id == 6)
                                                                        <textarea class="form-control" placeholder="Agrega texto..." rows="5"
                                                                            onblur="setQuestion({{ $service->id }}, {{ $device->id }}, {{ $question->id }}, this.value, 'text')">
                                                                            {{ $incident->answer ?? '' }}
                                                                        </textarea>
                                                                    @elseif (
                                                                        $question->question_option_id == 3 ||
                                                                            $question->question_option_id == 4 ||
                                                                            $question->question_option_id == 8 ||
                                                                            $question->question_option_id == 9)
                                                                        <input class="form-control" type="number"
                                                                            id="" name="" step="0.001"
                                                                            value="{{ $incident->answer ?? '' }}"
                                                                            placeholder="00.00" min="1"
                                                                            onblur="setQuestion({{ $service->id }}, {{ $device->id }}, {{ $question->id }}, this.value, 'number')">
                                                                    @else
                                                                        <select
                                                                            class="form-select border-secondary border-opacity-25"
                                                                            onchange="setQuestion({{ $service->id }}, {{ $device->id }}, {{ $question->id }}, this.value, 'select')">
                                                                            <option value="0">Sin
                                                                                opción</option>
                                                                            @foreach (getOptions($question->option->id, $answers) as $option)
                                                                                <option value="{{ $option }}"
                                                                                    {{ $incident && $incident->answer == $option ? 'selected' : '' }}>
                                                                                    {{ $option }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="mb-3">
                                                            <div
                                                                class="d-flex justify-content-between align-items-baseline border-bottom mb-2">
                                                                <h5 class="pb-1 mb-2 fw-bold">Plagas: </h5>
                                                                <button class="btn btn-success btn-sm" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapsePest{{ $device->id }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="collapsePest{{ $device->id }}">
                                                                    <i class="bi bi-plus-lg"></i>
                                                                    {{ __('buttons.add') }}</button>
                                                            </div>
                                                            <div class="collapse mb-3"
                                                                id="collapsePest{{ $device->id }}">
                                                                <div class="card card-body">
                                                                    <label class="form-label">Selecciona la
                                                                        plaga</label>
                                                                    <div class="input-group">
                                                                        <select class="form-select"
                                                                            id="select-pest{{ $device->id }}">
                                                                            @foreach ($pests as $index => $pest)
                                                                                <option value="{{ $pest->id }}">
                                                                                    {{ $pest->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <button class="btn btn-primary btn-sm"
                                                                            type="button"
                                                                            onclick="setPest({{ $device->id }})">{{ __('buttons.accept') }}</button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @php $found_pests = []; @endphp
                                                            <div class="my-3" id="device{{ $device->id }}-pests">
                                                                @foreach ($device->pests($order->id) as $found_pest)
                                                                    @php
                                                                        $found_pests[] = [
                                                                            'id' => $found_pest->pest_id,
                                                                            'value' => $found_pest->total,
                                                                        ];
                                                                    @endphp

                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-text">
                                                                            <input class="form-check-input mt-0"
                                                                                type="checkbox"
                                                                                value="{{ $found_pest->id }}"
                                                                                onchange="deletePest({{ $device->id }}, {{ $found_pest->pest_id }}, this.checked)"
                                                                                checked>
                                                                        </div>
                                                                        <span
                                                                            class="input-group-text">{{ $found_pest->pest->name }}</span>
                                                                        <input type="number" class="form-control"
                                                                            value="{{ $found_pest->total }}"
                                                                            oninput="setPestValue({{ $device->id }}, {{ $found_pest->pest_id }}, this.value)"
                                                                            min="1">
                                                                    </div>
                                                                @endforeach
                                                                @php
                                                                    $pests_data[] = [
                                                                        'device_id' => $device->id,
                                                                        'pests' => $found_pests,
                                                                    ];

                                                                    $found_pests = [];
                                                                @endphp
                                                            </div>
                                                        </div>

                                                        {{-- <div class="mb-3">
                                                            <div
                                                                class="d-flex justify-content-between align-items-baseline border-bottom mb-2">
                                                                <h5 class="pb-1 mb-2 fw-bold">Productos:
                                                                </h5>
                                                                <button class="btn btn-success btn-sm" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapseProduct{{ $device->id }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="collapseProduct{{ $device->id }}">
                                                                    <i class="bi bi-plus-lg"></i>
                                                                    {{ __('buttons.add') }}</button>
                                                            </div>
                                                            <div class="collapse mb-3"
                                                                id="collapseProduct{{ $device->id }}">
                                                                <div class="card card-body">
                                                                    <label class="form-label">Selecciona el producto y
                                                                        lote</label>
                                                                    @php
                                                                        $fetchedLots = $device->product_id
                                                                            ? $device->product->selectedLots(
                                                                                $order->programmed_date,
                                                                            )
                                                                            : $lots;
                                                                    @endphp
                                                                    <div class="input-group">
                                                                        <select class="form-select"
                                                                            id="select-product{{ $device->id }}">
                                                                            @foreach ($products as $product)
                                                                                <option value="{{ $product->id }}"
                                                                                    {{ $product->id == $device->product_id ? 'selected' : '' }}>
                                                                                    {{ $product->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <select class="form-select"
                                                                            id="select-lot{{ $device->id }}">
                                                                            @foreach ($fetchedLots as $lot)
                                                                                <option value="{{ $lot->id }}">
                                                                                    [{{ $lot->registration_number }}]
                                                                                    {{ $lot->product->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <button class="btn btn-primary btn-sm"
                                                                            type="button"
                                                                            onclick="setProduct({{ $device->id }})">{{ __('buttons.accept') }}</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="my-3"
                                                                id="device{{ $device->id }}-products">

                                                            </div>
                                                        </div> --}}

                                                        <div class="mb-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="{{ $device->states($order->id)->is_product_changed ?? '0' }}"
                                                                    id="device{{ $device->id }}-product-change"
                                                                    {{ $device->states($order->id) && $device->states($order->id)->is_product_changed ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="flexCheckDefault">
                                                                    Se realizo cambio de producto/cebo?
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="{{ $device->states($order->id)->is_product_changed ?? '0' }}"
                                                                    id="device{{ $device->id }}-device-change"
                                                                    {{ $device->states($order->id) && $device->states($order->id)->is_device_changed ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="flexCheckDefault">
                                                                    Se realizo cambio de dispositivo?
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="form-floating">
                                                                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 150px">{{ $device->states($order->id)->observations ?? '' }}</textarea>
                                                                <label for="floatingTextarea2">Observaciones</label>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="device{{ $device->id }}-service"
                                                            value="{{ $service->id }}" </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                            data-info="{{ json_encode($question_ids) }}"
                                                            onclick="setReview(this, {{ $service->id }}, {{ $device->id }}, {{ $order->incidents($device->id)->exists() }})">{{ __('buttons.update') }}</button>
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach
        @else
            @php $service_ids[] = $service->id @endphp
            <div class="row mb-3">
                <h5 class="border-bottom pb-1 mb-3 fw-bold">Servicio - {{ $service->name }} </h5>

                <div class="col-12 mb-3">
                    <textarea class="summernote" id="service{{ $service->id }}-details">
                            @if (empty($service->details($order->contract_id)))
<b>Plagas a controlar: </b> @foreach ($order->pests as $index => $item)
{{ $item->total . ' ' . $item->pest->name . ($index < count($order->pests) - 1 ? ', ' : '') }}
@endforeach
<br/>
<b>Tipo de servicio: </b>{{ $service->serviceType->name }}<br/>
<b>Método de aplicación: </b>
@foreach ($order->productsByService($service->id) as $item)
{{ $item->appMethod->name }}
@endforeach
<br/>
<b>Cantidad de litros aplicados: </b><br/>
<b>Áreas de aplicación: </b>{{ $order->areas }}<br/><br/>
<b>RECOMENDACIONES</b>
<p><strong>ANTES DE LA APLICACIÓN QUÍMICA</strong></p>
<ul>
  <li>Identificar la plaga a controlar.</li>
  <li>No debe encontrarse personal en el área.</li>
  <li>No debe de haber materia prima expuesta.</li>
  <li>Asegurar que la aplicación no afecte el proceso, producción o a terceros.</li>
</ul>

<p><strong>DURANTE LA APLICACIÓN QUÍMICA</strong></p>
<ul>
  <li>En el área solo debe de encontrarse el técnico aplicador.</li>
</ul>

<p><strong>DESPUÉS DE LA APLICACIÓN QUÍMICA</strong></p>
<ul>
  <li>Respetar el tiempo de reentrada conforme a la etiqueta del producto a utilizar.</li>
  <li>Realizar recolección de plaga o limpieza necesaria al tipo de área.</li>
</ul>
@else
{{ $service->details($order->contract_id)->details }}
@endif
                        </textarea>
                </div>
            </div>
        @endif
    @endforeach
</div>

<script>
    const pests = @json($pests);
    let pests_data = []

    $(document).ready(function() {
        pests_data = @json($pests_data);
    });

    function setPest(device_id) {
        var pest_id = parseInt($(`#select-pest${device_id}`).val());
        var pest = pests.find(item => item.id == pest_id);
        var html = $(`#device${device_id}-pests`).html();

        var index = pests_data.findIndex(item => item.device_id == device_id);

        if (!pests_data[index].pests.some(item => item.id == pest_id)) {
            html +=
                `
            <div class="my-2" id="device${device_id}-pest${pest_id}">
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0"
                            type="checkbox"
                            value="${pest.id}"
                            onchange="deletePest(${device_id}, ${pest_id}, this.checked)"
                            checked>
                    </div>
                    <span
                        class="input-group-text">${pest.name}</span>
                    <input type="number" class="form-control"
                        value="1" min="1" oninput="setPestValue(${device_id}, ${pest_id}, this.value)">
                </div>
            </div>
            `;

            pests_data[index].pests.push({
                id: pest_id,
                value: 1
            });
            $(`#device${device_id}-pests`).html(html);

        }
    }

    function setProduct(device_id) {
        var product_id = parseInt($(`#select-product${device_id}`).val());
        var lot_id = parseInt($(`#select-lot${device_id}`).val());

        var html = $(`#device${device_id}-products`).html();

        var index = product_data.findIndex(item => item.device_id == device_id);

        if (!product_data[index].pests.some(item => item.id == pest_id)) {
            html +=
                `
            <div class="my-2" id="device${device_id}-pest${pest_id}">
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0"
                            type="checkbox"
                            value="${pest.id}"
                            onchange="deletePest(${device_id}, ${pest_id}, this.checked)"
                            checked>
                    </div>
                    <span
                        class="input-group-text">${pest.name}</span>
                    <input type="number" class="form-control"
                        value="1" min="1" oninput="setPestValue(${device_id}, ${pest_id}, this.value)">
                </div>
            </div>
            `;

            pests_data[index].pests.push({
                id: pest_id,
                value: 1
            });
            $(`#device${device_id}-pests`).html(html);

        }
    }

    function deletePest(device_id, pest_id, isChecked) {
        if (!isChecked) {
            var confirmed = confirm("¿Estás seguro de que deseas eliminar la plaga?");
            if (confirmed) {
                var index = pests_data.findIndex(item => item.device_id == device_id);
                pests_data[index].pests = pests_data[index].pests.filter(item => item.id != pest_id)
                $(`#device${device_id}-pest${pest_id}`).remove();
            } else {
                $(`#device${device_id}-pest${pest_id}`).prop('checked', true);
            }
        }
    }

    function setPestValue(device_id, pest_id, value) {
        var i = pests_data.findIndex(item => item.device_id == device_id);
        var j = pests_data[i].pests.findIndex(item => item.id == pest_id)

        pests_data[i].pests[j].value = parseInt(value);
    }

    function setReview(element, service_id, device_id, hasReview) {
        const question_ids = JSON.parse(element.getAttribute('data-info'));
        const found_incidents = incidents.filter(incident => incident.device_id == device_id);

        if (found_incidents) {
            if (!hasReview) {
                if (found_incidents.length != question_ids.length) {
                    alert('Hay preguntas que aún no han sido respondidas.');
                    return;
                }

                found_incidents.forEach(incident => {
                    if (incident.value == '') {
                        alert('Algunas preguntas contienen campos incompletos');
                        return;
                    }

                    if (incident.type == 'select' && incident.value == '0') {
                        alert('Algunas preguntas contienen valores no válidos.');
                        return;
                    }
                });
            }

            storeIncidents(device_id);
        }
    }

    function storeIncidents(device_id) {
        var formData = new FormData();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var is_product_changed = $(`#device${device_id}-product-change`).is(':checked');
        var is_device_changed = $(`#device${device_id}-device-change`).is(':checked');
        var lot = $(`#device${device_id}-product-lot`).val();
        var service = $(`#device${device_id}-service`).val();
        var count = $(`#device${device_id}-product-count`).val();
        var fetched_pests = pests_data.find(item => item.device_id == device_id);

        formData.append('deviceId', JSON.stringify(device_id))
        formData.append('incidents', JSON.stringify(incidents));
        formData.append('pests_found', JSON.stringify(fetched_pests))
        formData.append('is_product_changed', JSON.stringify(is_product_changed))
        formData.append('is_device_changed', JSON.stringify(is_device_changed))
        formData.append('lot_id', JSON.stringify(lot))
        formData.append('service_id', JSON.stringify(service))
        formData.append('amount', JSON.stringify(count))

        console.log(formData);

        $.ajax({
            url: "{{ route('report.store.incidents', ['orderId' => $order->id]) }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function(response) {
                console.log(response)
                if (response.success) {
                    /*$('#status-device' + device_id).html(
                        '<i class="bi bi-check-circle-fill text-success"></i>');

                    if (response.is_changed) {
                        $('#change-product-device' + device_id).html(
                            '<i class="bi bi-check-circle-fill text-success"></i>');
                    } else {
                        $('#change-product-device' + device_id).html(
                            '<i class="bi bi-exclamation-triangle-fill text-warning"></i>');
                    }*/
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }
</script>
