@extends('layouts.app')

@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="container-fluid pt-3">
        <div class="row justify-content-between">
            <div class="col-auto mb-3">
                @can('write_product')
                    <a class="btn btn-primary" href="{{ route('product.create') }}">
                        <i class="bi bi-plus-lg fw-bold"></i> {{ __('product.title.create') }}
                    </a>
                @endcan
            </div>

            <div class="col-4 mb-3">
                <div type="browser" class="row mb-3">
                    @include('product.browser')
                </div>
            </div>
        </div>
        @include('messages.alert')
        <div class="table-responsive">
            @include('product.tables.index')
        </div>
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
@endsection
