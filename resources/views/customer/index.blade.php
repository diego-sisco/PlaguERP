@extends('layouts.app')
@section('content')
    <style>
        .sidebar {
            color: white;
            text-decoration: none
        }

        .sidebar:hover {
            background-color: #e9ecef;
            color: #212529;
        }
    </style>
    <div class="row w-100 justify-content-between m-0 h-100">
        <div class="col-1 m-0" style="background-color: #343a40;">
            <div class="row">
                <a href="{{ Route('customer.index', ['type' => 1, 'page' => 1]) }}" class="sidebar col-12 p-2 text-center">
                    Clientes
                </a>
                <a href="{{ Route('customer.index', ['type' => 0, 'page' => 1]) }}" class="sidebar col-12 p-2 text-center">
                    Clientes potenciales
                </a>
                <a href="{{ Route('customer.index', ['type' => 2, 'page' => 1]) }}" class="sidebar col-12 p-2 text-center">
                    Sedes
                </a>
            </div>
        </div>

        <div class="col-11">
            <div class="row justify-content-between p-3 m-0">
                <div class="col-auto">
                    @can('write_customer')
                        @if ($type < 2)
                            <a class="btn btn-primary" href="{{ route('customer.create', ['id' => 0, 'type' => $type]) }}">
                                <i class="bi bi-plus-lg fw-bold"></i>
                                {{ $type == 0 ? __('customer.title.create_lead') : ($type == 1 ? __('customer.title.create') : __('customer.title.create_sede')) }}
                            </a>
                        @endif
                    @endcan
                </div>

                <div class="col-4">
                    <div type="browser" class="row mb-3">
                        @include('customer.browser')
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                @include('messages.alert')
                <div class="table-responsive">
                    @include('customer.tables.index')
                </div>
                {{ $customers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
