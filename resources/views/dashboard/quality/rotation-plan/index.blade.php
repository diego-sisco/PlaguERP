@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-2">
            <a href="{{ route('quality.customer', ['id' => $customer->id]) }}" class="col-auto btn-primary p-0 fs-3"><i
                    class="bi bi-arrow-left m-3"></i></a>
            <h1 class="col-auto fs-2 fw-bold m-0 fw-bold">{{ $customer->name }}</h1>
        </div>

        <div class="row justify-content-between p-3 m-0">
            <div class="col-auto">
                @can('write_order')
                    <a class="btn btn-primary" href="{{ route('rotation.create') }}">
                        <i class="bi bi-plus-lg fw-bold"></i> Crear plan de rotación
                    </a>
                @endcan
            </div>
        </div>

        <div class="container-fluid">
            @include('messages.alert')
            <div class="table-responsive">
                @include('dashboard.quality.order.table')
            </div>
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection