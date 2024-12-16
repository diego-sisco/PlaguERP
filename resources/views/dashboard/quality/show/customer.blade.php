@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-3">
            <a href="{{ route('quality.index') }}" class="col-auto btn-primary p-0 fs-3"><i
                    class="bi bi-arrow-left m-3"></i></a>
            <h1 class="col-auto fs-2 fw-bold m-0">{{ $customer->name }}</h1>
        </div>
        <div class="row justify-content-center m-3">
            <div class="col">
                <a href="{{ route('quality.orders', ['id' => $customer->id]) }}" class="text-decoration-none">
                    <div class="card border border-secondary mb-3">
                        <div class="card-header text-center">Ordenes de Servicio</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span class="badge bg-warning">{{ $customer->countOrdersbyStatus(1) }}</span></h5>
                                <h5><span class="badge bg-info">{{ $customer->countOrdersbyStatus(3) }}</span></h5>
                                <h5><span class="badge bg-success">{{ $customer->countOrdersbyStatus(5) }}</span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('quality.contracts', ['id' => $customer->id]) }}" class="text-decoration-none">
                    <div class="card border border-secondary mb-3">
                        <div class="card-header text-center">Contratos</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span class="badge bg-dark">{{ $customer->contracts->count() }}</span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('customer.edit', ['id' => $customer->id, 'type' => 2, 'section' => 8]) }}" class="text-decoration-none">
                    <div class="card border border-secondary mb-3">
                        <div class="card-header text-center">Planos</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span class="badge bg-dark">{{ $customer->floorplans->count() }}</span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('quality.zones', ['id' => $customer->id]) }}" class="text-decoration-none">
                    <div class="card border border-secondary mb-3">
                        <div class="card-header text-center">Zonas</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span class="badge bg-dark">{{ $customer->applicationAreas->count() }}</span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('quality.devices', ['id' => $customer->id]) }}"
                    class="text-decoration-none">
                    <div class="card border border-secondary mb-3" style="max-width: 18rem;">
                        <div class="card-header text-center">Dispositivos</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span class="badge bg-dark">
                                    {{ $count_devices }}
                                </span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('customer.edit', ['id' => $customer->id, 'type' => 1, 'section' => 6]) }}"
                    class="text-decoration-none">
                    <div class="card border border-secondary mb-3" style="max-width: 18rem;">
                        <div class="card-header text-center">Archivos</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span
                                    class="badge {{ $customer->files->where('path', '!=', null)->count() < 6 ? 'bg-dark' : 'bg-success' }}">{{ $customer->files->where('path', '!=', null)->count() }}</span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <button class="btn btn-primary mx-4 mb-4" data-bs-toggle="modal" data-bs-target="#technicalModal">
                    Reasignación de técnicos
                </button>
            </div>
        </div>

        <div class="mx-3">
            <div class="table-responsive">
                <table class="table table-bordered text-center caption-top">
                    <caption class="border bg-secondary-subtle p-2 fw-bold text-dark">
                        Pendientes
                    </caption>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"> Actividad </th>
                            <th scope="col"> Fecha </th>
                            <th scope="col"> Acciones </th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($pendings as $index => $pending)
                            <tr>
                                <th scope="row">{{ ++$index }}</th>
                                <td>{{ $pending['content'] }}</td>
                                <td>{{ $pending['date'] }}</td>
                                <td>
                                    @can('write_customer')
                                        <a href="{{ $pending['type'] == 'contract' ? route('contract.edit', ['id' => $pending['id']]) : ($pending['type'] == 'order' ? route('order.edit', ['id' => $pending['id']]) : route('customer.edit', ['id' => $pending['id'], 'type' => 1, 'section' => 6])) }}"
                                            class="btn btn-secondary btn-sm">
                                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No hay pendientes por el momento.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="technicalModal" tabindex="-1" aria-labelledby="technicalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="technicalModalLabel">Técnicos para {{$customer->name}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label is-required">Rango de fecha</label>
                        <div class="input-group">
                            <input type="text" hidden id="search-date-url" value="{{ route('ajax.quality.search.date.technician', ['id' => $customer->id]) }}" />
                            <input type="text" class="form-control border-secondary" id="search-date" name="date" value="" autocomplete="off"/>
                            <button class="btn btn-primary" onclick="getOrdersByDate()"><i class="bi bi-search"></i> {{ __('buttons.search')}}</button>
                        </div>
                    </div>

                    <div class="row my-4">
                        <div class="col mx-3">
                            <div class="accordion" id="accordion-technicians" data-technicians='@json($technicians)'>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col mx-4">
                            <ul id="orders-list" class="list-group">

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('quality.technicians.replace', ['id' => $customer->id]) }}" id="replaceForm">
                        @csrf
                        <input type="hidden" name="id_orders" id="orderIds" value="">
                        <input type="hidden" name="technicians" id="technicianIds" value="">
                        <button type="submit" class="btn btn-primary mx-3">{{ __('buttons.accept')}}</a>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('buttons.cancel')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $(".card").hover(function() {
                $(this).addClass("animate__animated animate__pulse");
            }, function() {
                $(this).removeClass("animate__animated animate__pulse");
            });
        });
    </script>

    <script>
        function getOrdersByDate() {
            const formData = new FormData();
            const csrfToken = $('meta[name="csrf-token"]').attr("content");
            const search = $("#search-date").val();
            const ordersList = $("#orders-list");
            const techniciansContainer = $("#accordion-technicians");
            const technicians = techniciansContainer.data("technicians");
            var url = $("#search-date-url").val();
            var html = '';
            formData.append("date", search);
            ordersList.empty();
            techniciansContainer.empty();
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function(response) {
                    const ordersFound = response.orders;
                    const technicianSelected = response.technicianSelected;
                    // console.log(ordersFound);
                    // console.log(technicianSelected);
                    // console.log(ordersFound.length);
                    // console.log(technicianSelected.length);
                    if (ordersFound.length === 0) {
                        ordersList.append('<li class="list-group-item text-danger">No se encontraron órdenes para el rango de fechas proporcionado.</li>');
                    } else {

                        if (technicianSelected.length > 0) {
                            let accordionHtml = `
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            Técnicos 
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion-technicians">
                                        <div class="accordion-body">
                                            <ul class="list-group" id="technician-list">
                                                <li class="list-group-item bg-dark text-white">Reemplazar los técnicos:</li>
                            `;

                            // Agregar técnicos al acordeón
                            technicians.forEach(technician => {
                                const isChecked = technicianSelected.includes(technician.id) ? "checked" : "";
                                accordionHtml += `
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input class="technician form-check-input me-1" type="checkbox"
                                                value="${technician.id}" id="technician-${technician.id}" ${isChecked} />
                                            <label class="form-check-label" for="technician-${technician.id}">
                                                ${technician.name}
                                            </label>
                                        </div>
                                    </li>
                                `;
                            });

                            // Cerrar acordeón
                            accordionHtml += `
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            `;
                            // Agregar acordeón al contenedor
                            techniciansContainer.append(accordionHtml);
                        } else {
                            techniciansContainer.append('<div class="alert alert-info">No hay técnicos para mostrar.</div>');
                        }

                        // Agregar cada orden encontrada a la lista
                        ordersFound.forEach(order => {
                            ordersList.append(`
                                <li class="list-group-item" value="${order.id}">
                                    <strong>ID:</strong> ${order.id}  - 
                                    <strong>Fecha:</strong> ${order.programmed_date}
                                </li>
                            `);
                        });
                    }
                },
                error: function(error) {
                    console.error(error);
                },
            });
        }

        document.getElementById("replaceForm").addEventListener("submit", function (event) {
            // Obtener los IDs de las órdenes (simples elementos de la lista)
            const orderIds = [];
            document.querySelectorAll("#orders-list .list-group-item").forEach(item => {
                const id = item.getAttribute("value"); // Obtiene el valor del atributo "value"
                if (id) {
                    orderIds.push(parseInt(id)); // Convierte a entero y lo agrega al array
                }
            });

            console.log(orderIds);
            // Obtener los IDs de los técnicos seleccionados (checkboxes marcados)
            const technicianIds = [];
            document.querySelectorAll(".technician:checked").forEach(checkbox => {
                technicianIds.push(parseInt(checkbox.value));
            });

            // Actualizar los valores de los campos ocultos
            document.getElementById("orderIds").value = JSON.stringify(orderIds);
            document.getElementById("technicianIds").value = JSON.stringify(technicianIds);
        });

    </script>

    <script>
        $(function() {
            $('#search-date').daterangepicker({
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
                alwaysShowCalendars: true,
                autoUpdateInput: false,
            });
        });

        $('#search-date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });
    </script>

@endsection
