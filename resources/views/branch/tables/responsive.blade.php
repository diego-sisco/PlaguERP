<table class="table-responsive table text-start">
    <tbody>
        @if($users[$i]->status_id == 3)
        <tr class="table-secondary">
        @else
        <tr>
        @endif
            <th scope="row">#</th>
            <td>{{ $users[$i]->id }}</td>
        </tr>
        @if($users[$i]->status_id == 3)
        <tr class="table-secondary">
        @else
        <tr>
        @endif
            <th scope="row">{{ __('pagination.table.name') }}:</th>
            <td>{{ $users[$i]->name }}</td>
        </tr>
        @if($users[$i]->status_id == 3)
        <tr class="table-secondary">
        @else
        <tr>
        @endif
            <th scope="row">{{ __('pagination.table.email') }}:</th>
            <td>{{ $users[$i]->email }}</td>
        </tr>
        @if($users[$i]->status_id == 3)
        <tr class="table-secondary">
        @else
        <tr>
        @endif
            <th scope="row">{{ __('pagination.table.role') }}:</th>
            <td>
                @foreach ($roles as $role)
                @if ($role->id == $users[$i]->role_id)
                    {{ $role->name }}
                @endif
                @endforeach
            </td>
        </tr>
        @if($users[$i]->status_id == 3)
        <tr class="table-secondary">
        @else
        <tr>
        @endif
            <th scope="row">{{ __('pagination.table.department') }}:</th>
            <td>
                @foreach ($work_departments as $work_department)
                @if ($work_department->id == $users[$i]->work_department_id)
                    {{ $work_department->name }}
                @endif
                @endforeach
            </td>
        </tr>
        @if($users[$i]->status_id == 3)
        <tr class="table-secondary">
        @else
        <tr>
        @endif
            <th scope="row">{{ __('pagination.table.status') }}:</th>
            <td>
                @foreach ($statuses as $status)
                @if ($status->id == $users[$i]->status_id)
                    {{ $status->name }}
                @endif
                @endforeach
            </td>
        </tr>
        @if($users[$i]->status_id == 3)
        <tr class="table-secondary">
        @else
        <tr>
        @endif
            <th scope="row">{{ __('pagination.table.functions') }}:</th>
            <td>
                <a href="{{ route('user.show', ['id' => $users[$i]->id])}}" class="btn btn-info btn-sm">
                    <i class="bi bi-eye-fill"></i>
                </a>
                <a href="{{ route('user.edit', ['id' => $users[$i]->id])}}" class="btn btn-primary btn-sm"><i
                        class="bi bi-pencil-square"></i></a>
                @if ($users[$i]->id != Auth::user()->id)
                    @if($users[$i]->status_id == 3)
                    <a href="{{ route('user.restore', ['id' => $users[$i]->id])}}" class="btn btn-success btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
                    @else
                    <a href="{{ route('user.destroy', ['id' => $users[$i]->id])}}" class="btn btn-danger btn-sm"><i class="bi bi-x"></i></a>
                    @endif
                @else
                    <a href="#" class="btn btn-danger btn-sm disabled" onclick="return confirm('{{ __('No puedes eliminar tu propio usuario!!') }}')"><i class="bi bi-x"></i></a>
                @endif
            </td>
        </tr>
    </tbody>
</table>