<form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data" onsubmit="return submitForm()">
    @csrf

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="#" onclick="showInputs(this, 1)">Equipo interno</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="showInputs(this, 2)">Cliente</a>
        </li>
    </ul>

    <input type="hidden" name="url_customer" id="url-customer" value="{{ route('order.search.customer') }}" />
    <input type="hidden" id="password" name="password" value="" />
    <input type="hidden" id="type" name="type" value="1" />

    <div class="row mb-3">
        <div class="col-6">
            <label for="name" class="form-label is-required">{{ __('user.data.name') }}:
            </label>
            <input type="text" class="form-control border-secondary border-opacity-50" id="name" name="name"
                placeholder="Example" autocomplete="off" required />
        </div>

        <div class="col-6">
            <label for="email" class="form-label is-required">
                {{ __('user.data.email') }}:
            </label>
            <div class="input-group">
                <input type="text"
                    class="form-control border-secondary border-opacity-50 border-end-0 rounded-0 rounded-start"
                    id="username" placeholder="example"
                    onblur="$('#email').val(this.value + $('#mailType').val()); console.log($('#mailType').val())"
                    required />
                <select
                    class="form-select border-secondary border-opacity-50 input-group-text bg-secondary-subtle rounded-0 rounded-end"
                    id="mailType" required>
                    <option value="@siscoplagas.com">@siscoplagas.com</option>
                    <option value="@terkleen.com">@terkleen.com</option>
                </select>
            </div>
            <input type="hidden" id="email" name="email" />
        </div>
    </div>

    <!-- Usuario-Equipo Interno -->
    <div class="row mb-3" id="intern-inputs">
        <div class="col-3">
            <label for="company" class="form-label">{{ __('user.data.company') }}:
            </label>
            <select class="form-select border-secondary border-opacity-50" id="company" name="company_id"
                autocomplete="off">
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label for="branch" class="form-label">{{ __('user.data.branch') }}:
            </label>
            <div class="input-group flex-nowrap">
                <select class="form-select border-secondary border-opacity-50" id="branch" name="branch_id"
                    autocomplete="off">
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-3">
            <label for="role" class="form-label">{{ __('user.data.role') }}:
            </label>
            <select class="form-select border-secondary border-opacity-50" id="role" name="role_id"
                onchange="restiction(this.value)">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-3">
            <label for="work-department" class="form-label">{{ __('user.data.department') }}:
            </label>
            <select class="form-select border-secondary border-opacity-50" id="wk-department"
                onchange="$('#work-department').val(this.value)">
                @foreach ($work_departments as $department)
                    <option class="option-department" value="{{ $department->id }}">
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" id="work-department" name="work_department_id" value="" />
        </div>
    </div>

    <!-- Usuario-Cliente -->
    <div class="row mb-3" id="client-inputs" style="display: none;">
        <label for="accordionDirectory" class="form-label fw-bold is-required">{{ __('user.title.clients') }}:
        </label>
        <div class="form-text text-danger m-0" id="basic-addon4">
            * En caso de que no aparezca deberas crearlo.
        </div>
        <div class="col-12 p-0 m-0">
            <a href="{{ route('customer.create', ['id' => 0, 'type' => 1]) }}" id="form_service_button"
                class="btn btn-link" target="_blank">
                {{ __('customer.title.create') }}
            </a>
        </div>
        <div class="col-12 mb-3">
            <div class="row border rounded m-0 p-2 bg-body-tertiary">
                <div class="col-3">
                    <label for="client" class="form-label">Nombre: </label>
                    <input class="form-control border-secondary border-opacity-50" name="customer_name"
                        id="customer-name" placeholder="Example" value="" />
                </div>
                <div class="col-3">
                    <label for="client" class="form-label">Teléfono: </label>
                    <input class="form-control border-secondary border-opacity-50" name="customer_phone"
                        id="customer-phone" placeholder="0000000000" value="" />
                </div>
                <div class="col-6">
                    <label for="client" class="form-label">Dirección: </label>
                    <input class="form-control border-secondary border-opacity-50" name="customer_address"
                        id="customer-address" placeholder="Example #00, Col. Example" value="" />
                </div>
                <div class="col-3 mt-2">
                    <button id="form_service_button" type="button" class="btn btn-primary btn-sm"
                        onclick="searchCustomer()">
                        {{ __('buttons.search') }}
                    </button>
                </div>
            </div>
        </div>
        @include('order.modals.customers')

        <div class="col-12" id="customer-select"></div>

        <div class="col-12">
            <label for="accordionDirectory"
                class="form-label fw-bold is-required">{{ __('user.title.permissions') }}:
            </label>
            <ul class="list-group" id="tree-dir">
                @foreach ($directories as $i => $directory)
                @endforeach
            </ul>
        </div>
    </div>

    <input type="hidden" name="directories" id="directories" value="" />
    <input type="hidden" id="customers" name="customers" value="" />

    <button type="submit" class="btn btn-primary mt-3" onclick="generate_password();">
        {{ __('buttons.store') }}
    </button>
</form>

<script>
    const directories = @json($directories);
    const type = @json($type);
    const new_client_account = true;
    const paths = [];

    function showInputs(element, value) {
        $('.nav-link').removeClass('active');
        $(element).addClass('active');

        $('#intern-inputs').toggle(value == 1);
        $('#client-inputs').toggle(value != 1);

        $('#type').val(value);
    }
</script>
