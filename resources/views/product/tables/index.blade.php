<table class="table table-bordered text-center align-middle">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">ID</th>
            <th scope="col">Imagen</th>
            <th class="col-3" scope="col">Nombre</th>
            <th class="col-1" scope="col">Fabricante/distribuidor</th>
            <th scope="col">Línea de negocio</th>
            <th scope="col">Presentación</th>
            <th scope="col">No Registro</th>
            <th scope="col">Ingrediente activo</th>
            <th scope="col">Dosificación</th>
            <th scope="col">Esta obsoleto?</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $index => $product)
            <tr>
                <th scope="row">{{ ++$index }}</td>

                <td>{{ $product->id }}</td>
                @if ($product->image_path)
                    <td><img src="{{ route('image.show', ['filename' => $product->image_path]) }}"
                            style="width: 60px; height: 60px;" alt="Miniatura de imagen"></td>
                @else
                    <td><i class="bi bi-image"></i></td>
                @endif
                <td>{{ $product->name }}</td>
                <td>{{ $product->manufacturer ?? 'S/A' }}</td>
                <td> {{ $product->lineBusiness->name ?? 'S/A' }} </td>
                <td>{{ $product->presentation->name ?? 'S/A' }}</td>
                <td>{{ $product->register_number ?? 'S/A' }}</td>
                <td>{{ $product->active_ingredient ?? 'S/A' }}</td>
                <td>{{ $product->per_active_ingredient ?? 'S/A' }}</td>
                <td class="fw-bold {{ $product->is_obsolete ? 'text-danger' : 'text-success' }}">
                    {{ $product->is_obsolete ? 'Si' : 'No' }}</td>
                <td>
                    <a href="{{ route('product.show', ['id' => $product->id, 'section' => 1]) }}"
                        class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}</a>
                    @can('write_product')
                        <a href="{{ route('product.edit', ['id' => $product->id, 'section' => 1]) }}"
                            class="btn btn-secondary btn-sm"><i class="bi bi-pencil-square"></i>
                            {{ __('buttons.edit') }}</a>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-danger fw-bold" colspan="10">Sin productos</td>
            </tr>
        @endforelse
    </tbody>
</table>
