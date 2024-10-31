@extends('layouts.app')
@section('content')
    <!-- Formulario para ver un pesticida-->
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    @php
        function isPDF($filePath)
        {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $extension = strtolower($extension);
            return $extension === 'pdf' || $extension == 'PDF';
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
    </style>
    <div class="row w-100 justify-content-between m-0 h-100">
        <div class="col-1 m-0" style="background-color: #343a40;">
            <div class="row">
                <a href="{{ Route('product.show', ['id' => $product->id, 'section' => 1]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Datos generales
                </a>
                <a href="{{ Route('product.show', ['id' => $product->id, 'section' => 2]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Datos económicos
                </a>
                <a href="{{ Route('product.show', ['id' => $product->id, 'section' => 3]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Plagas
                </a>
                <a href="{{ Route('product.show', ['id' => $product->id, 'section' => 4]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Archivos
                </a>
            </div>
        </div>

        <div class="col-11 p-5 pt-3">
            @if ($section == 1)
                <div class="row">
                    <div class="col-3 p-3">
                        <img class="border border-1 rounded p-3 img-fluid" src="{{ asset($product->photo) }}"
                            alt="Miniatura de imagen">
                    </div>
                    <div class="col-9 p-3">
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.name') }} :</span>
                            <span class="col fw-normal">{{ $product->name }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.business_name') }} :</span>
                            <span class="col fw-normal">{{ $product->bussiness_name }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.bar_c') }} :</span>
                            <span class="col fw-normal">{{ $product->bar_code ? $product->bar_code : 'S/N' }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">Status :</span>
                            <span class="col fw-normal">
                                @if ($product->status == 1)
                                    Activo
                                    <i class="bi bi-check2" style="color: green"></i>
                                @else
                                    Inactivo <i class="bi bi-x" style="color: brown"></i>
                                @endif
                            </span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.obsolete') }} :</span>
                            <span class="col fw-normal">{{ $product->obsolete == 0 ? 'No' : 'Si' }}</span>
                        </div>

                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.pres') }} :</span>
                            <span class="col fw-normal">{{ $product->presentation->name }}</span>
                        </div>

                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.line_b') }}</span>
                            <span class="col fw-normal">{{ $product->businessLine->name }}</span>
                        </div>

                        
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.porp') }} :</span>
                            <span class="col fw-normal">{{ $product->purpose->name }}</span>
                        </div>

                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.manufacturer') }} :</span>
                            <span class="col fw-normal">{{ $product->manufacturer }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">Descripción:</span>
                            <span class="col fw-normal">{{ $product->description }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.indications_execution') }} :</span>
                            <span class="col fw-normal">{{ $product->indications_execution }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.register_number') }} :</span>
                            <span class="col fw-normal">{{ $product->register_number }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.valid_date') }} :</span>
                            <span class="col fw-normal">{{ $product->validity_date }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.active_ingredient') }} :</span>
                            <span class="col fw-normal">{{ $product->active_ingredient }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.peractive_ingredient') }} :</span>
                            <span class="col fw-normal">{{ $product->per_active_ingredient }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.dos') }} :</span>
                            <span class="col fw-normal">{{ $product->dosage }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.safety_period') }} :</span>
                            <span class="col fw-normal">{{ $product->safety_period }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.res_ef') }} :</span>
                            <span class="col fw-normal">{{ $product->residual_effect }}</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.type_b') }} :</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.grup_b') }} :</span>
                        </div>
                        <div class="row">
                            <span class="col fw-bold">{{ __('product.product-data.tox') }} :</span>
                            <span class="col fw-normal">{{ $product->toxicity == 1 ? 'Si' : 'No' }}</span>
                        </div>
                        @if ($product->toxicity == 1)
                            <div class="row">
                                <span class="col fw-bold">{{ __('product.product-data.tox_c') }} :</span>
                                <span class="col fw-normal">{{ $product->toxicityCategory->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($section == 2)
                <div class="row">
                    <span class="col fw-bold">{{ __('product.econom-data-product.purchase_price') }} :</span>
                    <span class="col fw-normal">{{ $product->economicData->purchase_price }}</span>
                </div>
                <div class="row">
                    <span class="col fw-bold">{{ __('product.econom-data-product.min_purchase_unit') }} :</span>
                    <span class="col fw-normal">{{ $product->economicData->min_purchase_unit }}</span>
                </div>
                <div class="row">
                    <span class="col fw-bold">{{ __('product.econom-data-product.mult_purchase') }} :</span>
                    <span class="col fw-normal">{{ $product->economicData->mult_purchase }}</span>
                </div>
                <div class="row">
                    <span class="col fw-bold">{{ __('product.econom-data-product.supplier') }} :</span>
                    <span class="col fw-normal">{{ $product->economicData->supplier->name }}</span>
                </div>
                <div class="row">
                    <span class="col fw-bold">Es vendible? </span>
                    @if ($product->economicData->selling == 1)
                        <span class="col fw-normal">Si</span>
                    @else
                        <span class="col fw-normal">No</span>
                    @endif
                </div>
                <div class="row">
                    <span class="col fw-bold">{{ __('product.econom-data-product.selling_price') }} :</span>
                    <span class="col fw-normal">{{ $product->economicData->selling_price }}</span>
                </div>
                <div class="row">
                    <span class="col fw-bold">{{ __('product.econom-data-product.subaccount_purchases') }} :</span>
                    <span class="col fw-normal">{{ $product->economicData->subaccount_purchases }}</span>
                </div>
                <div class="row">
                    <span class="col fw-bold">{{ __('product.econom-data-product.subaccount_sales') }} :</span>
                    <span class="col fw-normal">{{ $product->economicData->subaccount_sales }}</span>
                </div>
            @endif

            @if ($section == 3)
                @if (!$product->pests->isEmpty())
                    @foreach ($product->pests as $i => $pest)
                        <div class="row">
                            <span class="col fw-bold">Plaga {{ $i + 1 }} :</span>
                            <span class="col fw-normal">{{ $pest->name }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-danger"> Sin plagas asociadas </p>
                @endif
            @endif
        </div>
    </div>
@endsection
