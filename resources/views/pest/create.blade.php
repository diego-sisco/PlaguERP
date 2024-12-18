@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-3">
            <a href="{{ route('pest.index') }}" class="col-auto btn-primary p-0 fs-3"><i
                    class="bi bi-arrow-left m-3"></i></a>
            <h1 class="col-auto fs-2 fw-bold m-0">{{ __('pest.title.create') }} </h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                <form class="form" method="POST" action="{{ route('pest.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="name" class="form-label is-required">{{ __('pest.data.name') }}:
                                    </label>
                                    <input type="text" class="form-control border-secondary border-opacity-50"
                                        id="name" name="name" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="pcode" class="form-label is-required">{{ __('pest.data.code') }}:
                                    </label>
                                    <input type="text" class="form-control border-secondary border-opacity-50"
                                        id="pest-code" name="pest_code" value=0 required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="categid" class="form-label is-required">{{ __('pest.data.category') }}:
                                    </label>
                                    <select class="form-select border-secondary border-opacity-50 " id="pest-category-ID"
                                        name="pest_category_id">
                                        @foreach ($pestCategories as $pestCategory)
                                            <option value="{{ $pestCategory->id }}">{{ $pestCategory->category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="categid" class="form-label">Imagen:
                                    </label>
                                    <input type="file" class="form-control border-secondary border-opacity-50 rounded"
                                        accept=".png, .jpg, .jpeg" name="img" id="img">
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="desc" class="form-label is-required">{{ __('pest.data.description') }}: </label>
                            <textarea class="form-control border-secondary border-opacity-50 h-100" placeholder="Descripción de la plaga"
                                id="floatingTextarea" name="description" required>S/D</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary my-3">
                        {{ __('buttons.store') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
