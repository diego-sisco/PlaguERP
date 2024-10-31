@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="row w-100 justify-content-between p-3 m-0 mb-3">
        <div class="col-4">
            @can('write_order')
                <a class="btn btn-primary" href="{{ route('contract.create') }}">
                    <i class="bi bi-plus-lg fw-bold"></i> {{ __('contract.title.create') }}
                </a>
            @endcan
        </div>
        <div class="col-4">
            <div type="browser" class="row">
                @include('contract.browser')
            </div>
        </div>
    </div>
    <div class="row m-0 p-3">
        <div class="table-responsive">
            @include('contract.tables.index')
        </div>
    </div>
    <div class="row p-3 pt-0 m-0 justify-content-center">
        @include('layouts.pagination.contracts')
    </div>

    <script src="{{ asset('js/technician.min.js') }}"></script>
@endsection
