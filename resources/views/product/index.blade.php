@extends('layouts.app')

@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="w-100 m-0">
        <div class="row w-100 justify-content-between p-3 m-0 mb-3">
            <div class="col-4">
                @can('write_product')
                    <a class="btn btn-primary" href="{{ route('product.create') }}">
                        <i class="bi bi-plus-lg fw-bold"></i> {{ __('pagination.button.create_pesti') }}
                    </a>
                @endcan
            </div>
            <div class="col-4">
                <div type="browser" class="row">
                    @include('product.browser')
                </div>
            </div>
        </div>
        <div class="row m-0 p-3">
            <div class="table-responsive">
                @include('product.tables.index')
            </div>

        </div>
        <div class="row p-3 pt-0 m-0 justify-content-center">
            @include('layouts.pagination.products')
        </div>
    </div>
@endsection
