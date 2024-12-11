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
                <th scope="row"> {{++$index}} </th>
                <td> {{ $zone->id }} </td>
                <td> {{ $zone->name }} </td>

                <td> {{$zone->zone_type_id ? $zone->zoneType->name : "Sin Tipo"}}  </td>
                <td> {{ $zone->m2}} </td>
                <td> 
                    @can('write_order')
                        <a class="btn btn-secondary btn-sm" href="">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                    @endcan
                </td>
            </tr>
        @empty
            <td colspan="6">No hay zonas por el momento.</td>
        @endforelse
    </tbody>
</table>
