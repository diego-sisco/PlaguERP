<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('customer.data.name') }}</th>
            <th scope="col">{{ __('customer.data.phone') }}</th>
            <th scope="col">{{ __('customer.data.email') }}</th>
            <th scope="col">{{ __('customer.data.reason') }}</th>
            <th scope="col">{{ __('customer.data.tracking_at') }}</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leads as $lead)
            <tr>
                <th scope="row">{{ $lead->id }}</th>
                <td> {{ $lead->name }} </td>
                <td> {{ $lead->phone }} </td>
                <td>
                    {{ $lead->email ?? 'S/N' }}
                </td>
                <td> {{ $lead->reason ?? 'S/N' }} </td>
                <td> {{ $lead->tracking_at ?? 'S/N' }} </td>
                <td>
                    <a class="btn btn-info btn-sm"
                        href="{{ route('customer.show', ['id' => $lead->id, 'type' => 0, 'section' => 1]) }}">
                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                    </a>
                    <a class="btn btn-secondary btn-sm"
                        href="{{ route('customer.edit', ['id' => $lead->id, 'type' => 0, 'section' => 1]) }}">
                        <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                    </a>
                    <a class="btn btn-success btn-sm"
                        href="{{route('customer.tracking', ['id' => $lead->id])}}"
                        onclick="return confirm('{{ __('messages.are_you_sure_tracking') }}')">
                        <i class="bi bi-person-fill-check"></i> {{ __('buttons.tracking') }}
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
