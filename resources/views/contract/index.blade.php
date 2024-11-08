@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="row justify-content-between p-3 m-0">
        <div class="col-auto">
            @can('write_order')
                <a class="btn btn-primary" href="{{ route('contract.create') }}">
                    <i class="bi bi-plus-lg fw-bold"></i> {{ __('contract.title.create') }}
                </a>
            @endcan
        </div>

        <div class="col-4">
            <div type="browser" class="row mb-3">
                @include('contract.browser')
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @include('messages.alert')
        <div class="table-responsive">
            @include('contract.tables.index')
        </div>
        {{ $contracts->links('pagination::bootstrap-5') }}
    </div>

    <script src="{{ asset('js/technician.min.js') }}"></script>
@endsection
