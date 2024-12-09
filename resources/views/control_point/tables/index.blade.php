<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col-1">#</th>
            <th scope="col-1">ID</th>
            <th scope="col-1">{{ __('product.product-data.color') }} </th>
            <th scope="col-2">{{ __('product.product-data.name') }} </th>
            <th scope="col">Código</th>
            <th scope="col-2">{{ __('product.product-data.line_b') }} </th>
            <th scope="col-2">{{ __('product.product-data.porp') }}</th>
            <th scope="col-1"> Preguntas asociadas </th>
            <th scope="col-2">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($points as $index => $point)
            <tr>
                <th scope="row">{{ ++$index }}</th>
                <td>{{ $point->id }}</td>
                <td class="d-flex justify-content-center"> <span class="rounded w-75"
                        style="background-color: {{ $point->color }}; height:30px; "></span> </td>
                <td>{{ $point->name }}</td>
                <td class="fw-bold text-primary">{{ $point->code }}</td>
                <td>{{ $point->product->lineBusiness->name  ?? 'S/A'}}</td>
                <td>{{ $point->product->purpose->type ?? 'S/A'}}</td>
                <td>{{ count($point->questions) }}</td>
                <td>
                    <a href="{{ route('point.show', ['id' => $point->id, 'section' => 1]) }}"
                        class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i>
                        {{ __('buttons.show') }}</a>

                    <a href="{{ route('point.edit', ['id' => $point->id, 'section' => 1]) }}"
                        class="btn btn-secondary btn-sm"><i class="bi bi-pencil-square"></i>
                        {{ __('buttons.edit') }}</i></a>

                    <a href="{{ route('point.destroy', ['id' => $point->id]) }}" class="btn btn-danger btn-sm"
                        onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')"><i class="bi bi-trash-fill"></i>
                        Eliminar </a>

                </td>
            </tr>
        @endforeach
    </tbody>
</table>
