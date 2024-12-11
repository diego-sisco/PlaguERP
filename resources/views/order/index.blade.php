@extends('layouts.app')
@section('content')
    <div class="row justify-content-between p-3 m-0">
        <div class="col-auto">
            @can('write_order')
                <a class="btn btn-primary" href="{{ route('order.create') }}">
                    <i class="bi bi-plus-lg fw-bold"></i> {{ __('order.title.create') }}
                </a>
            @endcan
        </div>
    </div>

    <div class="container-fluid">
        @include('messages.alert')
        <div class="table-responsive">
            @include('order.tables.index')
        </div>
        {{--@include('layouts.pagination.orders')--}}
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
@endsection
