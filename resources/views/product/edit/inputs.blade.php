<div class="col-12 mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
        data-bs-target="#inputModal">Crear insumo</button>
</div>
<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col-1">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Método de aplicación</th>
            <th scope="col">Metros cuadrados (m²)</th>
            <th scope="col">Costo ($)</th>
            <th scope="col"> {{ __('buttons.actions') }} </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inputs as $input)
            <tr>
                <th scope="row">{{ $input->id }}</th>
                <td class="align-middle">{{ $input->product->name }}</td>
                <td class="align-middle">
                    {{ $input->appMethod->name }}
                </td>
                <td class="align-middle">{{ $input->zone_m2 }}</td>
                <td class="align-middle">{{ $input->cost }}</td>
                <td class="align-middle">
                    <button type="button" class="btn btn-secondary btn-sm"
                        data-input="{{ $input }}" data-bs-toggle="modal"
                        data-bs-target="#editInputModal" onclick="setInput(this)">
                        <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                    </button>
                    <a href="{{ route('product.input.destroy', ['id' => $input->id]) }}" class="btn btn-danger btn-sm"
                        onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                        <i class="bi bi-x-lg"></i> {{ __('buttons.delete') }}
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>