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
    <div class="row w-100 h-100 m-0">
        <div class="col-1 m-0" style="background-color: #343a40;">
            @include('dashboard.inventory.navigation')
        </div>
        <div class="col-11 p-4">
            <div class="row justify-content-between p-0 m-0 mb-3">
                <div class="col-auto p-0">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLotModal">
                        <i class="bi bi-plus-lg fw-bold"></i> Crear lote
                    </button>
                </div>

                <div class="col-4">
                    <div type="browser" class="row mb-3">
                        
                    </div>
                </div>
            </div>

            @include('lot.tables.index')
        </div>
    </div>

    @include('lot.create.modals.create')
@endsection
