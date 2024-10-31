@extends('layouts.app')

@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <!-- Desplegar tabla de catalogo de pestes-->

    <div class="w-100 m-0">
        <div class="row w-100 justify-content-between p-3 m-0 mb-3">
            <div class="col-4">
                @can('write_pest')
                    <a class="btn btn-primary" href="{{ route('pest.create', ['page' => 1]) }}">
                        <i class="bi bi-plus-lg fw-bold"></i> {{ __('pest.title.create') }}
                    </a>
                @endcan
            </div>
            <div class="col-4">
                <div type="browser" class="row">
                    @include('pest.browser')
                </div>
            </div>
        </div>
        <div class="row m-0 p-3">
            @include('pest.tables.index')            
        </div>
        <div class="row p-3 pt-0 m-0 justify-content-center">
            @include('layouts.pagination.pests')
        </div>
    </div>

@endsection
