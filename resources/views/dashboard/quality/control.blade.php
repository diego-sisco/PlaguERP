@extends('layouts.app')
@section('content')
    @php
        $page = $control_customers->currentPage();
        $index = $size * ($page - 1) + 1;
    @endphp

    <div class="row w-100 justify-content-between m-0 h-100">
        <div class="col-1 m-0" style="background-color: #343a40;">
            @include('dashboard.quality.navigation')
        </div>
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
                                <th class="col-1 align-middle" scope="col">#</th>
                                <th class="align-middle"scope="col">Responsable de Calidad</th>
                                <th class="align-middle" scope="col">Cliente matriz</th>
                                <th class="align-middle" scope="col">{{ __('buttons.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($control_customers as $control_customer)
                                <tr>
                                    <th scope="row">{{ $index }}</th>
                                    <td>{{ $control_customer->administrative->name }}</td>
                                    <td>{{ $control_customer->name }}</td>
                                    <td>
                                        {{--<button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#performanceModal" data-performance="{{ $data }}"
                                            onclick="setPerformance(this)">
                                            <i class="bi bi-lightning-charge-fill"></i>
                                            {{ __('buttons.performance') }}
                                        </button>--}}
                                        <a href="{{ route('quality.control.destroy', ['id' => $control_customer->id]) }}"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                                            <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                                        </a>
                                    </td>
                                </tr>
                                @php
                                    $index++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $control_customers->links('pagination::bootstrap-5') }}
    </div>

    @include('dashboard.quality.modals.control')
    @include('dashboard.quality.modals.performance')
@endsection
