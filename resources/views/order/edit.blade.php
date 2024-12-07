@extends('layouts.app')
@section('content')

    <div class="w-100 m-0">
        <div class="row w-100 justify-content-between p-3 m-0 mb-3">
            <!-- Header -->
            <div class="col-12 border-bottom pb-2">
                <div class="row">
                    <a href="javascript:history.back()" class="col-auto btn-primary p-0 fs-3"><i
                            class="bi bi-arrow-left m-3"></i></a>
                    <h1 class="col-auto fs-2 fw-bold m-0">{{ __('order.title.edit') }} {{$order->id}}</h1>
                </div>
            </div>
            <div class="col-12">
                @include('order.edit.form')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/technician.min.js') }}"></script>
    <script src="{{ asset('js/service.min.js') }}"></script>
    <script src="{{ asset('js/order/functions.min.js') }}"></script>
@endsection
