@extends('layouts.app')
@section('content')
    <div class="row w-100 justify-content-between m-0 h-100">
        
        @if($section == 1)
            @include('dashboard.quality.show.navigation')
        @else
            @include('dashboard.quality.navigation')
        @endif


        <div class="col-11">
            <div class="container-fluid">
                <div class="row justify-content-end">
                    <div type="browser" class="col-4 mb-3">
                    </div>
                </div>

                @include('messages.alert')
                <div class="row p-3 border-bottom">
                    <a href="{{ route('quality.customer.detail', ['id' => $customerId]) }}" class="col-auto btn-primary p-0 fs-3"><i
                        class="bi bi-arrow-left m-3"></i></a>
                    <h1 class="col-auto fs-2 m-0">
                        @switch($section)
                            @case(1)
                                Ordenes 
                                @switch($status)
                                    @case(1)
                                        Pendientes
                                        @break
                                    @case(2)
                                        Aceptadas
                                        @break
                                    @case(3)
                                        Finalizadas
                                        @break
                                    @case(4)
                                        Verificadas
                                        @break
                                    @case(5)
                                        Aprobadas
                                        @break
                                    @case(6)
                                        Canceladas
                                        @break
                                @endswitch
                                @break
                            @case(2)
                                Planos del cliente
                                @break
                            @case(3)
                                Zonas del cliente
                                @break
                            @case(4)
                                Dispositivos utilizados
                                @break
                        @endswitch
                    </h1>
                </div>

                <!-- Ordenes de servicio -->
                @if( $section == 1)
                    <div class="table-responsive m-3">
                        <table class="table text-center table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col-1" class="align-middle"># (ID)</th>
                                    <th scope="col-2" class="align-middle">Hora y fecha programada</th>
                                    <th scope="col-2" class="align-middle">Fecha realizada</th>
                                    <th scope="col-2" class="align-middle">Encargado</th>
                                    <th class="col" scope="col-2" class="align-middle">
                                        Cliente
                                    </th>
                                    <th scope="col" class="align-middle">Técnico</th>
                                    <th scope="col" class="align-middle">Estado</th>
                                    <th scope="col" class="align-middle">{{ __('buttons.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @if(!$orders->isEmpty())

                                    @foreach ($orders as $order)
                                        <tr id="order-{{ $order->id }}">
                                            <th class="align-middle" scope="row">{{ $order->id }}</th>
                                            <td class="align-middle"> {{ $order->start_time }} {{ $order->programmed_date }}</td>
                                            <td class="align-middle {{ empty($order->completed_date) ? 'text-danger' : '' }}">
                                                {{ empty($order->completed_date) ? 'S/N' : $order->completed_date }}</td>
                                            <td class="align-middle">
                                                {{ $order->customer->user->name ?? 'S/A' }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $order->customer->name }}
                                            </td>
                                            <td class="align-middle">
                                                @foreach ($order->technicians as $technician)
                                                    <div class="col-12">{{ $technician->user->name }}</div>
                                                @endforeach
                                            </td>
                                            <td
                                                class="fw-bold align-middle
                                                    @if ($order->status_id == 1) text-warning
                                                    @elseif ($order->status_id == 2 || $order->status_id == 3) text-primary
                                                    @elseif ($order->status_id == 4 || $order->status_id == 5) text-success
                                                    @else text-danger @endif 
                                                ">
                                                {{ $order->status->name }}
                                            </td>
                                            <td class="align-middle">
                                                <a class="btn btn-info btn-sm"
                                                    href="{{ route('order.show', ['id' => $order->id, 'section' => 1]) }}">
                                                    <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                                                </a>
                                                @can('write_order')
                                                    <a class="btn btn-secondary btn-sm mb-1"
                                                        href="{{ route('order.edit', ['id' => $order->id]) }}">
                                                        <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                                    </a>
                                                    @if ($status > 2 && $status < 6)
                                                        <a class="btn btn-dark btn-sm mb-1"
                                                            href="{{ route('report.review', ['id' => $order->id]) }}">
                                                            <i class="bi bi-file-pdf-fill"></i> Reporte
                                                        </a>
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">No hay ordenes por el momento.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{ $orders->links('pagination::bootstrap-5') }}

                
                <!-- Planos -->
                @elseif($section == 2)
                    <div class="table-responsive m-3">
                        <table class="table text-center table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col-1" class="align-middle">#</th>
                                    <th scope="col-1" class="align-middle">ID</th>
                                    <th scope="col-2" class="align-middle">Nombre</th>
                                    <th scope="col-2" class="align-middle">Servicio</th>
                                    <th scope="col-1" class="align-middle">Cantidad de dispositivos</th>
                                    <th scope="col-1" class="align-middle">No. de version</th>
                                    <th scope="col-2" class="align-middle">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @if($floorplans)
                                    @foreach ($floorplans as $index => $floorplan)
                                        <tr>
                                            <th class="align-middle" scope="row">{{ ++$index }}</th>
                                            <td class="align-middle" scope="row">{{ $floorplan['id'] }}</td>
                                            <td class="align-middle">{{ $floorplan['name'] }}</td>
                                            <td class="align-middle">
                                                {{ $floorplan['service'] ? $floorplan['service'] : 'S/S' }}
                                            </td>
                                            <td class="align-middle">{{ $floorplan['deviceCount'] }}</td>
                                            <td class="align-middle">{{ $floorplan['version'] }}</td>
                                            <td class="align-middle">
                                            <a href="{{ route('floorplans.edit', ['id' => $floorplan['id'], 'customerID' => $customerId, 'type' => 1, 'section' => 1]) }}"
                                                class="btn btn-info btn-sm mb-1">
                                                <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                                            </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">No existen planos del cliente.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                <!-- Zonas -->
                @elseif($section == 3)
                    <div class="table-responsive m-3">
                        <table class="table text-center table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col-1" class="align-middle">#</th>
                                    <th scope="col-1" class="align-middle">ID</th>
                                    <th scope="col-2" class="align-middle">Nombre</th>
                                    <th scope="col-2" class="align-middle">Tipo de Zona</th>
                                    <th scope="col-1" class="align-middle">m2</th>
                                    <th scope="col-1" class="align-middle">Cantidad de dispositivos</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @if($zones)
                                    @foreach ($zones as $index => $zone)
                                        <tr>
                                            <th class="align-middle" scope="row">{{ ++$index }}</th>
                                            <td class="align-middle" scope="row">{{ $zone['id'] }}</td>
                                            <td class="align-middle">{{ $zone['name'] }}</td>
                                            <td class="align-middle">
                                                {{ $zone['zonetype'] ? $zone['zonetype'] : 'S/Z' }}
                                            </td>
                                            <td class="align-middle">{{ $zone['m2'] }}</td>
                                            <td class="align-middle">{{ $zone['deviceCount'] }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">No existen zonas del cliente.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                <!-- Dispositivos Utilizados -->
                @elseif($section == 4)
                    <div class="table-responsive m-3">
                        <table class="table text-center table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col-1" class="align-middle"># (ID)</th>
                                    <th scope="col-2" class="align-middle">Tipo</th>
                                    <th scope="col-1" class="align-middle">Planos donde se utiliza</th>
                                    <th scope="col-1" class="align-middle">Cantidad total utilizada</th>
                                    <th scope="col-1" class="align-middle">Areas donde se encuentran</th>
                                    <th scope="col-1" class="align-middle">Codigo</th>

                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @if($deviceSummary)
                                    @foreach ($deviceSummary as $index => $device)
                                        <tr>
                                            <th class="align-middle" scope="row">{{ $device['id'] }}</th>
                                            <td class="align-middle">{{ $device['name'] }}</td>
                                            <td class="align-middle">
                                                {{ implode(', ', $device['floorplans']) }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $device['count'] }}
                                            </td>
                                            <th class="align-middle" scope="row">
                                                {{ implode(', ', $device['zones']) }}
                                            </th>
                                            <th class="align-middle" scope="row">{{ $device['code'] }}</th>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">No hay dispositivos utilizados por el cliente.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>

    
@endsection
