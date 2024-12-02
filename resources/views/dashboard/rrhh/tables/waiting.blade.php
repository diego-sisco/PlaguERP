<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('user.data.name') }}</th>
            <th scope="col">{{ __('user.data.email') }}</th>
            <th scope="col">{{ __('user.data.phone') }}</th>
            <th scope="col">{{ __('user.data.role') }}</th>
            <th scope="col">{{ __('user.data.department') }}</th>
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
                <td> {{ isset($user->roleData->phone) ? $user->roleData->phone : 'S/N' }} </td>
                <td> {{ $user->simpleRole->name }} </td>
                <td> {{ $user->workDepartment->name }} </td>
                <td class="fw-bold {{ $user->status->id == 2 ? 'text-success' : 'text-danger' }}"> {{ $user->status->name }} </td>
                <td>
                    <a href="{{ route('user.show', ['id' => $user->id, 'section' => 1]) }}" class="btn btn-info btn-sm">
                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                    </a>
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
