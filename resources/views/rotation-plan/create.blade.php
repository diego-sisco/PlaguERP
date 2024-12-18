@extends('layouts.app')
@section('content')
    @php
        $time_types = ['Segundo(s)', 'Minuto(s)', 'Hora(s)'];
    @endphp

    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-3">
            <a href="{{ route('rotation.index', ['contractId' => $contract->id]) }}" class="col-auto btn-primary p-0 fs-3"><i
                class="bi bi-arrow-left m-3"></i></a>
        <h1 class="col-auto fs-2 fw-bold m-0">Crear plan de rotación </h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                @include('messages.alert')
                @include('rotation-plan.create.form')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/product.min.js') }}"></script>
@endsection
