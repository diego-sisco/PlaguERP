@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <style>
        .sidebar {
            color: white;
            text-decoration: none
        }

        .sidebar:hover {
            background-color: #e9ecef;
            color: #212529;
        }
    </style>

    <div class="row w-100 justify-content-between m-0 h-100">
        <div class="col-1 m-0" style="background-color: #343a40;">
            <div class="row">
                <a href="{{ Route('user.edit', ['id' => $user->id, 'section' => 1]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Información de usuario
                </a>
                @if ($user->type_id == 1)
                    <a href="{{ Route('user.edit', ['id' => $user->id, 'section' => 2]) }}"
                        class="sidebar col-12 p-2 text-center">
                        Información laboral
                    </a>
                    <a href="{{ Route('user.edit', ['id' => $user->id, 'section' => 3]) }}"
                        class="sidebar col-12 p-2 text-center">
                        Archivos
                    </a>
                @endif
            </div>
        </div>

        <div class="col-11">
            <div class="row p-3 border-bottom">
                <a href="{{ route('user.index', ['type' => $user->type_id, 'page' => 1]) }}"
                    class="col-auto btn-primary p-0 fs-3"><i class="bi bi-arrow-left m-3"></i></a>
                <h1 class="col-auto fs-2 m-0">{{ __('user.title.edit') }} [<span class="fw-bold">{{ $user->name }}</span>]
                </h1>
            </div>

            <div class="row p-3 m-0">
                @switch($section)
                    @case(1)
                        @include('user.edit.personal')
                    @break
                    @case(2)
                        @include('user.edit.work')
                    @break
                    @case(3)
                        @include('user.edit.files')
                        @include('user.modals.files.update')
                    @break
                @endswitch
            </div>
        </div>
    </div>

    <script src="{{ asset('js/user/actions.min.js') }}"></script>
    <script src="{{ asset('js/directory.min.js') }}"></script>
    <script src="{{ asset('js/customer.min.js') }}"></script>
@endsection
