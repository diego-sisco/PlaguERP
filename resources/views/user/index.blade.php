@extends('layouts.app')
@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-between">
            <div class="col-auto mb-3">
                @can('write_user')
                    <a class="btn btn-primary" href="{{ route('user.create', ['type' => $type]) }}">
                        <i class="bi bi-plus-lg fw-bold"></i> {{ __('user.title.create') }}
                    </a>
                @endcan
            </div>

            <div type="browser" class="col-4 mb-3">
                @include('user.browser')
            </div>
        </div>

        <div class="row justify-content-between">
            <div class="col-auto mb-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                      <a class="nav-link {{ $type == 1 ? 'active' : '' }}" aria-current="page" href="{{ route('user.index', ['type' => 1]) }}">Staff</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ $type == 1 ? '' : 'active' }}" href="{{ route('user.index', ['type' => 2]) }}">Clientes</a>
                    </li>
                </ul>
            </div>

            <div class="col-4">
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

        @include('messages.alert')
        <div class="table-responsive">
            @include('user.tables.index')
        </div>
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
@endsection
