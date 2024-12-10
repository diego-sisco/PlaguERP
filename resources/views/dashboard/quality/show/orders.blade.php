@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-2">
            <a href="{{ route('service.index', ['page' => 1]) }}" class="col-auto btn-primary p-0 fs-3"><i
                    class="bi bi-arrow-left m-3"></i></a>
            <h1 class="col-auto fs-2 fw-bold m-0 fw-bold">{{ $customer->name }}</h1>
        </div>

        <div class="row justify-content-between p-3 m-0">
            <div class="col-auto">
                @can('write_order')
                    <a class="btn btn-primary" href="{{ route('order.create') }}">
                        <i class="bi bi-plus-lg fw-bold"></i> {{ __('order.title.create') }}
                    </a>
                @endcan
            </div>

            <div class="col-4">
                <div type="browser" class="row mb-3">
                    @include('order.browser')
                </div>
            </div>
        </div>

        <div class="container-fluid">
            @include('messages.alert')
            <div class="table-responsive">
                @include('order.tables.index')
            </div>
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
