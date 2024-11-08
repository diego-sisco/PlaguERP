@extends('layouts.app')
@section('content')

@if (!auth()->check())
    <?php header("Location: /login"); exit; ?>
@endif

<div class="container-fluid">
    <div class="row border-bottom p-3 mb-3">
        <a href="javascript:history.back()" class="col-auto btn-primary p-0 fs-3"><i
            class="bi bi-arrow-left m-3"></i></a>
    <h1 class="col-auto fs-2 fw-bold m-0"> Revision previa </h1>
    </div>
    <div class="row justify-content-center">
        <div class="col-11">
            @include('report.create.form')
        </div>
    </div>
</div>

<script src="{{ asset('js/user/actions.min.js') }}"></script>
<script src="{{ asset('js/user/validations.min.js') }}"></script>
@endsection