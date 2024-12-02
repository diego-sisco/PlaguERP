<table class="table table-bordered text-center caption-top">
    <caption class="border bg-secondary-subtle p-2 fw-bold text-dark">
        Reportes
    </caption>
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">No</th>
            <th scope="col">Fecha</th>
            <th scope="col">Hora</th>
            <th scope="col">Localidad</th>
            <th scope="col">Línea de negocio</th>
            <th scope="col">Sede</th>
            <th scope="col">Técnico</th>
            <th scope="col">Servicio(s)</th>
            <th scope="col">Firmado por</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($orders as $index => $order)
        @php
            $technician = $order->technicians()->first();
        @endphp
        <tr>
            <th scope="row">{{ $index + 1 }}</th>
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
                {{$technician->user->name ?? 'S/A'}}
            </td>
            <td>
                {{ implode(', ', $order->services->pluck('name')->toArray()) }}
            </td>
            <td class="fw-bold {{ $order->signature_name ? 'text-success' : 'text-danger' }}" id="order{{ $order->id }}-signature-name">{{ $order->signature_name ?? 'Sin firma' }}</td>
            <td>
                <button type="button" class="btn btn-warning btn-sm mb-1" onclick="openModal({{$order->id}})">
                    <i class="bi bi-pencil-fill"></i> {{ __('buttons.signature') }}
                </button>
                <a href="{{ route('report.print', ['orderId' => $order->id]) }}" class="btn btn-info btn-sm mb-1">
                    <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                </a>
            </td>
        </tr>
        @empty
            <tr>
                <td class="fw-bold text-danger" colspan="10">Sin coincidencias</td>
            </tr>
        @endforelse
    </tbody>
</table>
