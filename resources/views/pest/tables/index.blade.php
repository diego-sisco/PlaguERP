<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('pest.data.name') }} </th>
            <th scope="col">{{ __('pest.data.code') }} </th>
            <th scope="col">{{ __('pest.data.category') }} </th>
            <th scope="col">{{ __('pest.data.description') }} </th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pests as $pest)
            <tr class="text-center">
                <td class="text-center">{{-- <img src="{{ asset($pest->image) }}" class="w-25"> --}} {{ $pest->id }} </td>
                <td class="text-center">{{ $pest->name }}</td>
                <td>{{ $pest->pest_code }}</td>
                <td>{{ $pest->pestCategory->category }}</td>
                <td>{{ $pest->description }}</td>
                <td>
                    <a href="{{ route('pest.show', ['id' => $pest->id]) }}" class="btn btn-info btn-sm"><i
                            class="bi bi-eye-fill"></i> {{ __('buttons.show') }} </a>
                    <a href="{{ route('pest.edit', ['id' => $pest->id]) }}" class="btn btn-secondary btn-sm"><i
                            class="bi bi-pencil-square"></i> {{ __('buttons.edit') }} </a>
                    <a href="{{ route('pest.destroy', ['id' => $pest->id]) }}" class="btn btn-danger btn-sm"
                        onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                        <i class="bi bi-x-lg"></i> {{ __('buttons.delete') }} </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
