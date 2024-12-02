<div class="row mb-4 justify-content-between">
    <div class="col-4 border border-2 rounded shadow-sm">
        <img src="{{ route('image.show', ['filename' => $floorplan->path]) }}" class="card-img-top img-fluid">
    </div>
    <div class="col-7">
        <input type="file" class="form-control border-secondary border-opacity-25 rounded" accept=".png, .jpg, .jpeg"
            name="file" id="file">
    </div>
</div>
<div class="row">
    <div class="col-4 mb-3">
        <label for="filename" class="form-label">Nombre: </label>
        <input type="string" class="form-control border-secondary border-opacity-25" id="filename" name="filename"
            value="{{ $floorplan->filename }}">
    </div>
</div>
<div class="row mb-3">
    <div class="col-6 mb-3">
        <label for="exampleFormControlInput1" class="form-label">Servicio: </label>
        <select class="form-select border-secondary border-opacity-25 " id="service_id" name="service_id">
            <option value="0"> Sin servicio </option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}"
                    {{ ($floorplan->service_id != null && $floorplan->service_id == $service->id) || (!$floorplan->service_id && !$customer->services->isEmpty() && $customer->services->contains('id', $service->id)) ? 'selected' : '' }}>
                    {{ $service->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<button type="submit" class="btn btn-primary">{{ __('buttons.update') }}</button>
