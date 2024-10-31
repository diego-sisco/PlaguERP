@if(isset($orders) && empty($orders))
<div class="alert alert-danger alert-dismissible" role="alert">
    No se encontraron coincidencias
    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close"
    ></button>
</div>
@endif

<table class="table table-bordered text-center caption-top">
    <caption class="border bg-secondary-subtle p-2 fw-bold text-dark">
        {{ __('order.navbar.new_reports') }}
    </caption>
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Fecha</th>
            <th scope="col">Hora</th>
            <th scope="col">Localidad</th>
            <th scope="col">Línea de negocio</th>
            <th scope="col">Sede</th>
            <th scope="col">Técnicos</th>
            <th scope="col">No. Contrato</th>
            <th scope="col">Servicios</th>
            <th class="col" scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($orders)) @foreach ($orders as $order)
        <tr>
            <th scope="row">{{ $order->id }}</th>
            <td>
                {{
                Carbon\Carbon::parse($order->programmed_date)->format('d-m-Y')
                }}
            </td>
            <td>{{ $order->start_time }}</td>
            <td>{{ $order->customer->city }}</td>
            <td>
                @foreach ($order->services as $service) {{
                $service->businessLine->name }} @endforeach
            </td>
            <td>{{ $order->customer->name }}</td>
            <td>
                @foreach ($order->technicians as $technician) {{
                $technician->user->name }} @endforeach
            </td>
            <td>{{ $order->contract->id ?? 'S/N' }}</td>
            <td>
                @foreach ($order->services as $service) {{ $service->name }}
                @endforeach
            </td>

            <td>
                <button class="btn btn-info btn-sm" onclick="#!">
                    <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                </button>

                <button class="btn btn-success btn-sm" onclick="#!">
                    <i class="bi bi-download"></i> {{ __('buttons.download') }}
                </button>
            </td>
        </tr>
        @endforeach @endif
    </tbody>
</table>
