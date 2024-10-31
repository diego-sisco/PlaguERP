@include('layouts.alert')

<div class="col-12 mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFloorplanModal">
        Agregar plano </button>
</div>
<div class="row row-cols-6 mb-3">
    @if (!$customer->floorplans->isEmpty())
        @foreach ($customer->floorplans as $i => $floorplan)
            <div class="col">
                <div class="card">
                    <img src="{{ route('image.show', ['filename' => $floorplan->path]) }}" class="card-img-top">
                    <h5 class="card-title fw-bold text-center">
                        {{ $floorplan->filename ? $floorplan->filename : 'Sin Nombre' }}
                    </h5>
                    <div class="card-body d-flex flex-column justify-content-end">
                            <ul class="list-group p-0">
                                <a href="{{ route('floorplans.edit', ['id' => $floorplan->id, 'customerID' => $customer->id, 'type' => $type, 'section' => 1]) }}"
                                    class="list-group-item list-group-item-action"><i class="bi bi-pencil-square"></i>
                                    {{ __('buttons.edit') }}</a>
                                <a href="{{ route('floorplans.print', ['id' => $floorplan->id, 'type' => $type]) }}"
                                    class="list-group-item list-group-item-action"><i class="bi bi-printer-fill"></i>
                                    {{ __('buttons.print') }}</a>
                                <a href="{{ route('floorplans.qr', ['id' => $floorplan->id]) }}"
                                    class="list-group-item list-group-item-action"><i class="bi bi-qr-code"></i>
                                    {{ __('buttons.qr') }}</a>
                                <a href="{{ route('floorplans.delete', ['id' => $floorplan->id, 'customerID' => $customer->id, 'type' => $type]) }}"
                                    class="list-group-item list-group-item-action"
                                    onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')"><i
                                        class="bi bi-x-lg"></i> {{ __('buttons.delete') }}</a>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <span class="text-danger fs-5">Sin planos agregados</span>
        </div>
    @endif
</div>
