@extends('layouts.app')

@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="w-100 m-0">
        <div class="row w-100 justify-content-between p-3 m-0 mb-3">
            <div class="col-4">
               
                    <a class="btn btn-primary" href="{{ route('point.create') }}">
                        <i class="bi bi-plus-lg fw-bold"></i> {{ __('product.title-product.create_point') }}
                    </a>
               
            </div>
            <div class="col-4">
                <div type="browser" class="row">
                    @include('product.control_point.browser')
                </div>
            </div>
        </div>
        <div class="row m-0 p-3">
            @include('product.control_point.staticpoint')
        </div>
        <div class="row p-3 pt-0 m-0 justify-content-center">
            @include('layouts.pagination.ctrlpoints')
        </div>
    </div>

@endsection
