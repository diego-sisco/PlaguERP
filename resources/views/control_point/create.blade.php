@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-3">
            <a href="{{ route('point.index') }}" class="col-auto btn-primary p-0 fs-3"><i
                class="bi bi-arrow-left m-3"></i></a>
        <h1 class="col-auto fs-2 fw-bold m-0">{{ __('control_point.title.create') }}</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                @include('control_point.create.form')
            </div>
        </div>
    </div>
@endsection
