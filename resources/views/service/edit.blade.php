@extends('layouts.app')
@section('content')
    @php
        $time_types = ['Segundo(s)', 'Minuto(s)', 'Hora(s)'];
    @endphp

    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="w-100 m-0">
        <div class="row w-100 justify-content-between p-3 m-0 mb-3">
            <div class="col-12 border-bottom pb-2">
                <div class="row">
                    <a href="{{ route('service.index', ['page' => 1]) }}" class="col-auto btn-primary p-0 fs-3"><i
                            class="bi bi-arrow-left m-3"></i></a>
                    <h1 class="col-auto fs-2 fw-bold m-0">{{ __('service.title.edit') }} {{ $service->id }}</h1>
                </div>
            </div>
            <div class="col-12">
                @include('service.edit.form')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/service/control.min.js') }}"></script>
@endsection
