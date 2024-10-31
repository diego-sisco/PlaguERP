@extends('layouts.app')
@section('content')
    <div class="row w-100 justify-content-between p-3 m-0 mb-3">
        <div class="col-auto">
            @can('write_order')
                <a class="btn btn-primary" href="{{ route('branch.create') }}">
                    <i class="bi bi-plus-lg fw-bold"></i> Crear delegación
                </a>
            @endcan
        </div>

        <div class="col-4">
            <div type="browser" class="row mb-3">
                {{-- @include('user.browser') --}}
            </div>

        </div>
    </div>
    <div class="row m-0 p-3">
        <div class="table-responsive">
            @include('branch.tables.index')
        </div>
    </div>
    <div class="row p-3 pt-0 m-0 justify-content-center">
        {{-- @include('layouts.pagination.users') --}}
    </div>

    <!-- Modal -->
@endsection
