<input type="hidden" id="getTechnicians" value="{{ route('contract.getTechnicans') }}">

<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col-1">#</th>
            <th scope="col-1">Id</th>
            <th scope="col-2">{{ __('contract.data.customer') }}</th>
            <th scope="col-2">{{ __('contract.data.start_date') }}</th>
            <th scope="col-2">{{ __('contract.data.end_date') }}</th>
            <th scope="col-2">{{ __('contract.title.technicians') }}</th>
            <th scope="col-2">{{ __('contract.data.status') }}</th>
            <th scope="col-1">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($contracts as $index => $contract)
            <tr>
                <th scope="row">{{ ++$index }}</th>
                <td class="text-primary fw-bold">{{ $contract->id }}</th>
                <td> {{ $contract->customer->name }} </td>
                <td>{{ $contract->startdate }}</td>
                <td>{{ $contract->enddate }}</td>
                <td> {{ implode(', ', $contract->technicianNames()) }} </td>

                <td>
                    <span
                        class="fw-bold {{ $contract->status == 1 ? 'text-success' : ($contract->status == 0 ? 'text-danger' : 'text-warning') }}">
                        {{ $contract->status == 1 ? __('contract.status.active') : ($contract->status == 0 ? __('contract.status.finalized') : __('contract.status.to_finalize')) }}
                    </span>
                </td>
                <td>
                    <a class="btn btn-info btn-sm"
                        href="{{ route('contract.show', ['id' => $contract->id, 'section' => 1]) }}">
                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                    </a>
                    <a class="btn btn-dark btn-sm"
                        href="{{ route('rotation.index', ['contractId' => $contract->id]) }}">
                        <i class="bi bi-arrow-clockwise"></i> Plan de rotación
                    </a>
                    @can('write_order')
                        <a href="{{ route('contract.edit', ['id' => $contract->id]) }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                        <!--button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#technicianModal{{ $contract->id }}">
                                <i class="bi bi-person-fill-gear"></i> {{ __('contract.title.technicians') }}
                            </button>
                            <a href="#" class="btn btn-dark btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#fileModal{{ $contract->id }}"><i class="bi bi-file-earmark-pdf"></i>
                                    {{ __('contract.title.contract') }}</a-->
                        <a href="{{ route('contract.destroy', ['id' => $contract->id]) }}" class="btn btn-danger btn-sm"
                            onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                            <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                        </a>
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@foreach ($contracts as $contract)
    @include('contract.modals.technicians')
    @include('contract.modals.file')
@endforeach
