<table class="table-static table text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('pagination.branch_table.name') }}</th>
            <th scope="col">{{ __('pagination.branch_table.address') }}</th>
            <th scope="col">{{ __('pagination.branch_table.phone') }}</th>
            <th scope="col">{{ __('pagination.branch_table.city') }}</th>
            <th scope="col">{{ __('pagination.branch_table.license_number') }}</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($branches as $branch)
            <tr>
                <th scope="row">{{ $branch->id }}</th>
                <td>{{ $branch->name }}</td>
                <td>{{ $branch->address }}</td>
                <td>{{ $branch->phone }}</td>
                <td>{{ $branch->city }}</td>
                <td>{{ $branch->license_number }}</td>
                <td>
                    <a href="{{ route('branch.show', ['id' => $branch->id, 'section' => 1]) }}"
                        class="btn btn-info btn-sm">
                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                    </a>
                    <a href="{{ route('branch.edit', ['id' => $branch->id, 'section' => 1]) }}"
                        class="btn btn-secondary btn-sm"><i class="bi bi-pencil-square"></i>
                        {{ __('buttons.edit') }}</a>
                    <a href="{{ route('branch.destroy', ['id' => $branch->id]) }}" class="btn btn-danger btn-sm"
                        onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')"><i class="bi bi-trash-fill"></i>
                        {{ __('buttons.delete') }}</a>
                    {{-- @if ($branch->status_id == 3)
                    <a href="{{ route('branch.restore', ['id' => $branch->id]) }}" class="btn btn-success btn-sm"><i
                            class="bi bi-arrow-clockwise"></i> {{ __('buttons.restore') }}</a>
                @else
                    <a href="{{ route('branch.destroy', ['id' => $branch->id]) }}" class="btn btn-danger btn-sm"
                        onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')"><i class="bi bi-x-lg"></i>
                        {{ __('buttons.delete') }}</a>
                @endif --}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
