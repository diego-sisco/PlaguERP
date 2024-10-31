<table class="table-responsive table table-striped text-start">
    <tbody>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('product.product-data.photo')}}</th>
                @if (count($products) < 0)
                    <td><img src="{{ asset($products[$i]->photo) }}" style="width: 60px; height: 60px;" alt="Miniatura de imagen"></td>
                @endif
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('product.product-data.name')}}</th>
                @if (count($products) < 0)
                    <td>{{$products[$i]->name}}</td>
                @endif
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('product.product-data.stat')}}</th>
                @if (count($products) < 0)
                    @if ($products[$i]->status == 1)
                        <i class="bi bi-check2" style="color: green"></i>
                    @else
                        <i class="bi bi-x" style="color: red"></i>
                    @endif
                @endif
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('product.product-data.line_b')}}</th>
                @if (count($products) < 0)
                    @foreach ($lineBs as $lineb)
                        @if ($products[$i]->linebuss_id == $lineb->id)
                            {{ $lineb->name }}
                        @endif
                    @endforeach
                @endif
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('product.product-data.obsolete')}}</th>
                @if (count($products) < 0)
                    @if ($products[$i]->obsolete == 1)
                        <i class="bi bi-x" style="color: red;"></i>
                    @else
                        <i class="bi bi-check2" style="color: green;"></i>
                    @endif
                @endif
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('product.product-data.obsolete')}}</th>
                @if (count($products) < 0)
                    @if ($products[$i]->basic == 1)
                        <i class="bi bi-check2" style="color: green;"></i>
                    @else
                        <i class="bi bi-x" style="color: red;"></i>
                    @endif
                @endif
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{ __('buttons.actions') }}</th>
                @if (count($products) < 0)
                    <td>
                        <a href="{{ route('pesticide.show', $products[$i]->id)}}" class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i></a>
                        <a href="{{ route('pesticide.edit', $products[$i]->id)}}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i></a>
                    </td>
                @endif
            </tr>
        </tr>
    </tbody>
</table>