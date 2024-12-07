@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-3">
            <a href="{{ route('contract.index') }}" class="col-auto btn-primary p-0 fs-3"><i
                    class="bi bi-arrow-left m-3"></i></a>
            <h1 class="col-auto fs-2 fw-bold m-0">{{ __('contract.title.edit') }}</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                @include('contract.edit.form')
                @include('contract.modals.edit-service')
                @include('contract.modals.preview')
                @include('contract.modals.service')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/technician.min.js') }}"></script>
    <script src="{{ asset('js/customer.min.js') }}"></script>
    <script src="{{ asset('js/service.min.js') }}"></script>
    <script src="{{ asset('js/contract/functions.min.js') }}"></script>
@endsection
