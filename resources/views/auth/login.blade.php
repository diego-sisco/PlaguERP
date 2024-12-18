@extends('layouts.app')
@section('login')

    <div class="w-100 h-100 m-0">
        <div class="row h-100 m-0">
            <div class="col-4 bg-dark h-100 p-0">
                <div class="row justify-content-center align-items-center h-100 m-0">
                    <div class="p-5">
                        <img src="{{ asset('images/logo.png') }}" class="img-fluid w-100" alt="Logo" />
                    </div>
                </div>
            </div>
            <div class="col-8 bg-body-secondary h-100 p-0">
                <div class="d-flex justify-content-center h-100 m-0">
                    <div class="w-50">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="bg-white border rounded shadow p-3">
                                <form class="form p-3 h-100" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="row h-100">
                                        <div
                                            class="col-12 d-flex justify-content-center align-items-center p-2 text-center">
                                            <h1 class="fw-bold">
                                                {{ __('auth.welcome') }} </h1>
                                        </div>

                                        @if ($errors->any())
                                            <div class="alert alert-danger fs-6 mt-3 mb-3 p-1 text-center">
                                                @foreach ($errors->all() as $error)
                                                    <span>{{ $error }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="col-12 mb-3">
                                            <label for="exampleInputEmail1" class="form-label"> {{ __('auth.email') }}:
                                            </label>
                                            <input type="email" class="form-control border-secondary border-opacity-50"
                                                id="exampleInputEmail1" name="email" aria-describedby="emailHelp"
                                                maxlength="50">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="exampleInputPassword1" class="form-label">
                                                {{ __('auth.password') }}:
                                            </label>
                                            <div class="input-group flex-nowrap">
                                                <input type="password"
                                                    class="form-control border-secondary border-opacity-50" id="password"
                                                    name="password" autocomplete=off maxlength="50" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="togglePassword"><i id="eye-icon-pass"
                                                        class="bi bi-eye-fill"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <button type="submit" class="btn btn-success">{{ __('auth.login') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
