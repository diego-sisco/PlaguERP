@php
    $offset = ($zones->currentPage() - 1) * $zones->perPage();
@endphp

<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col-1">#</th>
            <th scope="col-1">ID</th>
            <th scope="col"> Nombre
            </th>
            <th scope="col"> Tipo
            </th>
            <th scope="col-1"> M2
            </th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($zones as $index => $zone)
            <tr id="zone-{{ $zone->id }}">
                <th scope="row"> {{ $offset + $index + 1 }} </th>
                <td class="text-primary"> {{ $zone->id }} </td>
                <td> {{ $zone->name }} </td>

                <td> {{ $zone->zone_type_id ? $zone->zoneType->name : 'No aplica (N/A)' }} </td>
                <td> {{ $zone->m2 }} </td>
                <td>
                    @can('write_order')
                        <button type="button" class="btn btn-secondary btn-sm" data-area="{{ $zone }}"
                            onclick="setData(this)" data-bs-toggle="modal" data-bs-target="#areaModal">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </button>
                        <a href="{{ Route('area.destroy', ['id' => $zone->id]) }}" class="btn btn-danger btn-sm"
                            onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                            <i class="bi bi-x-lg"></i> {{ __('buttons.delete') }}
                        </a>
                    @endcan
                </td>
            </tr>
        @empty
            <td colspan="6">No hay zonas por el momento.</td>
        @endforelse
    </tbody>
</table>
