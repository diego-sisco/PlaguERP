<div
    class="modal fade"
    id="createFloorplanModal"
    tabindex="-1"
    aria-labelledby="createFloorplanModalLabel"
    aria-hidden="true"
>
    <form
        class="modal-dialog"
        action="{{ route('floorplans.store', ['customerID' => $customer->id, 'type' => $type]) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h1
                    class="modal-title fs-5 fw-bold"
                    id="createFloorplanModalLabel"
                >
                    Crear plano
                </h1>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body row">
                <div class="col-12 mb-2">
                    <label for="filename" class="form-label is-required"
                        >Nombre:
                    </label>
                    <input
                        type="text"
                        class="form-control border-secondary border-opacity-25"
                        id="filename"
                        name="filename"
                        placeholder="Example"
                        required
                    />
                </div>
                <div class="col-12 mb-4">
                    <label for="filename" class="form-label is-required"
                        >Servicio:
                    </label>
                    <select
                        class="form-select border-secondary border-opacity-25"
                        name="service_id"
                        required
                    >
                        <option value="0">Sin servicio</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <div class="row border rounded shadow-sm p-3 m-0 mb-2">
                        <h5 class="fw-bold">
                            Arrastra y suelta o elige tus archivos haciendo clic
                            aquí:
                        </h5>
                        <p class="text-danger mb-0">
                            Solo se permiten archivos con formato .PDF .JPG
                            .JPEG .PNG
                        </p>
                        <p class="text-danger">
                            Los archivos deben ser menores a 3MB.
                        </p>
                        <input
                            type="hidden"
                            name="customer->id"
                            name="customer->id"
                            value="{{ $customer->id }}"
                        />
                        <input
                            accept=".png, .jpg, .jpeg"
                            type="file"
                            name="file"
                            required
                        />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    {{ __('buttons.update') }}
                </button>
            </div>
        </div>
    </form>
</div>
