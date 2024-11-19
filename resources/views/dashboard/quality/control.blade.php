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
            <div class="row w-100 justify-content-between p-3 m-0">
                <div class="col-4">
                    @can('write_service')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#controlModal">
                            <i class="bi bi-plus-lg fw-bold"></i> Crear relación
                        </button>
                    @endcan
                </div>
            </div>
            <div class="row m-0 p-3">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th class="col-1 align-middle" scope="col">ID Encargado (Calidad)</th>
                                <th class="align-middle"scope="col">Encargado (Calidad)</th>
                                <th class="col-1 align-middle" scope="col">ID Cliente</th>
                                <th class="align-middle" scope="col">Cliente</th>
                                <th class="align-middle" scope="col">Sedes del cliente</th>
                                <th class="align-middle" scope="col">{{ __('buttons.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @foreach ($customers as $customer)
                                    @if ($customer->user ? $customer->user->id == $user->id : false)
                                        @php
                                            $subdata = [];
                                            foreach ($customer->sedes as $sede) {
                                                $subdata[] = [
                                                    'name' => $sede->name,
                                                    'orders' => [
                                                        [
                                                            'status_id' => 1,
                                                            'status_name' => 'Pendientes',
                                                            'count' => $sede->countOrdersbyStatus(1),
                                                        ],
                                                        [
                                                            'status_id' => 3,
                                                            'status_name' => 'Finalizadas',
                                                            'count' => $sede->countOrdersbyStatus(3),
                                                        ],
                                                        [
                                                            'status_id' => 4,
                                                            'status_name' => 'Aprobadas',
                                                            'count' => $sede->countOrdersbyStatus(4),
                                                        ],
                                                    ],
                                                ];
                                            }
                                            $data = [
                                                'user' => $user->name,
                                                'customer_matrix' => $customer->name,
                                                'sedes' => $subdata,
                                            ];
                                            $data = json_encode($data);
                                        @endphp
                                        <tr>
                                            <th scope="row">{{ $user->id }}</th>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $customer->id }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->sedes->pluck('name')->implode(', ') }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#performanceModal"
                                                    data-performance="{{ $data }}" onclick="setPerformance(this)">
                                                    <i class="bi bi-lightning-charge-fill"></i>
                                                    {{ __('buttons.performance') }}
                                                </button>
                                                <a href="{{ route('quality.control.destroy', ['customerId' => $customer->id]) }}"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                                                    <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row p-3 pt-0 m-0 justify-content-center">
                {{-- @include('layouts.pagination.quality') --}}
            </div>
        </div>
    </div>

    @include('dashboard.quality.modals.control')
    @include('dashboard.quality.modals.performance')
@endsection
