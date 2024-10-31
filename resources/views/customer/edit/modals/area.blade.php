<div class="modal fade" id="editAreaModal" tabindex="-1" aria-labelledby="editAreaModalLabel" aria-hidden="true">
    <form class="modal-dialog" action="{{ route('area.update') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="editAreaModalLabel">Editar zona/área</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label is-required">Zona: </label>
                    <input type="hidden" id="edit-area-id" name="area_id" value ="">
                    <input type="text" class="form-control border-secondary border-opacity-25" id="edit-name" name="name"
                        placeholder="Agrega una nueva zona/área">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput2" class="form-label">Tipo de zona </label>
                    <select class="form-select border-secondary border-opacity-25 " id="edit-zone-type-id" name="zone_type_id">
                        <option value="">No Aplica (N/A)</option>
                        @foreach($zone_types as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label is-required">Metros cuadrados (m²)</label>
                    <input type="number" class="form-control border-secondary border-opacity-25" id="edit-m2" name="m2" min="0" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"> {{ __('buttons.update') }} </button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </div>
    </form>
</div>

<script>
    function setDataArea(data) {
        const area = JSON.parse(data.getAttribute("data-area"));
        $('#edit-area-id').val(area.id);
        $('#edit-name').val(area.name);
        $('#edit-zone-type-id').val(area.zone_type_id);
        $('#edit-m2').val(area.m2);
    }
</script>
