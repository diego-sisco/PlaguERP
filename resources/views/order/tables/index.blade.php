@php
    $offset = ($orders->currentPage() - 1) * $orders->perPage();
@endphp



<table class="table table-bordered text-center">
    <form method="GET" action="{{ route('order.search') }}">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID</th>
                <th scope="col">
                    {{ __('order.data.customer') }}
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search-customer"
                            placeholder="Filtrar por cliente" aria-label="Recipient's username"
                            aria-describedby="button-addon2" name="searchCustomer">
                        <button class="btn btn-primary" type="submit" id="button-addon2">
                            <i class="bi bi-search"></i></button>
                    </div>
                </th>
                <th scope="col">{{ __('order.data.start_time') }}
                    <div class="input-group input-group-sm">
                        <input type="time" class="form-control" id="search-time"
                            placeholder="Filtrar por hora programada" aria-label="Recipient's time"
                            aria-describedby="button-addon2" name="start_time">
                        <button class="btn btn-primary" type="submit" id="button-addon2">
                            <i class="bi bi-search"></i></button>
                    </div>
                </th>
                <th scope="col">{{ __('order.data.programmed_date') }}
                    <div class="input-group input-group-sm">
                        <input type="date" class="form-control" id="search-date"
                            placeholder="Filtrar por fecha programada" aria-label="Recipient's username"
                            aria-describedby="button-addon2" name="programmed_date">
                        <button class="btn btn-primary" type="submit" id="button-addon2">
                            <i class="bi bi-search"></i></button>
                    </div>
                </th>
                <th scope="col">{{ __('order.data.service') }}
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search-service"
                            placeholder="Filtrar por servicio" aria-label="Recipient's service"
                            aria-describedby="button-addon2" name="searchService">
                        <button class="btn btn-primary" type="submit" id="button-addon2">
                            <i class="bi bi-search"></i></button>
                    </div>
                </th>
                <th scope="col">{{ __('order.data.status') }}
                    <div class="input-group input-group-sm">
                            <select class="form-select form-select-sm" name="searchStatus">
                                <option value="">Filtrar por estado</option>
                                <option value="Pendiente">Pendientes</option>
                                <option value="Aceptada">Aceptadas</option>
                                <option value="Finalizada">Finalizadas</option>
                                <option value="Verificada" >Verificadas</option>
                                <option value="Aprobada">Aprobadas</option>
                                <option value="Cancelada">Canceladas</option>
                            </select>
                        <button class="btn btn-primary" type="submit" id="button-addon2">
                            <i class="bi bi-search"></i></button>
                    </div>
                </th>
                <th scope="col">{{ __('buttons.actions') }}</th>
            </tr>
        </thead>
    </form>
    <tbody>
        @foreach ($orders as $index => $order)
            <tr id="order-{{$order->id}}">
                <th scope="row">{{ $offset + $index + 1 }}</th>
                <td>{{ $order->id }}</td>
                <td>{{ $order->customer->name }}</td>
                <td>{{ $order->start_time }}</td>
                <td>{{ $order->programmed_date }}</td>
                <td>
                    @foreach ($order->services as $service)
                        {{ $service->name }} <br>
                    @endforeach
                </td>
                <td
                    class="fw-bold 
                    {{ $order->status_id == 1
                        ? 'text-warning'
                        : ($order->status_id == 2 || $order->status_id == 3
                            ? 'text-primary'
                            : ($order->status_id == 4 || $order->status_id == 5
                                ? 'text-success'
                                : 'text-danger')) }}">
                                    {{ $order->status->name }}
                </td>
                <td>
                    <a class="btn
                        btn-info btn-sm"
                        href="{{ route('order.show', ['id' => $order->id, 'section' => 1]) }}">
                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                    </a>
                    @can('write_order')
                        <a class="btn btn-secondary btn-sm" href="{{ route('order.edit', ['id' => $order->id]) }}">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                    @endcan
                    @can('write_order')
                        @if ($order->status->id != 6)
                            <a href="{{ route('order.destroy', ['id' => $order->id]) }}" class="btn btn-danger btn-sm"
                                onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                                <i class="bi bi-x-lg"></i> {{ __('buttons.cancel') }}
                            </a>
                        @endif
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
