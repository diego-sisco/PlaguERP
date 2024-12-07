@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php
        header('Location: /login');
        exit();
        ?>
    @endif

    <div class="row w-100 h-100 m-0">
        @include('dashboard.quality.navigation')

        <div class="col-11">

            <div class="row p-3 border-bottom">
                <a href="{{ route('quality.customers') }}" class="col-auto btn-primary p-0 fs-3"><i
                        class="bi bi-arrow-left m-3"></i></a>
                <h1 class="col-auto fs-2 m-0">Detalles del cliente: <span class="fw-bold">{{$customer->name}}</span></h1>
            </div>
            <div class="row m-4">
                <div class="col">
                    <a href="{{ route('quality.customer.details.general', ['customerId' => $customer->id, 'section' => 1, 'status' => 1]) }}" class="text-decoration-none">
                        <div class="card border mb-3" style="max-width: 18rem;">
                            <div class="card-header text-center">Ordenes de Servicio</div>
                            <div class="card-body text-dark">
                                    <div class="d-flex justify-content-evenly align-items-center flex-wrap" >
                                        <span class="badge bg-warning">{{$customerData['servicePendiente']}}</span>
                                        <span class="badge bg-info">{{$customerData['serviceFinished']}}</span>
                                        <span class="badge bg-success">{{$customerData['serviceApproved']}}</span>
                                    </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('quality.customer.details.general', ['customerId' => $customer->id, 'section' => 2, 'status' => 1]) }}" class="text-decoration-none">
                        <div class="card border mb-3" style="max-width: 18rem;">
                            <div class="card-header text-center">Planos</div>
                            <div class="card-body text-dark">
                                    <div class="d-flex justify-content-evenly align-items-center flex-wrap" >
                                        <span class="badge bg-secondary">{{$customerData['floorplansCount']}}</span>
                                    </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('quality.customer.details.general', ['customerId' => $customer->id, 'section' => 3, 'status' => 1]) }}" class="text-decoration-none">
                        <div class="card border mb-3" style="max-width: 18rem;">
                            <div class="card-header text-center">Zonas</div>
                            <div class="card-body text-dark">
                                    <div class="d-flex justify-content-evenly align-items-center flex-wrap" >
                                        <span class="badge bg-secondary">{{$customerData['applicationAreaCount']}}</span>
                                    </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('quality.customer.details.general', ['customerId' => $customer->id, 'section' => 4, 'status' => 1]) }}" class="text-decoration-none">
                        <div class="card border mb-3" style="max-width: 18rem;">
                            <div class="card-header text-center">Productos Utilizados</div>
                            <div class="card-body text-dark">
                                <div class="d-flex justify-content-evenly align-items-center flex-wrap">
                                    <div class="badge bg-secondary">
                                        {{ $customerData['products'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ route('customer.edit', ['id' => $customer->id, 'type' => 1 , 'section' => 6]) }}" class="text-decoration-none">
                        <div class="card border mb-3" style="max-width: 18rem;">
                            <div class="card-header text-center">Archivos</div>
                            <div class="card-body text-dark">
                                <div class="d-flex justify-content-evenly align-items-center flex-wrap" >
                                    <span class="badge {{$customerData['customerFile'] < 6 ? 'bg-secondary' : 'bg-success'}}">{{$customerData['customerFile']}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>



            <div class="row mx-3">
                <div class="table-responsive">
                    <table class="table text-center table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col"> Pendiente </th>
                                <th scope="col"> Fecha </th>
                                <th scope="col"> Acciones </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($customerData['pendings'])
                                @foreach ($customerData['pendings'] as $index => $pending)
                                    <tr>
                                        <th scope="row">{{ $index++ }}</th>
                                        <td>{{ $pending['content'] }}</td>
                                        <td>{{ $pending['date'] }}</td>
                                        <td>
                                            @can('write_customer')
                                                    @php
                                                        $route = match($pending['type']) {
                                                            'contract' => route('contract.show', ['id' => $pending['id'], 'section' => 1]),
                                                            'order' => route('order.edit', ['id' => $pending['id']]),
                                                            'file' => route('customer.edit', ['id' => $customer->id, 'type' => 1 , 'section' => 6]),
                                                        };
                                                    @endphp
                                                <a href="{{ $route }}"
                                                    class="btn btn-secondary btn-sm">
                                                    <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">No hay pendientes por el momento.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

    </div>

@endsection
