<form class="form static-form" method="POST" action="{{ route('user.update', ['id' => $user->id]) }}"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="password" value="{{ $user->nickname }}">

    <div class="row">
        <div class="col-3 mb-3">
            <label for="company" class="form-label is-required">{{ __('user.data.company') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " id="company" name="company_id" required>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ $company->id == $user->company_id ? 'selected' : '' }}>
                        {{ $company->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-3">
            <label for="role" class="form-label is-required">{{ __('user.data.role') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " id="role" name="role_id"
                onchange="set_role_restiction()" {{ $user->role_id == 3 ? 'disabled' : '' }} required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $role->id == $user->role_id ? 'selected' : '' }}>
                        {{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-3">
            <label for="department" class="form-label is-required">{{ __('user.data.department') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " id="wk-department"
                onchange="$('#work-department').val(this.value)" {{ $user->role_id == 3 ? 'disabled' : '' }}>
                @foreach ($work_departments as $department)
                    <option class="option-department" value="{{ $department->id }}"
                        {{ $department->id == $user->work_department_id ? 'selected' : '' }}>
                        {{ $department->name }} </option>
                @endforeach
            </select>
            <input type="hidden" id="work-department" name="work_department_id"
                value="{{ $user->work_department_id }}">
        </div>
        <div class="col-3 mb-3">
            <label for="status" class="form-label is-required">{{ __('user.data.status') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " id="status" name="status_id" required>
                @foreach ($statuses as $status)
                    <option value="{{ $status->id }}" {{ $status->id == $user->status_id ? 'selected' : '' }}>
                        {{ $status->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-3 mb-3">
            <label for="branch" class="form-label is-required">{{ __('user.data.assigned_branch') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " id="branch" name="branch_id" required>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $branch->id == $user->branch_id ? 'selected' : '' }}>
                        {{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 mb-3">
            <label for="hiredate" class="form-label is-required">{{ __('user.data.hiredate') }}: </label>
            <input type="date" class="form-control border-secondary border-opacity-25" id="hiredate" name="hiredate"
                value="{{ !empty($user->roleData->hiredate) ? $user->roleData->hiredate : '' }}" required>
        </div>
        <div class="col-3 mb-3">
            <label for="salary" class="form-label is-required">{{ __('user.data.salary') }}: </label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="salary" name="salary" min="0"
                    placeholder="1000" value="{{ $user->roleData->salary ?? '' }}" required />
            </div>
        </div>
        <div class="col-3 mb-3">
            <label for="clabe" class="form-label">{{ __('user.data.clabe') }}: </label>
            <input type="number" class="form-control border-secondary border-opacity-25" id="clabe" name="clabe"
                value="{{ !empty($user->roleData->clabe) ? $user->roleData->clabe : '' }}" min=0
                placeholder="012345678901234567" oninput="this.value = this.value.toUpperCase()">
        </div>
    </div>
    <div class="row">
        <div class="col-4 mb-3">
            <label for="contract" class="form-label is-required">{{ __('user.data.contract_type') }}: </label>
            <select class="form-select border-secondary border-opacity-25 " id="contract" name="contract" required>
                @foreach ($contracts as $contract)
                    <option value="{{ $contract->id }}"
                        {{ $user->contracts->last()->contract_type_id == $contract->id ? 'selected' : '' }}>
                        {{ $contract->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-4 mb-3">
            <label for="contract_startdate" class="form-label is-required">{{ __('user.data.startdate') }}: </label>
            <input type="date" class="form-control border-secondary border-opacity-25" id="contract_startdate"
                name="contract_startdate" value="{{ $dates['startdate'] }}" required>
        </div>
        <div class="col-4 mb-3">
            <label for="contract_enddate" class="form-label">{{ __('user.data.enddate') }}: </label>
            <input type="date" class="form-control border-secondary border-opacity-25" id="contract_enddate"
                name="contract_enddate" value="{{ $dates['enddate'] }}">
        </div>
    </div>

    <button type="submit" id="form-work-btn" class="btn btn-primary mt-3">{{ __('buttons.update') }}</button>
</form>
