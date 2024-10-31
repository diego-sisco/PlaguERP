@extends('layouts.app') @section('content') @if (!auth()->check()) <?php
header('Location: /login'); exit(); ?> @endif

<style>
    .sidebar {
        color: white;
        text-decoration: none;
    }

    .sidebar:hover {
        background-color: #e9ecef;
        color: #212529;
    }

    .directory:hover {
        text-decoration: underline !important;
        color: #0d6efd !important;
    }
</style>

<div class="w-100 h-100 m-0 p-5">
    <h1 class="fw-bold text-center mb-5">
        BIENVENIDO AL SISTEMA DE CLIENTES POR SISCOPLAGAS
    </h1>
    <div class="d-flex flex-wrap justify-content-evenly">
        <a
            href="{{ route('client.system.index', ['path' => $path]) }}"
            class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
            style="height: 10rem; width: 12rem; background-color: #ff8f00"
        >
            <div
                class="w-100 d-flex justify-content-center align-items-end"
                style="font-size: 3rem; height: 60%"
            >
                <i class="bi bi-folder-fill"></i>
            </div>
            <div
                class="w-100 d-flex justify-content-center align-items-center"
                style="font-size: 1.2rem; height: 40%"
            >
                <p class="card-text fw-bold">Carpetas y archivos</p>
            </div>
        </a>

        <a
            href="{{ route('client.reports.index', ['section' => 1]) }}"
            class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
            style="height: 10rem; width: 12rem; background-color: #01579b"
        >
            <div
                class="w-100 d-flex justify-content-center align-items-end"
                style="font-size: 3rem; height: 60%"
            >
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div
                class="w-100 d-flex justify-content-center align-items-center"
                style="font-size: 1.2rem; height: 40%"
            >
                <p class="card-text fw-bold">Reportes</p>
            </div>
        </a>

        @can('write_client')
        <a
            href="{{ route('client.mip.index', ['path' => $mip_path]) }}"
            class="d-flex flex-column align-items-center justify-content-center card shadow text-center text-white text-decoration-none p-2 m-2"
            style="height: 10rem; width: 12rem; background-color: #4e342e"
        >
            <div
                class="w-100 d-flex justify-content-center align-items-end"
                style="font-size: 3rem; height: 60%"
            >
                <i class="bi bi-folder2-open"></i>
            </div>
            <div
                class="w-100 d-flex justify-content-center align-items-center"
                style="font-size: 1.2rem; height: 40%"
            >
                <p class="card-text fw-bold">MIP</p>
            </div>
        </a>
        @endcan
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".card").hover(
            function () {
                $(this).addClass("animate__animated animate__pulse");
            },
            function () {
                $(this).removeClass("animate__animated animate__pulse");
            },
        );
    });
</script>
@endsection
