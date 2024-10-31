@php
    session()->put('product_type', 1);
    session()->save();
@endphp

<table class="table table-bordered text-center align-middle">
    <thead>
        <tr>
            <th scope="col">Imagen</th>
            <th scope="col">Nombre</th>
            <th scope="col">Fabricante</th>
            <th scope="col">Línea de negocio</th>
            <th scope="col">Presentación</th>
            <th scope="col">No Registro</th>
            <th scope="col">Ingrediente activo</th>
            <th scope="col">Dosificación</th>
            <th scope="col">Obsoleto</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if ($products)
            @forelse ($products as $product)
                <tr>
                    @if ($product->image_path)
                        <td><img src="{{ route('image.show', ['filename' => $product->image_path]) }}" style="width: 60px; height: 60px;"
                                alt="Miniatura de imagen"></td>
                    @else
                        <td><i class="bi bi-image"></i></td>
                    @endif
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->bussiness_name ?? 'S/A' }}</td>
                    <td> {{ $product->lineBusiness->name ?? 'S/A' }} </td>
                    <td>{{ $product->presentation->name ?? 'S/A' }}</td>
                    <td>{{ $product->register_number ?? 'S/A' }}</td>
                    <td>{{ $product->active_ingredient ?? 'S/A' }}</td>
                    <td>{{ $product->per_active_ingredient ?? 'S/A' }}</td>
                    <td class="fw-bold {{ $product->is_obsolete ? 'text-danger' : 'text-success' }}">{{ $product->is_obsolete ? 'Si' : 'No' }}</td>
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
                    <td class="text-danger fw-bold" colspan="9">Sin productos</td>
                </tr>
            @endforelse
        @endif
    </tbody>
</table>
