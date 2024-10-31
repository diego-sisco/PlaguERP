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
                <a href="{{ route('dashboard.crm', ['section' => 1]) }}" class="sidebar col-12 p-2 text-center"> Inicio
                </a>
                <a class="sidebar col-12 p-2 text-center" data-bs-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Seguimiento de clientes
                </a>
                <div class="collapse" id="collapseExample" style="background-color: #495057;">
                    <div class="row">
                        <a href="{{ route('dashboard.crm', ['section' => 2]) }}" class="sidebar col-12 p-2 text-center">
                            Agendados
                        </a>
                        <a href="{{ route('dashboard.crm', ['section' => 3]) }}" class="sidebar col-12 p-2 text-center">
                            Potenciales
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-11 p-3">

            @if ($section == 1)
                <div class="row mb-3">
                    @include('dashboard.crm.chart')
                </div>
            @endif
            @if ($section == 2)
                @include('dashboard.crm.tables.tracking')
            @endif

            @if ($section == 3)
                @include('dashboard.crm.tables.leads')
            @endif
            
        </div>
    </div>
@endsection
