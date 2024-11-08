@extends('layouts.app')
@section('content')
    <div class="row justify-content-between p-3 m-0">
        <div class="col-auto">
            @can('write_order')
                <a class="btn btn-primary" href="{{ route('branch.create') }}">
                    <i class="bi bi-plus-lg fw-bold"></i> {{ __('branch.title.create') }}
                </a>
            @endcan
        </div>
    </div>

    <div class="container-fluid">
        @include('messages.alert')
        <div class="table-responsive">
            @include('branch.tables.index')
        </div>
    </div>
@endsection
