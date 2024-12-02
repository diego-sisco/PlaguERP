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
    </style>

    {{-- <div class="row w-100 justify-content-between m-0 h-100">
        <div class="col-1 m-0" style="background-color: #343a40;">
            <div class="row">
                <a href="#" class="sidebar col-12 p-2 text-center"> Inicio
                </a>
                <a class="sidebar col-12 p-2 text-center" data-bs-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Seguimiento de clientes
                </a>
                <div class="collapse" id="collapseExample" style="background-color: #495057;">
                    <div class="row">
                        <a href="{{ route('crm', ['section' => 2]) }}" class="sidebar col-12 p-2 text-center">
                            Agendados
                        </a>
                        <a href="{{ route('crm', ['section' => 3]) }}" class="sidebar col-12 p-2 text-center">
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
    </div> --}}

    <div class="w-100 h-100 m-0 p-5">
        <h1 class="fw-bold text-center mb-5"> Gestión de Relación con Clientes (CRM) </h1>
        <div class="d-flex flex-wrap justify-content-evenly ">
            <a href="{{ route('crm.tracking', ['type' => 1]) }}"
                class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
                style="height: 10rem; width:12rem; background-color: #ff8f00;">
                <div class="w-100 d-flex justify-content-center align-items-end" style="font-size: 3rem; height: 60%">
                    <i class="bi bi-calendar-fill"></i>
                </div>
                <div class="w-100 d-flex justify-content-center align-items-center" style="font-size: 1.2rem; height: 40%">
                    <p class="card-text fw-bold">Agenda</p>
                </div>
            </a>

            <a href="#"
                class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
                style="height: 10rem; width:12rem; background-color: #196f3d;">
                <div class="w-100 d-flex justify-content-center align-items-end" style="font-size: 3rem; height: 60%">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <div class="w-100 d-flex justify-content-center align-items-center" style="font-size: 1.2rem; height: 40%">
                    <p class="card-text fw-bold">Analiticas</p>
                </div>
            </a>

            <a href="#"
                class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
                style="height: 10rem; width:12rem; background-color:#0d47a1;">
                <div class="w-100 d-flex justify-content-center align-items-end" style="font-size: 3rem; height: 60%">
                    <i class="bi bi-graph-up-arrow"></i> 
                </div>
                <div class="w-100 d-flex justify-content-center align-items-center" style="font-size: 1.2rem; height: 40%">
                    <p class="card-text fw-bold">Marketing</p>
                </div>
            </a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".card").hover(function() {
                $(this).addClass("animate__animated animate__pulse");
            }, function() {
                $(this).removeClass("animate__animated animate__pulse");
            });
        });
    </script>
@endsection
