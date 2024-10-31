<div class="row p-3 m-0 border">
    <ul class="nav nav-underline">
        <li class="nav-item">
            <a class="nav-link {{ $is_active ? 'active' : '' }}" aria-current="page"
                href="{{ route('warehouse.index', ['is_active' => 1]) }}">Almacenes activos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $is_active ? '' : 'active' }}"
                href="{{ route('warehouse.index', ['is_active' => 0]) }}">Almacenes inactivos</a>
        </li>
    </ul>
</div>

<div class="p-3 mb-3">
    @if ($is_active)
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('warehouse.create') }}">
                <i class="bi bi-plus-lg fw-bold"></i> Crear almacen
            </a>
        </div>
    @endif

    @include('layouts.alert')

    <table class="table text-center table-bordered">
        <thead>
            <tr>
                <th scope="col"># (ID)</th>
                <th scope="col">Nombre</th>
                <th scope="col">Sede</th>
                <th scope="col">Activo</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($warehouses as $warehouse)
                <tr id="table-body">
                    <td>{{ $warehouse->id }}</td>
                    <td>{{ $warehouse->name }}</td>
                    <td>{{ $warehouse->branch->name }}</td>
                    <td class="{{ $warehouse->active ? 'text-success' : 'text-danger' }} fw-bold">
                        {{ $warehouse->active ? 'Sí' : 'No' }}</td>
                    <td>
                        <a href="{{ route('warehouse.show', ['id' => $warehouse->id]) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                        </a>
                        @if ($is_active)
                            <a href="{{ route('warehouse.edit', ['id' => $warehouse->id]) }}"
                                class="btn btn-secondary btn-sm">
                                <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                            </a>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#inputModal" data-warehouse="{{ json_encode($warehouse) }}" onclick="setData(this, {{ $warehouse->id }}, 'input')">
                                <i class="bi bi-box-arrow-in-down-right"></i> {{ __('buttons.input') }}
                            </button>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#outputModal" data-warehouse="{{ json_encode($warehouse) }}" onclick="setData(this, {{ $warehouse->id }}, 'output')">
                                <i class="bi bi-box-arrow-up-left"></i> {{ __('buttons.output') }}
                            </button>
                            <a href="{{ route('warehouse.movements', ['id' => $warehouse->id]) }}"
                                class="btn btn-primary btn-sm">
                                <i class="bi bi-arrow-left-right"></i> {{ __('buttons.movements') }}
                            </a>
                            <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#stockModal" onclick="setStock({{ $warehouse->id }})">
                                <i class="bi bi-boxes"></i> {{ __('buttons.stock') }}
                            </button>
                            <a href="{{ route('warehouse.destroy', ['id' => $warehouse->id]) }}"
                                class="btn btn-danger btn-sm">
                                <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-danger" colspan="5">No hay almacenes para mostrar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
