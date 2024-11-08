@extends('layouts.app')

@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="row justify-content-between p-3 m-0">
        <div class="col-auto">
            @can('write_pest')
                <a class="btn btn-primary" href="{{ route('pest.create', ['page' => 1]) }}">
                    <i class="bi bi-plus-lg fw-bold"></i> {{ __('pest.title.create') }}
                </a>
            @endcan
        </div>

        <div class="col-4">
            <div type="browser" class="row mb-3">
                @include('pest.browser')
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @include('messages.alert')
        <div class="table-responsive">
            @include('pest.tables.index')
        </div>
        {{ $pests->links('pagination::bootstrap-5') }}
    </div>
@endsection
