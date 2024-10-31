@extends('layouts.app')

@section('content')
    <div class="w-100 m-0">
        <div class="row w-100 justify-content-between p-3 m-0 mb-3">
            <!-- Header -->
            <div class="col-12 border-bottom pb-2">
                <div class="row">
                    <a href="javascript:history.back()" class="col-auto btn-primary p-0 fs-3"><i
                            class="bi bi-arrow-left m-3"></i></a>
                    <h1 class="col-auto fs-2 fw-bold m-0">{{ __('product.title-product.crea_question') }}</h1>
                </div>
            </div>
            <div class="col-12">
                <form method="POST" class="form p-5 pt-3"
                    action="{{ route('question.store', ['pointID' => $pointID, 'section' => $section]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col mb-3">
                            <label for="ask" class="form-label">Texto de la pregunta: </label>
                            <input type="text" class="form-control border-secondary border-opacity-25" id="ask"
                                name="ask">
                        </div>

                        <label for="ask" class="form-label">Tipo de respuesta admitida: </label>
                        <table class="table m-2 mt-0">
                            <thead>
                                <tr>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($question_options as $option)
                                    <tr>
                                        <td><input type="radio" name="selected_option" value="{{ $option->id }}">
                                            {{ $option->value }} </td>
                                        <td>{{ $option->description }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
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