@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php
        header('Location: /login');
        exit();
        ?>
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

        .flat-btn {
            background-color: #55ff00;
        }
    </style>

    <div class="row w-100 justify-content-between m-0 h-100">
        <div class="col-1 m-0" style="background-color: #343a40;">
            <div class="row">
                <a href="{{ route('user.create', ['type' => 1]) }}" class="sidebar col-12 p-2 text-center">
                    Crear usuario
                </a>
                <a href="{{ route('rrhh', ['section' => 1]) }}" class="sidebar col-12 p-2 text-center"> Usuarios pendientes
                </a>
                <a class="sidebar col-12 p-2 text-center" data-bs-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Documentos
                </a>
                <div class="collapse" id="collapseExample" style="background-color: #495057;">
                    <div class="row">
                        <a href="{{ route('rrhh', ['section' => 2]) }}" class="sidebar col-12 p-2 text-center">
                            Pendientes
                        </a>
                        <a href="{{ route('rrhh', ['section' => 3]) }}" class="sidebar col-12 p-2 text-center">
                            Por expirar
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-11 p-3">
            @if ($section == 1)
                @include('dashboard.rrhh.tables.waiting')
                @else
                @include('dashboard.rrhh.tables.files')

            @endif
        </div>
    </div>
@endsection
