<div class="modal fade" id="areaModal" tabindex="-1" aria-labelledby="areaModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
        <form class="modal-content" id="area-form" action="{{ route('area.store', ['customerId' => $customer->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="areaModalLabel">Zona</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label is-required">Nombre de zona</label>
                    <input type="text" class="form-control border-secondary border-opacity-50" id="area-name" name="name"
                        placeholder="Agrega una nueva zona/área">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput2" class="form-label">Tipo de zona</label>
                    <select class="form-select border-secondary border-opacity-50 " id="area-zone-type" name="zone_type_id">
                        <option value="">No Aplica (N/A)</option>
                        @foreach($zone_types as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="m2" class="form-label is-required">Metros cuadrados (m²)</label>
                    <input type="number" class="form-control border-secondary border-opacity-50" id="area-m2" name="m2" min="0" max="10000" value="0" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"> {{ __('buttons.store') }} </button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    function setData(element) {
        const area = JSON.parse(element.getAttribute("data-area"));
        const url = "{{ route('area.update', ['id' => ':id']) }}";
        const actionUrl = url.replace(':id', area.id);

        $('#area-form').attr('action', actionUrl);
        $('#area-name').val(area.name);
        $('#area-zone-type').val(area.zone_type_id);
        $('#area-m2').val(area.m2);
    }

    function resetForm() {
        $('#area-form').find(
                'input[type="text"], input[type="number"], input[type="email"], input[type="date"], input[type="file"], select, textarea'
                )
            .val('');
        $('#area-zone-type').val('');
        $('#area-form').attr('action', "{{ route('area.store', ['customerId' => $customer->id]) }}");
    }
</script>
