<div class="col-12 mb-3">
    <a href="{{ route('customer.create', ['id' => $customer->id, 'type' => 2]) }}"
        class="btn btn-primary"> Crear sede
    </a>
</div>
<table class="table text-center table-bordered">
    <thead>
        <tr>
            <th scope="col-1">#</th>
            <th scope="col-2">Nombre</th>
            <th scope="col-2">Dirección</th>
            <th scope="col-2">Teléfono</th>
            <th scope="col-2">Correo</th>
            <th scope="col-1">Estado</th>
            <th scope="col-2">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (!$customer->sedes->isEmpty())
            @foreach ($customer->sedes as $sede)
                <tr>
                    <th scope="row">{{ $sede->id }}</th>
                    <td class="align-middle">{{ $sede->name }}</td>
                    <td class="align-middle">{{ $sede->address }}</td>
                    <td class="align-middle">{{ $sede->phone }}</td>
                    <td class="align-middle">{{ $sede->email }}</td>
                    <td
                        class="align-middle {{ $sede->status == 1 ? 'text-success' : 'text-danger' }} fw-bold">
                        {{ $sede->status == 1 ? 'Activo' : 'Inactivo' }}
                    </td>
                    <td class="align-middle">
                        <a href="{{ route('customer.show', ['id' => $sede->id, 'type' => 2, 'section' => 1]) }}"
                            class="btn btn-info btn-sm">
                            <i class="bi bi-eye-fill"></i>
                            {{ __('buttons.show') }}
                        </a>
                        <a href="{{ route('customer.edit', ['id' => $sede->id, 'type' => 2, 'section' => 1]) }}"
                            class="btn btn-secondary btn-sm btn-sm">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>