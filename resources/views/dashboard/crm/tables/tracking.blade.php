<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('customer.data.name') }}</th>
            <th scope="col">{{ __('customer.data.phone') }}</th>
            <th scope="col">{{ __('customer.data.email') }}</th>
            <th scope="col">{{ __('customer.data.tracking_at') }}</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($frecuencies as $frequency)
            <tr>
                <th scope="row">{{ $frequency->order->customer->id }}</th>
                <td> {{ $frequency->order->customer->name }} </td>
                <td> {{ $frequency->order->customer->phone }} </td>
                <td> {{ $frequency->order->customer->email }} </td>
                <td>
                    {{ Carbon\Carbon::parse($frequency->next_date, 'UTC')->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') }}
                </td>
                <td>
                    <a class="btn btn-info btn-sm"
                        href="{{ route('customer.show', ['id' => $frequency->order->customer->id, 'type' => $frequency->order->customer->general_sedes > 0 ? 2 : 1, 'section' => 1]) }}">
                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                    </a>
                    <a class="btn btn-secondary btn-sm"
                        href="{{ route('customer.edit', ['id' => $frequency->order->customer->id, 'type' => $frequency->order->customer->general_sedes > 0 ? 2 : 1, 'section' => 1]) }}">
                        <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
