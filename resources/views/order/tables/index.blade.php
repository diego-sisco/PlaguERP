@php
    $offset = ($orders->currentPage() - 1) * $orders->perPage();
@endphp

<table class="table table-bordered text-center">
    <thead>
        <form method="GET" action="{{ route('order.search') }}">
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID</th>
                <th scope="col">
                    {{ __('order.data.customer') }}
                    @if (!isset($customer))
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="search-customer"
                                placeholder="Filtrar por cliente" name="customer"">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i></button>
                        </div>
                    @endif
                </th>
                <th scope="col">{{ __('order.data.start_time') }}
                    <div class="input-group input-group-sm">
                        <input type="time" class="form-control" id="search-time" name="time"
                            placeholder="Filtrar por hora programada">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i></button>
                    </div>
                </th>
                <th scope="col">{{ __('order.data.programmed_date') }}
                    <div class="input-group input-group-sm" method="GET" action="{{ route('planning.activities') }}">
                        @csrf
                        <input type="text" class="form-control" id="search-date" name="date" value="" />
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </th>
                <th class="col-4" scope="col">{{ __('order.data.service') }}
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search-service" name="service"
                            placeholder="Filtrar por servicio">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i></button>
                    </div>
                </th>
                <th scope="col">{{ __('order.data.status') }}
                    <div class="input-group input-group-sm">
                        <select class="form-select form-select-sm" id="search-status" name="status">
                            @foreach ($order_status as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i></button>
                    </div>
                </th>
                <th scope="col">{{ __('buttons.actions') }}</th>
                
            </tr>
        </form>
    </thead>
    <tbody>
        @foreach ($orders as $index => $order)
            <tr id="order-{{ $order->id }}">
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
                        btn-info btn-sm mb-2"
                        href="{{ route('order.show', ['id' => $order->id, 'section' => 1]) }}">
                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                    </a>
                    @can('write_order')
                        <button class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal"
                            data-bs-target="#signatureModal" onclick="setSignatureId({{ $order->id }})">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.signature') }}
                        </button>
                        <a class="btn btn-secondary btn-sm mb-2" href="{{ route('order.edit', ['id' => $order->id]) }}">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                    @endcan

                    @can('write_order')
                        @if ($order->status->id != 6)
                            <a href="{{ route('order.destroy', ['id' => $order->id]) }}" class="btn btn-danger btn-sm mb-2"
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

@include('order.modals.signature')