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

    <div class="w-100 h-100 m-0 p-5">
        <h1 class="fw-bold text-center mb-5"> BIENVENIDO A SISCOPLAGAS </h1>
        <div class="d-flex flex-wrap justify-content-evenly ">
            <a href="{{ route('crm.dashboard') }}"
                class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
                style="height: 10rem; width:12rem; background-color:#ff6f00;">
                <div class="w-100 d-flex justify-content-center align-items-end" style="font-size: 3rem; height: 60%">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="w-100 d-flex justify-content-center align-items-center" style="font-size: 1.2rem; height: 40%">
                    <p class="card-text fw-bold">CRM</p>
                </div>
            </a>

            <a href="{{ route('planning.schedule') }}"
                class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
                style="height: 10rem; width:12rem; background-color:#01579b;">
                <div class="w-100 d-flex justify-content-center align-items-end" style="font-size: 3rem; height: 60%">
                    <i class="bi bi-calendar-fill"></i>
                </div>
                <div class="w-100 d-flex justify-content-center align-items-center" style="font-size: 1.2rem; height: 40%">
                    <p class="card-text fw-bold">Planificación</p>
                </div>
            </a>

            <a href="{{ route('quality.orders', ['status' => 1, 'page' => 1]) }}"
                class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
                style="height: 10rem; width:12rem; background-color:#c62828">
                <div class="w-100 d-flex justify-content-center align-items-end" style="font-size: 3rem; height: 60%">
                    <i class="bi bi-gear-fill"></i>
                </div>
                <div class="w-100 d-flex justify-content-center align-items-center" style="font-size: 1.2rem; height: 40%">
                    <p class="card-text fw-bold">Calidad y gestión</p>
                </div>
            </a>

            <a href="{{ route('warehouse.index', ['is_active' => 1])}}"
                class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
                style="height: 10rem; width:12rem; background-color:#1b5e20">
                <div class="w-100 d-flex justify-content-center align-items-end" style="font-size: 3rem; height: 60%">
                    <i class="bi bi-box-fill"></i>
                </div>
                <div class="w-100 d-flex justify-content-center align-items-center" style="font-size: 1.2rem; height: 40%">
                    <p class="card-text fw-bold">Catálogo/Almacen</p>
                </div>
            </a>

            <a href="{{ route('rrhh', ['section' => 1]) }}"
                class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
                style="height: 10rem; width:12rem; background-color:#ad1457">
                <div class="w-100 d-flex justify-content-center align-items-end" style="font-size: 3rem; height: 60%">
                    <i class="bi bi-person-fill-gear"></i>
                </div>
                <div class="w-100 d-flex justify-content-center align-items-center" style="font-size: 1.2rem; height: 40%">
                    <p class="card-text fw-bold">RRHH</p>
                </div>
            </a>

                <a href="{{ route('client.index') }}"
                    class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
                    style="height: 10rem; width:12rem; background-color: #273746">
                    <div class="w-100 d-flex justify-content-center align-items-end" style="font-size: 3rem; height: 60%">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <div class="w-100 d-flex justify-content-center align-items-center" style="font-size: 1.2rem; height: 40%">
                        <p class="card-text fw-bold">Sistema de clientes</p>
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
