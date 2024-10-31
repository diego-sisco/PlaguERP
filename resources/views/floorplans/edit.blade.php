@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    @php
        $pointNames = [];
        $areaNames = [];
        $productNames = [];
        $image = route('image.show', ['filename' => $floorplan->path]);

        foreach ($products as $product) {
            $productNames[] = [
                'id' => $product->id,
                'name' => $product->name,
            ];
        }
    @endphp

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
                <a href="{{ Route('floorplans.edit', ['id' => $floorplan->id, 'customerID' => $customer->id, 'type' => $type, 'section' => 1]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Datos generales
                </a>
                <a href="{{ Route('floorplans.edit', ['id' => $floorplan->id, 'customerID' => $customer->id, 'type' => $type, 'section' => 2]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Puntos de control
                </a>
            </div>
        </div>

        <div class="col-11">
            <div class="row p-3 border-bottom">
                <a href="{{ Route('customer.edit', ['id' => $customer->id, 'type' => $type, 'section' => 8]) }}"
                    class="col-auto btn-primary p-0 fs-3"><i class="bi bi-arrow-left m-3"></i></a>
                <h1 class="col-auto fs-2 fw-bold m-0">{{ __('modals.title.edit_floorplan') }}</h1>
            </div>
            <div class="row p-5 pt-3">
                <form id="form" class="form" method="POST"
                    action="{{ Route('floorplans.update', ['id' => $floorplan->id, 'section' => $section]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if ($section == 1)
                        @include('floorplans.edit.form')
                    @endif

                    @if ($section == 2)
                        @include('floorplans.edit.devices')
                    @endif
                </form>
            </div>
        </div>
    </div>

    
@endsection
