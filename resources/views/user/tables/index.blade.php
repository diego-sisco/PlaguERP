<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('user.data.name') }}</th>
            <th scope="col">{{ __('user.data.email') }}</th>
            @if ($type == 1)
                <th scope="col">{{ __('user.data.phone') }}</th>
                <th scope="col">{{ __('user.data.role') }}</th>
                <th scope="col">{{ __('user.data.department') }}</th>
            @endif
            <th scope="col">{{ __('user.data.status') }}</th>
            <th scope="col">{{ __('buttons.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <th scope="row"> {{ $user->id }} </th>
                <td> {{ $user->name }} </td>
                <td> {{ $user->email }} </td>
                @if ($type == 1)
                    <td> {{ $user->roleData->phone ?? 'S/N' }} </td>
                    <td> {{ $user->simpleRole->name }} </td>
                    <td> {{ $user->workDepartment->name ?? 'S/N' }} </td>
                @endif
                <td class="fw-bold {{ $user->status->id == 2 ? 'text-success' : ($user->status->id == 3 ? 'text-danger' : 'text-warning') }}">
                    {{ $user->status->name }} </td>
                <td>
                    @if ($type == 1)
                        <a href="{{ route('user.show', ['id' => $user->id, 'section' => 1]) }}"
                            class="btn btn-info btn-sm">
                            <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                        </a>
                    @endif
                    @can('write_user')
                        <a href="{{ route('user.edit', ['id' => $user->id, 'section' => 1]) }}"
                            class="btn btn-secondary btn-sm">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
