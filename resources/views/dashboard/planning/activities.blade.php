@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <style>
        .draggable {
            width: 150px;
            cursor: pointer;
        }

        .droppable {
            min-height: 50px;
        }

        .container {
            overflow: hidden;
        }

        .content {
            width: 1000px;
            white-space: nowrap;
        }
    </style>

    <div class="row w-100 justify-content-between m-0 h-100">
        @include('dashboard.planning.navigation')
        <div class="col-11 p-3 container">
            <div class="col-12">
                <div class="row justify-content-between mb-2">
                    <div class="col-auto">
                        <div class="col-auto">
                            <form class="input-group" method="GET" action="{{ route('planning.activities') }}">
                                @csrf
                                <input type="text" class="form-control" id="date-range" name="date"
                                    value="{{ $date }}" />
                                <button class="btn btn-success btn-sm" type="submit"
                                    id="search-button">{{ __('buttons.search') }}</button>
                            </form>


                        </div>
                    </div>

                    <div class="col-auto">
                        <span class="mx-3"><i class="bi bi-square-fill" style="color:#c0392b"></i> Domestico</span>
                        <span class="mx-3"><i class="bi bi-square-fill" style="color:#196f3d"></i> Comercial</span>
                        <span class="mx-3"><i class="bi bi-square-fill" style="color:#1f618d"></i> Industrial</span>
                    </div>
                </div>

                <div class="row justify-content-end mb-3">
                    <div class="col-auto btn-group">
                        <button class="btn btn-outline-info text-dark btn-sm mb-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1"
                            onclick="checkboxControl('collapse1', true)">
                            <i class="bi bi-funnel-fill"></i> Técnicos
                        </button>

                        <button class="btn btn-outline-info text-dark btn-sm mb-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2"
                            onclick="checkboxControl('collapse2', false)">
                            <i class="bi bi-funnel-fill"></i> Línea de negocio
                        </button>

                        <button class="btn btn-outline-info text-dark btn-sm mb-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3"
                            onclick="checkboxControl('collapse3', false)">
                            <i class="bi bi-funnel-fill"></i> Delegaciones
                        </button>

                        <button class="btn btn-outline-info text-dark btn-sm  mb-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4"
                            onclick="checkboxControl('collapse4', false)">
                            <i class="bi bi-funnel-fill"></i> Tipo de servicio
                        </button>
                    </div>
                </div>

                <div class="collapse mb-3" id="collapse1">
                    <div class="card card-body">
                        <div class="row mb-3">
                            @foreach ($technicians as $technician)
                                <div class="col-3">
                                    <div class="form-check">
                                        <input class="form-check-input border-secondary border-opacity-50" type="checkbox"
                                            value="{{ $technician->id }}" id="{{ $technician->id }}" checked />
                                        <label class="form-check-label" for="technician{{ $technician->id }}">
                                            {{ $technician->user->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <button class="btn btn-primary btn-sm" onclick="filterPlanning('collapse1', 'technician')">
                                    {{ __('buttons.filter') }} </button>
                                <button class="btn btn-danger btn-sm" onclick="resetInput(1)"> {{ __('buttons.reset') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="collapse mb-3" id="collapse2">
                    <div class="card card-body">
                        <div class="row mb-3">
                            @foreach ($business_lines as $line)
                                <div class="col-3">
                                    <div class="form-check">
                                        <input class="form-check-input border-secondary border-opacity-50" type="checkbox"
                                            value="{{ $line->id }}" id="{{ $line->id }}">
                                        <label class="form-check-label" for="business-line{{ $line->id }}">
                                            {{ $line->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <button class="btn btn-primary btn-sm"
                                    onclick="filterPlanning('collapse2', 'business_line')">
                                    {{ __('buttons.filter') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="collapse mb-3" id="collapse3">
                    <div class="card card-body">
                        <div class="row mb-3">
                            @foreach ($branches as $branch)
                                <div class="col-3">
                                    <div class="form-check">
                                        <input class="form-check-input border-secondary border-opacity-50" type="checkbox"
                                            value="{{ $branch->id }}" id="{{ $branch->id }}">
                                        <label class="form-check-label" for="branch{{ $branch->id }}">
                                            {{ $branch->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <button class="btn btn-primary btn-sm" onclick="filterPlanning('collapse3', 'branch')">
                                    {{ __('buttons.filter') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="collapse mb-3" id="collapse4">
                    <div class="card card-body">
                        <div class="row mb-3">
                            @foreach ($service_types as $service_type)
                                <div class="col-3">
                                    <div class="form-check">
                                        <input class="form-check-input border-secondary border-opacity-50" type="checkbox"
                                            value="{{ $service_type->id }}" id="{{ $service_type->id }}">
                                        <label class="form-check-label" for="service-type{{ $service_type->id }}">
                                            {{ $service_type->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <button class="btn btn-primary btn-sm"
                                    onclick="filterPlanning('collapse4', 'service_type')">
                                    {{ __('buttons.filter') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="planningTable">
                        <thead>
                            <tr>
                                <th scope="col-auto">Hora</th>
                                @foreach ($technicians as $technician)
                                    <th class="text-center align-middle" scope="col"> {{ $technician->user->name }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daily_program as $rowIndex => $daily)
                                <tr>
                                    <td class="align-middle fw-bold"> {{ $daily['hour'] }} </td>
                                    @foreach ($technicians as $colIndex => $technician)
                                        <td class="droppable" id="cell{{ $technician->id }}"
                                            ondrop="drop(event, {{ $rowIndex }}, {{ $colIndex }})"
                                            ondragover="allowDrop(event)">
                                            @foreach ($daily['activities'] as $order)
                                                @if ($order['technicianIds']->contains($technician->id))
                                                    <div class="card text-light draggable mb-3"
                                                        id="drag{{ $order['id'] }}" draggable="true"
                                                        ondragstart="drag(event, {{ $order['id'] }})"
                                                        ondblclick="redirectTo('{{ route('order.edit', ['id' => $order['id']]) }}')"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-custom-class="custom-tooltip"
                                                        data-bs-title="{{ $order['address'] }}"
                                                        style="width: 8rem; background-color: {{ $order['service_type'] == 1 ? '#c0392b' : ($order['service_type'] == 2 ? '#196f3d' : '#1f618d') }};">
                                                        <div class="card-body">
                                                            <h5 class="card-title fw-bold">Orden {{ $order['id'] }}
                                                            </h5>
                                                            <h6 class="card-subtitle mb-2">
                                                                {{ $order['start_time'] . ', ' . $order['programmed_date'] }}
                                                            </h6>
                                                            <p class="card-text">
                                                                {{ $order['customer'] }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        var draggedItem = null; // Elemento arrastrado
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var orderID = 0;

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        $(function() {
            $('#date-range').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'DD/MM/YYYY' // Cambiar el formato aquí
                },
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Esta semana': [moment().startOf('week'), moment().endOf('week')],
                    'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                    'Este mes': [moment().startOf('month'), moment().endOf('month')],
                    'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                    'Este año': [moment().startOf('year'), moment().endOf('year')],
                },
                alwaysShowCalendars: true
            });
        });

        $('[data-bs-toggle="collapse"]').on('click', function() {
            const targetId = $(this).attr('data-bs-target');

            $('.collapse').each(function() {
                if ($(this).attr('id') != targetId.slice(1)) {
                    $(this).removeClass('show');
                }
            });
        });


        function checkboxControl(id, is_checked) {
            filter_data = {};
            $(`#${id} input[type="checkbox"]`).prop('checked', is_checked);
        }

        function filter(id, key) {
            var filter_data = {};
            const checkedValues = $(`#${id} input[type="checkbox"]:checked`).map(function() {
                return parseInt($(this).val());
            }).get();

            filter_data = {
                key,
                values: checkedValues
            };

            return filter_data;
        }

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev, order) {
            draggedItem = ev.target; // Guarda el elemento arrastrado
            orderID = order
        }

        function drop(ev, row, col) {
            ev.preventDefault();
            if (ev.target.classList.contains('droppable')) {
                ev.target.appendChild(draggedItem);
                var technicianID = ev.target.id.substring(4);
                var formData = new FormData();
                formData.append("technicianId", parseInt(technicianID));
                formData.append("orderId", orderID);
                formData.append("hour", row);
                formData.append("col", col);

                $.ajax({
                    type: "POST",
                    url: "{{ route('planning.schedule.update') }}",
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    data: formData,
                    success: function(response) {
                        if(response == 200) {
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al enviar la solicitud AJAX:", error);
                    }
                });

            }
        }

        function redirectTo(url) {
            window.location.href = url;
        }

        function resetInput(type) {
            $(`#collapse${type} input[type="checkbox"]`).prop('checked', false);
        }

        function filterPlanning(id, key) {
            const filter_data = filter(id, key);
            var formData = new FormData();
            var date = $('#date-range').val();
            formData.append('data', JSON.stringify(filter_data));
            formData.append('date', JSON.stringify(date));

            $.ajax({
                type: "POST",
                url: "{{ route('planning.filter') }}",
                contentType: false,
                processData: false,
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                data: formData,
                success: function(response) {
                    if (response.technicians.length > 0) {
                        $('#planningTable thead').empty();
                        var technicians = response.technicians;
                        var thead = '<tr>';
                        thead += '<th scope="col-auto">Hora</th>';
                        technicians.forEach(function(technician) {
                            thead += '<th class="text-center align-middle" scope="col">' + technician
                                .name +
                                '</th>';
                        });
                        thead += '</tr>';
                        $('#planningTable thead').append(thead);
                        $('#planningTable tbody').empty();
                        response.daily_program.forEach(function(daily, rowIndex) {
                            var row = '<tr>';
                            row += '<td class="align-middle fw-bold">' + daily.hour + '</td>';

                            technicians.forEach(function(technician, colIndex) {
                                /*var cell = '<td class="droppable" id="cell' + technician.id +
                                    '" ondrop="drop(event, ' + rowIndex + ', ' + colIndex +
                                    ')"
                                ondragover = "allowDrop(event)" > ';*/

                                var cell =
                                    `<td class="droppable" id="cell${technician.id}" ondrop="drop(event, ${rowIndex}, ${colIndex}) ondragover="allowDrop(event)">`;

                                daily.activities.forEach(function(order) {
                                    if (order.technicianIds.includes(technician.id)) {
                                        var color = order.service_type == 1 ?
                                            '#c0392b' : (
                                                order.service_type == 2 ? '#196f3d' :
                                                '#1f618d');

                                        cell += `<div class="card text-light draggable mb-3" id="drag${order.id}" draggable="true" ondragstart="drag(event, ${order.id})" ondblclick="redirectTo('{{ route('order.edit', ['id' => 'ORDER_ID']) }}'.replace('ORDER_ID', ${order.id}))" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="${order.address}" style="width: 8rem; background-color: ${color};">
                                                <div class="card-body">
                                                    <h5 class="card-title fw-bold">Orden ${order.id}</h5>
                                                    <h6 class="card-subtitle mb-2">${order.start_time}, ${order.programmed_date}</h6>
                                                    <p class="card-text">${order.customer}</p>
                                                </div>
                                            </div>`;

                                        /*cell +=
                                            '<div class="card text-light draggable mb-3"
                                        id = "drag' +
                                        order.id +
                                            '"
                                        draggable = "true"
                                        ondragstart = "drag(event, ' +
                                        order.id +
                                            ')"
                                        ondblclick =
                                            "redirectTo(\'{{ route('order.edit', ['id' => 'ORDER_ID']) }}\'.replace(\'ORDER_ID\', ' +
                                        order.id +
                                            '))"
                                        data - bs - toggle = "tooltip"
                                        data - bs - placement = "top"
                                        data - bs - custom - class = "custom-tooltip"
                                        data - bs - title = "' +
                                        order.address +
                                            '"
                                        style = "width: 8rem; background-color: ' +
                                        color + ';">';
                                        cell += '<div class="card-body">';
                                        cell +=
                                            '<h5 class="card-title fw-bold">Orden ' +
                                            order.id + '</h5>';
                                        cell += '<h6 class="card-subtitle mb-2">' +
                                            order
                                            .start_time + ', ' + order.programmed_date +
                                            '</h6>';
                                        cell += '<p class="card-text">' + order
                                            .customer +
                                            '</p>';
                                        cell += '</div> <
                                            /div>';*/
                                    }
                                });

                                cell += '</td>';
                                row += cell;
                            });

                            row += '</tr>';
                            $('#planningTable tbody').append(row);
                        });

                        // Añadir la clase 'table-bordered'
                        $('#planningTable').addClass('table-bordered');
                    } else {
                        alert('Filtro no aplicado porque no hay técnicos relacionados a las órdenes encontradas.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al enviar la solicitud AJAX:", error);
                }
            });
        }
    </script>
@endsection
