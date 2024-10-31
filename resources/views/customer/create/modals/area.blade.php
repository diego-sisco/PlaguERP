<div class="modal fade" id="createAreaModal" tabindex="-1" aria-labelledby="createAreaModalLabel" aria-hidden="true">
    <form class="modal-dialog" action="{{ route('area.store', ['customerId' => $customer->id]) }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="createAreaModalLabel">Crear zona</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label is-required">Zona: </label>
                    <input type="text" class="form-control border-secondary border-opacity-25" id="name" name="name"
                        placeholder="Agrega una nueva zona/área">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput2" class="form-label">Tipo de zona </label>
                    <select class="form-select border-secondary border-opacity-25 " id="zone_type_id" name="zone_type_id">
                        <option value="">No Aplica (N/A)</option>
                        @foreach($zone_types as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label is-required">Metros cuadrados (m²)</label>
                    <input type="number" class="form-control border-secondary border-opacity-25" id="m2" name="m2" min="0" value="0" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"> {{ __('buttons.store') }} </button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </div>
    </form>
</div>
