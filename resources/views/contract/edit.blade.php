@extends('layouts.app')
@section('content')

@if (!auth()->check())
    <?php header("Location: /login"); exit; ?>
@endif

<div class="border rounded shadow m-3 p-2 bg-white" id=" accordionEdit">
    <div class="edit-title d-flex justify-content-start align-items-center gap-2 text-center">
        <a href="{{ route('contract.index') }}" class="btn-primary p-0 fs-3"><i class="bi bi-arrow-left m-3"></i></a>
        <h1 class="fs-2 fw-bold m-0">{{ __('modals.title.edit_user') }}</h1>
    </div>
    <div class="accordion m-4" id="accordionExample">
        <div class="accordion-item mb-1">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark-subtle text-emphasis-dark" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <strong> {{ __('user.title.personal_information') }} </strong>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    @include('user.edit.personal')
                </div>
            </div>
        </div>
        <div class="accordion-item mb-1">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark-subtle text-emphasis-dark" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <strong> {{ __('user.title.work_information') }} </strong>
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    @include('user.edit.work')
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-dark-subtle text-emphasis-dark" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <strong> {{ __('user.title.files') }} </strong>
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    @include('user.edit.files')
                </div>
            </div>
        </div>
       
    </div>
</div>


<script src="{{ asset('js/user/actions.min.js') }}"></script>
<script src="{{ asset('js/user/validations.min.js') }}"></script>

@endsection