<table class="table text-center table-bordered">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"> {{ __('customer.data.name') }} </th>
            <th scope="col"> {{ __('customer.data.phone') }} </th>
            <th scope="col"> {{ __('customer.data.email') }} </th>
            @if ($type != 0)
                <th scope="col"> {{ __('customer.data.type') }}</th>
                <th scope="col">{{ __('customer.data.origin') }}</th>
            @else
                <th class="col-3" scope="col">{{ __('customer.data.reason') }}</th>
            @endif
            <th scope="col"> {{ __('customer.data.created_at') }}</th>
            <th scope="col"> {{ __('buttons.actions') }} </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
            <tr>
                <th scope="row">{{ $customer->id }}</th>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->email }}</td>
                @if ($type != 0)
                    <td>{{ $customer->serviceType->name }}</td>
                    <td>{{ isset($customer->matrix->name) ? $customer->matrix->name . ' (' . $customer->matrix->id . ')' : 'Matriz' }}
                    </td>
                @else
                    <td>{{ $customer->reason ?? 'S/N' }}</td>
                @endif
                <td>
                    {{ Carbon\Carbon::parse($customer->created_at, 'UTC')->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') }}
                    {{-- $customer->created_at --}}
                </td>
                <td>
                    <a href="{{ route('customer.show', ['id' => $customer->id, 'type' => $type, 'section' => 1]) }}"
                        class="btn btn-info btn-sm">
                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                    </a>
                    @can('write_customer')
                        <a href="{{ route('customer.edit', ['id' => $customer->id, 'type' => $type, 'section' => 1]) }}"
                            class="btn btn-secondary btn-sm">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                        @if ($type == 0)
                            <a href="{{ route('customer.convert', ['id' => $customer->id]) }}"
                                class="btn btn-success btn-sm"
                                onclick="return confirm('{{ __('messages.lead_to_customer') }}')">
                                <i class="bi bi-person-fill-add"></i> {{ __('buttons.convert') }}
                            </a>
                        @endif
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
