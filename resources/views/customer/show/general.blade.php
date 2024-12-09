<!-- Datos generales y fiscales -->

<div class="col-12">
    @if ($section == 1)
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.name') }}:</span>
            <span class="col">{{ $customer->name }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.address') }}:</span>
            <span class="col">{{ $customer->address }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.phone') }}:</span>
            <span class="col">{{ $customer->phone }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.correo') }}:</span>
            <span class="col">{{ $customer->email }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.data.url_map') }}:</span>
            @if ($customer->map_location_url)
                <span class="col fw-normal">
                    <a class="btn-link" href="{{ $customer->map_location_url }}" target="_blank">
                        <i class="bi bi-geo-alt-fill"></i> {{ __('customer.data.map_link') }}
                    </a>
                </span>
            @else
                <span class="col fw-normal text-danger">
                    S/N
                </span>
            @endif
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.status') }}:</span>
            <span class="col">
                <span class="{{ $customer->status == 1 ? 'text-success' : 'text-danger' }}">
                    <i
                        class="bi bi-{{ $customer->status == 1 ? 'check2' : 'x' }} {{ $customer->status == 1 ? 'text-success' : 'text-danger' }}"></i>
                    {{ $customer->status == 1 ? 'Activo' : 'Inactivo' }}
                </span>
            </span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.zipcode') }}:</span>
            <span class="col">{{ $customer->zip_code }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.city') }}:</span>
            <span class="col">{{ $customer->city }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.state') }}:</span>
            <span class="col">{{ $customer->state }}</span>
        </div>

        <!-- Categoria -->
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.serv') }}:</span>
            <span class="col">{{ $customer->serviceType->name }}</span>
        </div>

        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.categs') }}:</span>
            <span class="col">{{ $customer->companyCategory->category }}</span>
        </div>

        @if ($type == 0)
            <div class="row">
                <span class="col fw-bold">{{ __('customer.data.reason') }}:</span>
                <span class="col text-danger fw-bold">{{ $customer->reason }}</span>
            </div>
        @endif

        <!-- Disponibilidad de horario -->
        @if ($type != 0)
            <div class="row">
                <span class="col fw-bold">Disponibilidad:</span>
                @if ($customer->start_time != null || $customer->end_time != null)
                    <span class="col">{{ $customer->start_time }} - {{ $customer->end_time }}</span>
                @else
                    <span class="col text-danger">S/N</span>
                @endif
            </div>

            <!-- Datos fiscales -->
            <div class="row">
                <span class="col fw-bold">{{ __('customer.customer_table.busname') }}:</span>
                @if ($customer->tax_name != null)
                    <span class="col">{{ $customer->tax_name }}</span>
                @else
                    <span class="col text-danger">S/N</span>
                @endif
            </div>
            <div class="row">
                <span class="col fw-bold">{{ __('customer.customer_table.tax') }}:</span>
                @if ($customer->taxRegime != null)
                    <span class="col">{{ $customer->taxRegime->name }}</span>
                @else
                    <span class="col text-danger">S/N</span>
                @endif
            </div>
            <div class="row">
                <span class="col fw-bold">{{ __('customer.customer_table.rfc') }}:</span>
                @if ($customer->rfc != null)
                    <span class="col">{{ $customer->rfc }}</span>
                @else
                    <span class="col text-danger">S/N</span>
                @endif
            </div>
        @endif
    @endif

    <!-- Zonas de alcance -->
    @if ($section == 2)
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.branch') }}:</span>
            @foreach ($branches as $item)
                @if ($item->id == $customer->branch_id)
                    <span class="col">{{ $item->name }}</span>
                @endif
            @endforeach
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.meters') }}:</span>
            <span class="col">{{ $customer->meters }} -
                @if ($customer->unit == 1)
                    (M2-Metros Cuadrados)
                @elseif($customer->unit == 2)
                    (M3-Metros Cubicos)
                @else
                    Indefinido
                @endif
            </span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.docs') }}:</span>
            <span class="col">
                @if ($customer->print_doc == 1)
                    <i class="bi bi-check2" style="color: green"></i>Si
                @else
                    <i class="bi bi-x" style="color: brown"></i>No
                @endif
            </span>
        </div>

        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.valcert') }}:</span>
            <span class="col">
                @if ($customer->validate_certificate == 1)
                    <i class="bi bi-check2" style="color: green"></i>Si
                @else
                    <i class="bi bi-x" style="color: brown"></i>No
                @endif
            </span>
        </div>
    @endif

    <!-- Portal -->
    @if ($section == 3)
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.url') }}:</span>
            <span class="col">{{ $customer->url }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.emailweb') }}:</span>
            <span class="col">{{ $customer->portal_email }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.username') }}:</span>
            <span class="col">{{ $customer->username }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.passweb') }}:</span>
            <span class="col">{{ $customer->password }}</span>
        </div>
    @endif

    <!-- Sedes -->
    @if ($section == 4)
        <div class="table-responsive">
            <table class="table text-center table-bordered">
                <thead>
                    <tr>
                        <th scope="col-1">#</th>
                        <th scope="col-2">Nombre</th>
                        <th scope="col-2">Dirección</th>
                        <th scope="col-2">Teléfono</th>
                        <th scope="col-2">Correo</th>
                        <th scope="col-1">Estado</th>
                        <th scope="col-2">{{ __('buttons.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($customer->sedes))
                        @foreach ($customer->sedes as $sede)
                            <tr>
                                <th scope="row">{{ $sede->id }}</th>
                                <td>{{ $sede->name }}</td>
                                <td>{{ $sede->address }}</td>
                                <td>{{ $sede->phone }}</td>
                                <td>{{ $sede->email }}</td>
                                <td
                                    class="align-middle {{ $sede->status == 1 ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ $sede->status == 1 ? 'Activo' : 'Inactivo' }}
                                </td>
                                <td>
                                    <a href="{{ route('customer.show', ['id' => $sede->id, 'type' => 2, 'section' => 1]) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                                    </a>
                                    <a href="{{ route('customer.edit', ['id' => $sede->id, 'type' => 2, 'section' => 1]) }}"
                                        class="btn btn-secondary btn-sm">
                                        <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    @endif

    <!-- Referencias del cliente -->
    @if ($section == 5)
        <!--  <div class="col-12 mb-3">
            <a href="{{ route('reference.create', ['id' => $customer->id, 'type' => $type]) }}"
                class="btn btn-primary">
                Crear
                referencia </a>
        </div>-->
        <div class="table-responsive">
            <table class="table text-center table-bordered">
                <thead>
                    <tr>
                        <th scope="col-1">#</th>
                        <th scope="col-2">Nombre</th>
                        <th scope="col-2">Tipo de referencia</th>
                        <th scope="col-2">Correo electrónico</th>
                        <th scope="col-2">Teléfono</th>
                        <th scope="col-1">Departamento</th>
                        <th scope="col-2">{{ __('buttons.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->references as $reference)
                        <tr>
                            <th scope="row">{{ $reference->id }}</th>
                            <td class="align-middle">{{ $reference->name }}</td>
                            <td class="align-middle">{{ $reference->referenceType->name }}</td>
                            <td class="align-middle">{{ $reference->email }}</td>
                            <td class="align-middle">{{ $reference->phone }}</td>
                            <td class="align-middle">{{ $reference->department }}</td>
                            <td class="align-middle">
                                <a href="{{ route('reference.show', ['id' => $reference->id, 'type' => $type]) }}"
                                    class="btn btn-info btn-sm">
                                    <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                                </a>
                                @can('write_customer')
                                    <a href="{{ Route('reference.edit', ['id' => $reference->id, 'type' => $type]) }}"
                                        class="btn btn-secondary btn-sm">
                                        <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-danger fw-bold" colspan="7">Sin referencias</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    <!-- Archivos -->
    @if ($section == 6)
        <div class="table-responsive">
            <table class="table text-center table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Archivo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->files as $file)
                        <tr>
                            <td class="align-middle">{{ $file->filename->name }}</td>
                            <td class="align-middle">
                                <a href="{{ route('customer.file.download', ['id' => $file->id]) }}"
                                    class="btn btn-link{{ $file->path ? '' : ' disabled' }}">
                                    {{ $file->filename->name . '.pdf' ?? 'Sin documento' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-danger fw-bold" colspan="7">Sin referencias</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    <!-- Areas de aplicación -->
    @if ($section == 7)
        <div class="col-12 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAreaModal">
                Crear zona </button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th scope="col-1">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Creado</th>
                        <th scope="col"> {{ __('buttons.actions') }} </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->applicationAreas as $area)
                        <tr>
                            <th scope="row">{{ $area->id }}</th>
                            <td class="align-middle">{{ $area->name }}</td>
                            <td class="align-middle">{{ $area->created_at }}</td>
                            <td class="align-middle">
                                <a href="{{ Route('area.delete', ['id' => $area->id]) }}"
                                    class="btn btn-danger btn-sm">
                                    <i class="bi bi-x"></i> {{ __('buttons.delete') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-danger fw-bold" colspan="4">Sin zonas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    <!-- Planos -->
    @if ($section == 8)
        <div class="col-12 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#createFloorplanModal">
                Crear plano </button>
        </div>
        <div class="row mb-3">
            @if (!$customer->floorplans->isEmpty())
                @foreach ($customer->floorplans as $floorplan)
                    <div class="col-2 p-0 m-2">
                        <div class="card shadow border-2">
                            <img src="{{ route('image.show', ['filename' => $floorplan->path]) }}"
                                class="card-img-top img-fluid">
                            <div class="card-body m-0">
                                <h5 class="card-title fw-bold">{{ $floorplan->filename }}</h5>
                                <div class="btn-group d-flex justify-content-between">
                                    <a href="{{ route('floorplans.show', ['id' => $floorplan->id, 'type' => $type]) }}"
                                        class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i> Ver
                                        plano</a>

                                    <a href="{{ route('floorplans.edit', ['id' => $floorplan->id, 'customerID' => $customer->id, 'type' => $type, 'section' => 1]) }}"
                                        class="btn btn-secondary btn-sm">
                                        <i class="bi bi-pencil-square"></i> Editar plano
                                    </a>
                                </div>
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
    @endif

    <!-- Ordenes de servicio -->
    @if ($section == 9)
        <div class="col-12 mb-3">
            @can('write_order')
                <a class="btn btn-primary" href="{{ route('order.create') }}">
                    <i class="bi bi-plus-lg fw-bold"></i> {{ __('order.title.create') }}
                </a>
            @endcan
        </div>
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Hrs. de inicio</th>
                        <th scope="col">Fecha programada</th>
                        <th scope="col">Servicio(s)</th>
                        <th scope="col">Cliente(s)</th>
                        <th scope="col">Estado</th>
                        <th scope="col">{{ __('buttons.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->ordersPaginate() as $order)
                        <tr>
                            <th scope="row">{{ $order->id }}</th>
                            <td>{{ $order->start_time }}</td>
                            <td>{{ $order->programmed_date }}</td>
                            <td>
                                {{ implode(', ', $order->services->pluck('name')->toArray()) }}
                            </td>
                            <td>
                                {{ $order->customer->name }}
                            </td>
                            <td
                                class="fw-bold {{ $order->status_id == 1
                                    ? 'text-warning'
                                    : ($order->status_id == 2 || $order->status_id == 3
                                        ? 'text-primary'
                                        : ($order->status_id == 4 || $order->status_id == 5
                                            ? 'text-success'
                                            : 'text-danger')) }}">

                                {{ $order->status->name }}
                            </td>
                            </td>
                            <td>
                                <a class="btn
                                    btn-info btn-sm mb-1"
                                    href="{{ route('order.show', ['id' => $order->id, 'section' => $section]) }}">
                                    <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                                </a>
                                @can('write_order')
                                    <a class="btn btn-secondary btn-sm mb-1"
                                        href="{{ route('order.edit', ['id' => $order->id]) }}">
                                        <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                    </a>
                                    @if ($order->status_id != 6)
                                        <button class="btn btn-danger btn-sm mb-1"
                                            onclick="set_delete_id({{ $order->id }})">
                                            <i class="bi bi-x-lg"></i> {{ __('buttons.cancel') }}
                                        </button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-danger" colspan="7">Sin ordenes asignadas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $customer->ordersPaginate()->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
