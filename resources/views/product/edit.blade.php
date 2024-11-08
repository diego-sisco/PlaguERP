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

        .flat-btn {
            background-color: #55ff00;
        }
    </style>

    <div class="row w-100 justify-content-between m-0 h-100">
        <div class="col-1 m-0" style="background-color: #343a40;">
            <div class="row">
                <a class="sidebar col-12 p-2 text-center" data-bs-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    Especificación
                </a>
                <div class="collapse" id="collapseExample" style="background-color: #495057;">
                    <div class="row">
                        <a href="{{ route('product.edit', ['id' => $product->id, 'section' => 1]) }}"
                            class="sidebar col-12 p-2 text-center"> Generales
                        </a>
                        <a href="{{ route('product.edit', ['id' => $product->id, 'section' => 2]) }}"
                            class="sidebar col-12 p-2 text-center"> Técnicas
                        </a>
                        <a href="{{ route('product.edit', ['id' => $product->id, 'section' => 3]) }}"
                            class="sidebar col-12 p-2 text-center"> Toxicidad
                        </a>
                        <a href="{{ route('product.edit', ['id' => $product->id, 'section' => 4]) }}"
                            class="sidebar col-12 p-2 text-center"> Compra/Venta
                        </a>
                    </div>
                </div>
                <a href="{{ Route('product.edit', ['id' => $product->id, 'section' => 5]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Plagas
                </a>
                <a href="{{ Route('product.edit', ['id' => $product->id, 'section' => 6]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Insumo y aplicación
                </a>
                <a href="{{ Route('product.edit', ['id' => $product->id, 'section' => 7]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Archivos
                </a>
            </div>
        </div>

        <div class="col-11">
            <div class="row p-3 border-bottom">
                <a href="{{ route('product.index') }}" class="col-auto btn-primary p-0 fs-3"><i
                        class="bi bi-arrow-left m-3"></i></a>
                <h1 class="col-auto fs-2 m-0">{{ __('product.title.edit') }} <span class="fw-bold">[{{$product->name}}]</span></h1>
            </div>
            <div class="row p-3 m-0">
                @include('product.edit.form')
            </div>
        </div>
    </div>
    @include('product.edit.modals.input')
    @include('product.edit.modals.file')

    <script src="{{ asset('js/service/control.min.js') }}"></script>
@endsection
