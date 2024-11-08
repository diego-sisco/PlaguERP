@extends('layouts.app')

@section('content')
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
                <a href="{{ Route('point.edit', ['id' => $point->id, 'section' => 1]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Datos generales
                </a>
                <a href="{{ Route('point.edit', ['id' => $point->id, 'section' => 2]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Preguntas
                </a>
            </div>
        </div>

        <div class="col-11">
            <div class="row p-3 border-bottom">
                <a href="{{ route('point.index') }}" class="col-auto btn-primary p-0 fs-3"><i
                        class="bi bi-arrow-left m-3"></i></a>
                        <h1 class="col-auto fs-2 m-0"> {{ __('control_point.title.edit') }} <span class="fw-bold">[{{$point->name . ' ' . $point->id}}]</span>
                </h1>
            </div>
            <div class="row p-5 pt-3">
                @switch($section)
                    @case(1)
                        @include('control_point.edit.form')
                    @break

                    @case(2)
                        <div class="col-12 mb-3">
                            <a href="{{ route('question.create', ['pointId' => $point->id]) }}"
                                class="btn btn-primary btn-sm me-3">
                                <i class="bi bi-plus-lg"></i> Crear pregunta
                            </a>
                            <button type="button" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal"
                                data-bs-target="#questionModal">
                                <i class="bi bi-nut-fill"></i> Asignar pregunta
                            </button>
                        </div>

                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('product.point.question') }} </th>
                                    <th scope="col"> Respuesta(s) </th>
                                    <th scope="col">{{ __('product.point.description') }}</th>
                                    <th scope="col">{{ __('buttons.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($point->questions as $question)
                                    <tr>
                                        <th scope="row">{{ $question->id }}</th>
                                        <td> {{ $question->question }} </td>
                                        <td> {{ $question->option->value }} </td>
                                        <td> {{ $question->option->description }} <span class="fw-bold">{{ $question->option->value }}</span></td>
                                        <td>
                                            @can('write_product')
                                                <a href="{{ route('question.destroy', $question->id) }}" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('{{ __('messages.are_you_sure_remove') }}')"><i
                                                        class="bi bi-x-lg"></i> {{ __('buttons.remove') }}</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @break
                @endswitch
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <form class="modal-dialog" action="{{ Route('question.set', ['pointId' => $point->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="questionModalLabel">Asignar pregunta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        @foreach ($questions as $i => $question)
                            <li class="list-group-item d-flex">
                                <input class="form-check-input border-secondary question" type="checkbox"
                                    value="{{ $question->id }}"
                                    {{ in_array($question->id, $point->questions->pluck('id')->toArray()) ? 'checked' : '' }}
                                    onchange="setQuestions()">
                                <ul style="list-style-type: none;">
                                    <li><span class="fw-bold">ID</span>: {{ $question->id }}</li>
                                    <li><span class="fw-bold">Pregunta</span>: {{ $question->question }}</li>
                                    <li><span class="fw-bold">Respuesta(s)</span>: {{ $question->option->value }}</li>
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                    <input type="hidden" id="selected-question" name="selected_question" value="">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('buttons.accept') }}</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"> {{ __('buttons.cancel') }} </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(() => {
            setQuestions();
        });

        function setQuestions() {
            var input = '#selected-question';
            var questionClass = '.question';
            var checkedArray = [];

            $(questionClass).each(function() {
                if ($(this).is(':checked')) {
                    checkedArray.push(parseInt($(this).val()));
                }
            });

            $(input).val(JSON.stringify(checkedArray));
        }

        function setProducts() {
            var input = '#selected-products'
            var productClass = '.product';
            var checkedProducts = []
            $(productClass).each(function() {
                if ($(this).is(':checked')) {
                    checkedProducts.push(parseInt($(this).val()));
                }
            });
            $(input).val(JSON.stringify(checkedProducts));
            console.log($(input).val())
        }
    </script>

@endsection
