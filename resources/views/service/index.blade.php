@extends('layouts.app')
@section('content')
    @php
        $time_types = ['Segundo(s)', 'Minuto(s)', 'Hora(s)'];
    @endphp

    <div class="container-fluid pt-3">
        <div class="row justify-content-between">
            <div class="col-auto mb-3">
                @can('write_service')
                    <a class="btn btn-primary" href="{{ route('service.create') }}">
                        <i class="bi bi-plus-lg fw-bold"></i> {{ __('service.button.create') }}
                    </a>
                @endcan
            </div>

            <div class="col-4 mb-3">
                <div type="browser" class="row mb-3">
                    @include('service.browser')
                </div>
            </div>
        </div>
        @include('messages.alert')
        <div class="table-responsive">
            @include('service.tables.index')
        </div>
        {{ $services->links('pagination::bootstrap-5') }}
    </div>
@endsection
