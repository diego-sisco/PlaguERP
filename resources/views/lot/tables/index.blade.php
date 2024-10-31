<table class="table text-center table-bordered">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">No. Lote</th>
            <th scope="col">Almacen</th>
            <th scope="col">Producto</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Fecha de caducidad</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($lots as $lot)
            <tr>
                <td>{{ $lot->id }}</td>
                <td>{{ $lot->registration_number }}</td>
                <td>{{ $lot->warehouse->name }}</td>
                <td>{{ $lot->product->name }}</td>
                <td>{{ $lot->amount }}</td>
                <td>{{ $lot->expiration_date }}</td>
                <td>
                    <a href="{{ route('lot.edit', $lot->id) }}" class="btn btn-secondary btn-sm"><i
                            class="bi bi-pencil-square"></i> Editar</a>
                    <a href="{{ route('lot.destroy', ['id' => $lot->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                        <i class="bi bi-trash-fill"></i> Eliminar</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
