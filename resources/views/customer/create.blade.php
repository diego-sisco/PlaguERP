@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-3">
            <a href="{{ route('customer.index', ['type' => $type, 'page' => 1]) }}" class="col-auto btn-primary p-0 fs-3"><i
                    class="bi bi-arrow-left m-3"></i></a>
            <h1 class="col-auto fs-2 fw-bold m-0">
                {{ $type == 0 ? __('customer.title.create_lead') : ($type == 1 ? __('customer.title.create') : __('customer.title.create_sede')) }}
            </h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                @include('customer.create.form')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/user/validations.min.js') }}"></script>
    <script src="{{ asset('js/user/actions.min.js') }}"></script>
@endsection
