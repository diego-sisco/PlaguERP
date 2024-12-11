@php
    $offset = ($contracts->currentPage() - 1) * $contracts->perPage();
@endphp

<table class="table table-bordered text-center">
    <thead>
            <tr>
                <th scope="col-1">#</th>
                <th scope="col-1">ID</th>
                <th scope="col"> Inicia en
                </th>
                <th scope="col"> Termina en
                </th>
                <th scope="col-1"> Tecnicos
                </th>
                <th scope="col-1"> Estado
                </th>
                <th scope="col">{{ __('buttons.actions') }}</th>
            </tr>
    </thead>
    <tbody>
        @forelse ($contracts as $index => $contract)
            <tr id="contract-{{$contract->id}}">
                <th scope="row">{{ $offset + $index + 1 }}</th>
                <td class="text-primary"> {{$contract->id}} </td>
                <td> {{$contract->startdate}} </td>
                <td> {{$contract->enddate}} </td>
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
                    @can('write_order')
                        <a href="{{ route('contract.edit', ['id' => $contract->id]) }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                        <a href="{{ route('contract.destroy', ['id' => $contract->id]) }}" class="btn btn-danger btn-sm"
                            onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                            <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                        </a>
                    @endcan
                </td>
            </tr>
        @empty
            <td colspan="7">No hay contratos por el momento.</td>
        @endforelse
    </tbody>
</table>
