<form
    class="row border rounded bg-body-tertiary p-3 m-0 mb-3"
    class="modal-content"
    method="GET"
    action="{{ route('client.report.search.backup') }}"
    enctype="multipart/form-data"
>
    @csrf
    <div class="col-6 mb-3">
        <label for="sede" class="form-label is-required">Sede: </label>
        <select class="form-select" id="sede" name="sede" required>
            @foreach ($user->customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-2 mb-3">
        <label for="report-id" class="form-label">No. Reporte (#): </label>
        <input
            type="number"
            class="form-control"
            id="report-id"
            name="report_id"
            placeholder="1 ..."
            min="1"
        />
    </div>
    <div class="col-2 mb-3">
        <label for="date" class="form-label">Fecha: </label>
        <input type="date" class="form-control" id="date" name="date" />
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
    </div>
</form>