@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="w-100 m-0">
        <div class="row w-100 justify-content-between p-3 m-0 mb-3">
            <!-- Header -->
            <div class="col-12 border-bottom pb-2">
                <div class="row">
                    <a href="{{ route('pest.index') }}" class="col-auto btn-primary p-0 fs-3"><i
                            class="bi bi-arrow-left m-3"></i></a>
                    <h1 class="col-auto fs-2 fw-bold m-0">{{ __('pest.title.edit') }}</h1>
                </div>
            </div>
            <div class="col-12">

                <form class="form p-5 pt-3" method="POST" action="{{ route('pest.update', $pest->id) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-6 mb-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label for="name"
                                        class="form-label is-required">{{ __('pagination.pest_catalog.nom') }}:
                                    </label>
                                    <input type="text" class="form-control border-secondary border-opacity-50" id="name"
                                        name="name" value="{{ $pest->name }}" required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label for="pcode"
                                        class="form-label is-required">{{ __('pagination.pest_catalog.pcode') }}:
                                    </label>
                                    <input type="text" class="form-control border-secondary border-opacity-50" id="pest-code"
                                        name="pest_code" value="{{ $pest->pest_code }}" required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label for="categid"
                                        class="form-label is-required">{{ __('pagination.pest_catalog.categ') }}:
                                    </label>
                                    <select class="form-select border-secondary border-opacity-50 " id="pest-category-id" name="pest_category_id">
                                        @foreach ($categs as $categ)
                                            @if ($categ->id == $pest->pest_category_id)
                                                <option value="{{ $categ->id }}" selected>{{ $categ->category }}</option>
                                            @else
                                                <option value="{{ $categ->id }}">{{ $categ->category }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="categid" class="form-label">Imagen: </label>
                                    <input type="file" class="form-control border-secondary border-opacity-50 rounded"
                                        accept=".png, .jpg, .jpeg" name="img" id="img" value="{{ $pest->image }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <label for="desc"
                                class="form-label is-required">{{ __('pagination.pest_catalog.desc') }}</label>
                            <textarea class="form-control border-secondary border-opacity-50 h-100" placeholder="Descripción de la plaga" id="description"
                                name="description" required> {{ $pest->description }} </textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" onclick="">
                        {{ __('buttons.update') }}
                    </button>                    
                </form>

            </div>
        </div>
    </div>
@endsection
