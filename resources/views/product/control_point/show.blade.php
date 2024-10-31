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
                <a href="{{ Route('point.show', ['id' => $point->id, 'section' => 1]) }}" class="sidebar col-12 p-2 text-center">
                    Datos generales
                </a>
                <a href="{{ Route('point.show', ['id' => $point->id, 'section' => 2]) }}" class="sidebar col-12 p-2 text-center">
                    Preguntas
                </a>
            </div>
        </div>

        <div class="col-11">
            <div class="row p-3 border-bottom">
                <a href="{{ route('point.index', ['page' => 1]) }}" class="col-auto btn-primary p-0 fs-3"><i
                        class="bi bi-arrow-left m-3"></i></a>
                <h1 class="col-auto fs-2 fw-bold m-0">Ver punto de control
                </h1>
            </div>
            <div class="row p-5 pt-3">
                @switch($section)
                    @case(1)
                        <div class="col-12">
                            <div class="row">
                                <span class="col fw-bold">Color: </span>
                                <span class="col fw-normal">
                                    <div class="w-25">
                                        <div class="rounded w-25" style="background-color: {{ $point->color }}; height: 30px;">
                                        </div>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span class="col fw-bold">Nombre: </span>
                                <span class="col fw-normal">{{ $point->product->name }}</span>
                            </div>
                            <div class="row">
                                <span class="col fw-bold">Status: </span>
                                <span class="col fw-normal"> {!! $point->product->status == 1
                                    ? '<i class="bi bi-check2 text-success"></i> Activo'
                                    : '<i class="bi bi-x text-danger"></i> Inactivo' !!} </span>
                            </div>
                            <div class="row">
                                <span class="col fw-bold">Línea de negocio: </span>
                                <span class="col fw-normal">{{ $point->product->businessLine->name }}</span>
                            </div>
                            <div class="row">
                                <span class="col fw-bold">Finalidad: </span>
                                <span class="col fw-normal">{{ $point->product->purpose->name }}</span>
                            </div>
                            <div class="row">
                                <span class="col fw-bold">Producto asociado: </span>
                                <span class="col fw-normal {{ isset($point->product) ? 'text-black' : 'text-danger' }}">
                                    {{ isset($point->product) ? $point->product->name : 'S/N' }}
                                </span>
                            </div>
                        </div>
                    @break

                    @case(2)
                        <div class="col-12">
                            @if ($point->questions)
                                <table class="table table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('product.point.question') }} </th>
                                            <th scope="col">{{ __('product.point.op_question') }} </th>
                                            <th scope="col">{{ __('product.point.description') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($point->questions as $question)
                                            <tr>
                                                <th scope="row">{{ $question->question }}</th>
                                                <td>{{ $question->option->value }}</td>
                                                <td>{{ $question->option->description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <span class="col fw-bold">Sin preguntas asociadas</span>
                            @endif
                        </div>
                    @break
                @endswitch
            </div>
        </div>
    </div>
@endsection
