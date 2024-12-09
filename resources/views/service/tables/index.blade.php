<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col-1">#</th>
            <th scope="col-1">ID</th>
            <th class="col-4" scope="col">{{ __('service.data.name') }}</th>
            <th scope="col">{{ __('service.data.type') }}</th>
            <th scope="col">{{ __('service.data.prefix') }}</th>
            <th scope="col">{{ __('service.data.cost') }} ($)</th>
            <th scope="col">{{ __('service.data.has_pests') }}</th>
            <th scope="col">{{ __('service.data.has_application_methods') }}</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($services as $index => $service)
            <tr>
                <th scope="row">{{ ++$index }}</th>
                <th scope="row">{{ $service->id }}</th>
                <td>{{ $service->name }}</td>
                <td>
                    {{ $service->serviceType->name }}
                </td>
                <td>{{ $service->prefixType->name }}</td>
                <td>${{ $service->cost }}</td>
                <td class="fw-bold {{$service->has_pests ? 'text-success' : 'text-danger' }}">
                    {{ $service->has_pests ? 'Si' : 'No' }}
                </td>
                
                <td class="fw-bold {{$service->has_application_methods ? 'text-success' : 'text-danger' }}">
                    {{ $service->has_application_methods ? 'Si' : 'No' }}
                </td>
                <td>
                    <a href="{{ route('service.show', ['id' => $service->id, 'section' => 1]) }}"
                        class="btn btn-info btn-sm">
                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                    </a>
                    @can('write_service')
                        <a href="{{ route('service.edit', ['id' => $service->id]) }}" class="btn btn-secondary btn-sm mb-1">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                        <a href="{{ route('service.destroy', ['id' => $service->id]) }}" class="btn btn-danger btn-sm mb-1"
                            onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                            <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                        </a>
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
