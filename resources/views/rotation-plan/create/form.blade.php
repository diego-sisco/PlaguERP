<form id="create_service_form" class="form" method="POST" action="{{ route('rotation.store') }}"
    enctype="multipart/form-data">
    @csrf
    <div class="row mb-2">
        <h5 class="fw-bold pb-1 border-bottom">Asignación</h5>
        <div class="col-4 mb-3">
            <label class="form-label is-required">Nombre del cliente</label>
            <input type="text" class="form-control border-secondary border-opacity-50"
                value="{{ $contract->customer->name }}" disabled />
        </div>
        <div class="col-2 mb-3">
            <label class="form-label is-required">Contracto asignado</label>
            <input type="text" class="form-control border-secondary border-opacity-50"
                value="[{{ $contract->id }}] {{ date('d-m-Y', strtotime($contract->startdate)) }} : {{ date('d-m-Y', strtotime($contract->enddate)) }}"
                disabled />
        </div>
        <div class="col-1 mb-3">
            <label class="form-label is-required">Revisión </label>
            <input type="number" class="form-control border-secondary border-opacity-50" id="no-review"
                name="no_review" value="{{ $contract->rotationPlans->count()+1 }}" min="1" disabled />
        </div>
    </div>
    <div class="row mb-2">
        <h5 class="fw-bold pb-1 border-bottom">Información general</h5>
        <div class="col-6 mb-3">
            <label class="form-label is-required">Nombre </label>
            <input type="text" class="form-control border-secondary border-opacity-50" id="name" name="name"
                placeholder="Nombre del plan de rotación" required />
        </div>
        <div class="col-2 mb-3">
            <label class="form-label is-required">Código </label>
            <input type="text" class="form-control border-secondary border-opacity-50" id="code" name="code"
                required />
        </div>
        <div class="col-2 mb-3">
            <label class="form-label is-required">Fecha de autorización </label>
            <input type="date" class="form-control border-secondary border-opacity-50" id="authorization-at"
                name="authorization_at" required />
        </div>
    </div>

    <div class="row mb-2">
        <h5 class="fw-bold pb-1 border-bottom">Duración</h5>
        <div class="col-2 mb-3">
            <label class="form-label is-required">Inicia en </label>
            <input type="date" class="form-control border-secondary border-opacity-50" id="startdate"
                name="start_date" value="{{ $contract->startdate }}" required />
        </div>
        <div class="col-2 mb-3">
            <label class="form-label is-required">Termina en </label>
            <input type="date" class="form-control border-secondary border-opacity-50" id="enddate" name="end_date"
                value="{{ $contract->enddate }}" required />
        </div>
    </div>

    <div class="row mb-2">
        <h5 class="fw-bold pb-1 border-bottom">Productos</h5>
        <div class="form-text text-danger pb-1" id="basic-addon4">
            * Selecciona al menos 1 producto.
        </div>
        <div class="col-12">
            <div class="input-group mb-3">
                <input type="text" class="form-control border-secondary border-opacity-50" id="search"
                    placeholder="Nombre del producto/materia prima">
                <button class="btn btn-primary" type="button" onclick="getProducts()"><i class="bi bi-search"></i>
                    {{ __('buttons.search') }}</button>
            </div>
        </div>

        <div class="col-12">
            <div class="mb-3">
                <button type="button" class="btn btn-success btn-sm" onclick="hasProducts()">Agregar fechas</button>
            </div>
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Id</th>
                        <th class="col-3 text-center" scope="col">Nombre</th>
                        <th class="col text-center" scope="col">Color</th>
                        <th class="col text-center" scope="col">Utilización</th>
                        <th class="col text-center" scope="col">Ingrediente activo</th>
                        <th class="col-2 text-center" scope="col">Inicia en</th>
                        <th class="col-2 text-center" scope="col">Termina en</th>
                        <th class="col text-center" scope="col">{{ __('buttons.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="selected-products"></tbody>
            </table>
        </div>
    </div>
    <div class="row mb-2">
        <h5 class="fw-bold pb-1 border-bottom">Información adicional</h5>
        <div class="col-12 mb-3">
            <label class="form-label">Aviso importante </label>
            <input type="text" class="form-control border-secondary border-opacity-50" id="importart-text"
                name="importart_text" placeholder="" />
        </div>
        <div class="col-12 mb-3">
            <label class="form-label">Notas </label>
            <textarea class="form-control border-secondary border-opacity-50" id="notes" name="notes" rows="5"></textarea>
        </div>
    </div>

    <input type="hidden" id="url-search-product" value="{{ route('rotation.search.product') }}" />
    <input type="hidden" name="contractId" value="{{ $contract->id }}" />
    <input type="hidden" id="products" name="products" />

    <button type="submit" class="btn btn-primary my-3" onclick="submitForm()">
        {{ __('buttons.store') }}
    </button>
</form>

<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Productos encontrados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="product-list"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    onclick="setProducts()">{{ __('buttons.store') }}</button>
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dateModalLabel">Período</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label is-required">Número (#) de producto </label>
                    <input type="text" class="form-control" id="product-indexs" placeholder="1, 2, 3..."
                        oninput="verifyProduct(this.value)">
                </div>
                <div class="mb-3">
                    <label class="form-label is-required">Período de los productos </label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="product-startdate">
                        <input type="date" class="form-control" id="product-enddate">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label is-required">Color </label>
                    <input type="color" class="form-control form-control-color" id="color" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    onclick="setDates()">{{ __('buttons.store') }}</button>
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </div>
    </div>
</div>