@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

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
            <div class="row w-100 justify-content-between p-3 m-0 mb-3">
                <div class="col-4">
                    @if ($type != 2)
                        @can('write_customer')
                            <a class="btn btn-primary" href="{{ route('customer.create', ['id' => 0, 'type' => $type]) }}">
                                <i class="bi bi-plus-lg fw-bold"></i>
                                @switch($type)
                                    @case(0)
                                        {{ __('customer.title.create_lead') }}
                                    @break;
                                    @case(1)
                                        {{ __('customer.title.create') }}
                                    @break;
                                @endswitch
                            </a>
                        @endcan
                    @endif
                </div>
                <div class="col-4">
                    <div type="browser" class="row">
                        @include('customer.browser')
                    </div>
                </div>
            </div>
            <div class="row m-0 p-3">
                @include('customer.tables.index')           
            </div>
            <div class="row p-3 pt-0 m-0 justify-content-center">
                @include('layouts.pagination.customers')
            </div>
        </div>
    </div>
@endsection
