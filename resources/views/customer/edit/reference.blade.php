<div class="col-12 mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRefModal"> Crear referencia</button>
</div>
<table class="table text-center table-bordered">
    <thead>
        <tr>
            <th scope="col-1">#</th>
            <th scope="col-2">Nombre</th>
            <th scope="col-2">Tipo de referencia</th>
            <th scope="col-2">Correo electrónico</th>
            <th scope="col-2">Teléfono</th>
            <th scope="col-1">Departamento</th>
            <th scope="col-2">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($customer->references))
            @foreach ($customer->references as $reference)
                <tr>
                    <th scope="row">{{ $reference->id }}</th>
                    <td class="align-middle">{{ $reference->name }}</td>
                    <td class="align-middle">{{ $reference->referenceType->name }}</td>
                    <td class="align-middle">{{ $reference->email }}</td>
                    <td class="align-middle">{{ $reference->phone }}</td>
                    <td class="align-middle">{{ $reference->department }}</td>
                    <td class="align-middle">
                        <a href="{{ route('reference.show', ['id' => $reference->id, 'type' => $type]) }}"
                            class="btn btn-info btn-sm">
                            <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                        </a>
                        <a href="{{ Route('reference.edit', ['id' => $reference->id, 'type' => $type]) }}"
                            class="btn btn-secondary btn-sm btn-sm">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>