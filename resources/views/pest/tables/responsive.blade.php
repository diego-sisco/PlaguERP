<table class="table-responsive table table-striped text-start">
    <tbody>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('pagination.pest_catalog.img')}}</th>
                @if (count($pests) < 0)
                    <td><img src="{{ asset($pests[$i]->image) }}" style="width: 60px; height: 60px;" alt="Miniatura de imagen"></td>
                @endif
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('pagination.pest_catalog.nom')}}</th>
                @if (count($pests) < 0)
                    <td>{{$pests[$i]->name}}</td>
                @endif
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('pagination.pest_catalog.pcode')}}</th>
                @if (count($pests) < 0)
                    <td>{{$pests[$i]->pest_code}}</td>
                @endif
                
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('pagination.pest_catalog.categ')}}</th>
                @if (count($pests) < 0)
                    @foreach ($categs as $categ)
                        @if ($pests[$i]->pest_category_id == $categ->id)
                            <td>{{ $categ->category}}</td>
                        @endif
                    @endforeach
                @endif
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{__('pagination.pest_catalog.desc')}}</th>
                @if (count($pests) < 0)
                    <td>{{$pests[$i]->description}}</td>
                @endif
            </tr>
        </tr>
        <tr class="table-secondary">
            <tr>
                <th scope="row">{{ __('buttons.actions') }}</th>
                @if (count($pests) < 0)
                    <td>
                        <a href="{{ route('pest.show', $pests[$i]->id)}}" class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i></a>
                        <a href="{{ route('pest.edit', $pests[$i]->id)}}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i></a>
                        <a href="{{ route('pest.destroy', $pests[$i]->id)}}" class="btn btn-danger btn-sm"><i class="bi bi-x"></i></a>
                    </td>
                @endif
            </tr>
        </tr>
    </tbody>
</table>