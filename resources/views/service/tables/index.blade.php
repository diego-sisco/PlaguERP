<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('service.data.name') }}</th>
            <th scope="col">{{ __('service.data.type') }}</th>
            <th scope="col">{{ __('service.data.prefix') }}</th>
            <th scope="col">{{ __('service.data.cost') }} ($)</th>
            <th scope="col">{{ __('service.data.has_pests') }}</th>
            <th scope="col">{{ __('service.data.has_application_methods') }}</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($services as $service)
            <tr>
                <th scope="row">{{ $service->id }}</th>
                <td>{{ $service->name }}</td>
                <td>
                    {{ $service->serviceType->name }}
                </td>
                <td>{{ $service->prefixType->name }}</td>
                <td>${{ $service->cost }}</td>
                <td>
                    <i
                        class="bi {{ $service->has_pests ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                </td>
                <td>
                    <i
                        class="bi {{ $service->has_application_methods ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                </td>
                <td>
                    <a href="{{ route('service.show', ['id' => $service->id, 'section' => 1]) }}"
                        class="btn btn-info btn-sm">
                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                    </a>
                    @can('write_service')
                        <a href="{{ route('service.edit', ['id' => $service->id]) }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                        <a href="{{ route('service.destroy', ['id' => $service->id]) }}" class="btn btn-danger btn-sm"
                            onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                            <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                        </a>
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
