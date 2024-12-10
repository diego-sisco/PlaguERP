@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-3">
            <a href="{{ route('service.index', ['page' => 1]) }}" class="col-auto btn-primary p-0 fs-3"><i
                    class="bi bi-arrow-left m-3"></i></a>
            <h1 class="col-auto fs-2 fw-bold m-0">{{ $customer->name }}</h1>
        </div>
        <div class="row justify-content-center m-3">
            <div class="col">
                <a href="{{ route('quality.orders', ['id' => $customer->id, 'statusId' => 1]) }}" class="text-decoration-none">
                    <div class="card border border-secondary mb-3">
                        <div class="card-header text-center">Ordenes de Servicio</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span class="badge bg-warning">{{ $customer->countOrdersbyStatus(1) }}</span></h5>
                                <h5><span class="badge bg-info">{{ $customer->countOrdersbyStatus(3) }}</span></h5>
                                <h5><span class="badge bg-success">{{ $customer->countOrdersbyStatus(5) }}</span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('quality.contracts', ['id' => $customer->id]) }}" class="text-decoration-none">
                    <div class="card border border-secondary mb-3">
                        <div class="card-header text-center">Contratos</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span class="badge bg-dark">{{ $customer->contracts->count() }}</span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('quality.floorplans', ['id' => $customer->id]) }}" class="text-decoration-none">
                    <div class="card border border-secondary mb-3">
                        <div class="card-header text-center">Planos</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span class="badge bg-dark">{{ $customer->floorplans->count() }}</span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('quality.zones', ['id' => $customer->id]) }}" class="text-decoration-none">
                    <div class="card border border-secondary mb-3">
                        <div class="card-header text-center">Zonas</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span class="badge bg-dark">{{ $customer->applicationAreas->count() }}</span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('quality.devices', ['id' => $customer->id]) }}"
                    class="text-decoration-none">
                    <div class="card border border-secondary mb-3" style="max-width: 18rem;">
                        <div class="card-header text-center">Dispositivos</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span class="badge bg-dark">
                                    {{ $count_devices }}
                                </span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('customer.edit', ['id' => $customer->id, 'type' => 1, 'section' => 6]) }}"
                    class="text-decoration-none">
                    <div class="card border border-secondary mb-3" style="max-width: 18rem;">
                        <div class="card-header text-center">Archivos</div>
                        <div class="card-body text-dark">
                            <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                <h5><span
                                    class="badge {{ $customer->files->where('path', '!=', null)->count() < 6 ? 'bg-dark' : 'bg-success' }}">{{ $customer->files->where('path', '!=', null)->count() }}</span></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="mx-3">
            <div class="table-responsive">
                <table class="table table-bordered text-center caption-top">
                    <caption class="border bg-secondary-subtle p-2 fw-bold text-dark">
                        Pendientes
                    </caption>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"> Actividad </th>
                            <th scope="col"> Fecha </th>
                            <th scope="col"> Acciones </th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($pendings as $index => $pending)
                            <tr>
                                <th scope="row">{{ ++$index }}</th>
                                <td>{{ $pending['content'] }}</td>
                                <td>{{ $pending['date'] }}</td>
                                <td>
                                    @can('write_customer')
                                        <a href="{{ $pending['type'] == 'contract' ? route('contract.edit', ['id' => $pending['id']]) : ($pending['type'] == 'order' ? route('order.edit', ['id' => $pending['id']]) : route('customer.edit', ['id' => $pending['id'], 'type' => 1, 'section' => 6])) }}"
                                            class="btn btn-secondary btn-sm">
                                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No hay pendientes por el momento.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".card").hover(function() {
                $(this).addClass("animate__animated animate__pulse");
            }, function() {
                $(this).removeClass("animate__animated animate__pulse");
            });
        });
    </script>
@endsection
