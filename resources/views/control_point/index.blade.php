@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="row justify-content-between p-3 m-0">
        <div class="col-auto">
            @can('write_point')
                <a class="btn btn-primary" href="{{ route('point.create') }}">
                    <i class="bi bi-plus-lg fw-bold"></i> Crear punto de control
                </a>
            @endcan
        </div>

        <div class="col-4">
            <div type="browser" class="row mb-3">
                @include('control_point.browser')
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @include('messages.alert')
        <div class="table-responsive">
            @include('control_point.tables.index')
        </div>
        {{ $points->links('pagination::bootstrap-5') }}
    </div>
@endsection
