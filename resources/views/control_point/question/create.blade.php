@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-3">
            <a href="javascript:history.back()" class="col-auto btn-primary p-0 fs-3"><i class="bi bi-arrow-left m-3"></i></a>
            <h1 class="col-auto fs-2 fw-bold m-0">{{ __('control_point.title.create') }}</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                <form method="POST" class="form"
                    action="{{ route('question.store', ['pointId' => $pointId]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col mb-3">
                            <label for="question" class="form-label is-required">Texto de la pregunta: </label>
                            <input type="text" class="form-control border-secondary border-opacity-50" id="question"
                                name="question" required>
                        </div>
                    </div>
                    <div class="row">
                        <h5 class="fw-bold">{{ __('control_point.title.options') }}</h5>
                        <div class="col">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Valor</th>
                                        <th class="text-center" scope="col">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($question_options as $option)
                                        <tr>
                                            <td><input type="radio" name="question_option_id" value="{{ $option->id }}">
                                                {{ $option->value }} </td>
                                            <td class="text-center">{{ $option->description }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary my-3">Guardar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showSelect(selectedValue) {
            var select = document.getElementById("category_select");
            if (selectedValue == 12) {
                select.style.display = "block";
            } else {
                select.style.display = "none";
            }
        }
    </script>
@endsection
