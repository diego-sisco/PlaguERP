<div class="row">
    <h5 class="mb-3">Activa o desactiva las propiedades</h5>
    @foreach ($properties as $property)
    <div class="col-12 mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input  property" type="checkbox" role="switch" id="property_{{ $property->id }}" {{ $customer->properties->pluck('id')->contains($property->id) ? 'checked' : '' }} value="{{ $property->id }}" onchange="setProperties()">
            <label class="form-check-label" for="property_{{ $property->id }}">{{ $property->name }}</label>
        </div>
    </div>
    @endforeach

</div>

<input type="hidden" id="selected-properties" name="selected_properties" value="">

<script>
    function setProperties() {
        var input = '#selected-properties'
        var propertyClass = '.property';
        var checkedProperties = [];
        $(propertyClass).each(function() {
            if ($(this).is(':checked')) {
                checkedProperties.push($(this).val());
            }
        });
        $(input).val(JSON.stringify(checkedProperties));
    }

    function setFileId(id) {
        $('#file-id').val(id);
    }
</script>