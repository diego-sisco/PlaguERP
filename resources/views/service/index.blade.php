@extends('layouts.app')
@section('content')
    @php
        $time_types = ['Segundo(s)', 'Minuto(s)', 'Hora(s)'];
    @endphp

    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="row w-100 justify-content-between p-3 m-0 mb-3">
        <div class="col-4">
            @can('write_service')
                <a class="btn btn-primary" href="{{ route('service.create') }}">
                    <i class="bi bi-plus-lg fw-bold"></i> {{ __('service.button.create') }}
                </a>
            @endcan
        </div>
        <div class="col-4">
            <div type="browser" class="row">
                @include('service.browser')
            </div>
        </div>
    </div>
    <div class="row m-0 p-3">
        <div class="table-responsive">
            @include('service.tables.index')
        </div>
    </div>
    <div class="row p-3 pt-0 m-0 justify-content-center">
        @include('layouts.pagination.services')
    </div>
@endsection
