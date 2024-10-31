@extends('layouts.app')
@section('content')

    @php
        function isPDF($filePath)
        {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $extension = strtolower($extension);
            return $extension === 'pdf' || $extension == 'PDF';
        }
    @endphp

    <style>
        .sidebar {
            color: white;
            text-decoration: none
        }

        .sidebar:hover {
            background-color: #e9ecef;
            color: #212529;
        }

        .flat-btn {
            background-color: #FF6B35;
        }
    </style>

    <div class="row w-100 justify-content-between m-0 h-100">
        <div class="col-1 m-0" style="background-color: #343a40;">
            <div class="row">
                <a href="{{ Route('contract.show', ['id' => $contract->id, 'section' => 1]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Informacion general
                </a>

                <a href="{{ Route('contract.show', ['id' => $contract->id, 'section' => 2]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Ordenes de servicio
                </a>
            </div>
        </div>

        <div class="col-11">
            <div class="row p-3 border-bottom">
                <a href="{{ route('contract.index', ['page' => 1]) }}" class="col-auto btn-primary p-0 fs-3">
                    <i class="bi bi-arrow-left m-3"></i>
                </a>
                <h1 class="col-auto fs-2 fw-bold m-0">{{ __('contract.title.show') }} {{ $contract->id }} </h1>
            </div>

            <div class="row p-5 pt-3">
                @if ($section == 1)
                    <div class="row">
                        <span class="col fw-bold">{{ __('contract.data.customer') }}:</span>
                        <span class="col fw-normal">{{ $contract->customer->name }}</span>
                    </div>
                    <div class="row">
                        <span class="col fw-bold">{{ __('contract.data.created_by') }}:</span>
                        <span class="col fw-normal">{{ $contract->user->name }}</span>
                    </div>
                    <div class="row">
                        <span class="col fw-bold">{{ __('contract.data.start_date') }}:</span>
                        <span class="col fw-normal">{{ $contract->startdate }}</span>
                    </div>
                    <div class="row">
                        <span class="col fw-bold">{{ __('contract.data.end_date') }}:</span>
                        <span class="col fw-normal text-warning-emphasis">{{ $contract->enddate }}</span>
                    </div>

                    @foreach ($contract->services as $service)
                        <div class="row">
                            <span class="col fw-bold">{{ __('contract.data.service') }}:</span>
                            <span class="col fw-bold text-primary">{{ $service->service->name }}
                                [{{ $service->total }}]</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('contract.data.period') }}:</span>
                            <span class="col fw-normal">{{ $service->execfrequency->name }}</span>
                        </div>
                    @endforeach
                @else
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('contract.data.start_time') }}</th>
                                <th scope="col">{{ __('contract.data.programmed_date') }}</th>
                                <th scope="col">{{ __('contract.data.service') }}</th>
                                <th scope="col">{{ __('contract.data.customer') }}</th>
                                <th scope="col">{{ __('contract.data.status') }}</th>
                                <th scope="col">{{ __('buttons.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contract->orders as $order)
                                <tr>
                                    <th scope="row">{{ $order->id }}</th>
                                    <td>{{ $order->start_time }}</td>
                                    <td>{{ $order->programmed_date }}</td>
                                    <td>
                                        @foreach ($order->services as $service)
                                            {{ $service->name }}
                                        @endforeach
                                    </td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td
                                        class="fw-bold 
                                            @if ($order->status_id == 1 || $order->status_id == 5) text-warning
                                            @elseif ($order->status_id == 2 || $order->status_id == 3)
                                                text-primary
                                            @elseif ($order->status_id == 4)
                                                text-success
                                            @else
                                                text-danger @endif">
                                        {{ $order->status->name }}
                                              </td>
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('order.show', ['id' => $order->id, 'section' => 1]) }}">
                                            <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                                        </a>
                                        @can('write_order')
                                            <a class="btn btn-secondary btn-sm"
                                                href="{{ route('order.edit', ['id' => $order->id]) }}">
                                                <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                            </a>
                                        @endcan
                                        @can('write_order')
                                            @if ($order->status->id != 6)
                                                <a href="{{ route('order.destroy', ['id' => $order->id]) }}"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('{{ __('messages.are_you_sure') }}')">
                                                    <i class="bi bi-x-lg"></i> {{ __('buttons.cancel') }}
                                                </a>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
