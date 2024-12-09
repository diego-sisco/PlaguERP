<div class="col-12 mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#areaModal"
        onclick="resetForm()">Crear zona</button>
</div>
<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th scope="col-1">#</th>
                <th scope="col-1">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Tipo de zona</th>
                <th scope="col">Metros cuadrados (m²)</th>
                <th scope="col">Creado</th>
                <th scope="col"> {{ __('buttons.actions') }} </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customer->applicationAreas as $index => $area)
                <tr>
                    <th scope="row">{{ ++$index }}</th>
                    <td class="align-middle">{{ $area->id }}</td>
                    <td class="align-middle">{{ $area->name }}</td>
                    <td class="align-middle">
                        {{ $area->zoneType->name ?? 'N/A' }}
                    </td>
                    <td class="align-middle">{{ $area->m2 }}</td>
                    <td class="align-middle">{{ $area->created_at }}</td>
                    <td class="align-middle">
                        <button type="button" class="btn btn-secondary btn-sm" data-area="{{ $area }}"
                            onclick="setData(this)" data-bs-toggle="modal" data-bs-target="#areaModal">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </button>
                        <a href="{{ Route('area.destroy', ['id' => $area->id]) }}" class="btn btn-danger btn-sm"
                            onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                            <i class="bi bi-x-lg"></i> {{ __('buttons.delete') }}
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-danger fw-bold" colspan="6">
                        Sin zonas
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
