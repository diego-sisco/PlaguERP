@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="row justify-content-between p-3 m-0">
        <div class="col-auto">
            @can('write_user')
                <a class="btn btn-primary" href="{{ route('user.create', ['type' => $type]) }}">
                    <i class="bi bi-plus-lg fw-bold"></i> {{ __('user.title.create') }}
                </a>
            @endcan
        </div>

        <div class="col-4">
            <div type="browser" class="row mb-3">
                @include('user.browser')
            </div>
            <form class="form input-group m-0" method="GET" action="{{ route('user.export') }}">
                <select type="password" class="form-select border-secondary border-opacity-25 " name="option_export">
                    <option value="1" selected>Datos generales (personal y laboral)</option>
                    <option value="2">Archivos</option>
                </select>
                <button type="submit" class="input-group-text btn btn-dark" id="basic-addon1">
                    {{ __('buttons.export') }}
                </button>
            </form>
        </div>
    </div>

    <div class="container-fluid">
        @include('messages.alert')
        <div class="table-responsive">
            @include('user.tables.index')
        </div>
        @include('layouts.pagination.users')
    </div>
@endsection
