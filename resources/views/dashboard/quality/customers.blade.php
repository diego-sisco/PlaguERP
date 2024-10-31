@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php
        header('Location: /login');
        exit();
        ?>
    @endif

    <div class="row w-100 h-100 m-0">
        @include('dashboard.quality.navigation')
        <div class="col-11">
            <div class="row p-3 m-0">

                
            </div>
            <div class="row p-3 pt-0 m-0 justify-content-center">
                {{-- @include('layouts.pagination.quality') --}}
            </div>
        </div>
    </div>
@endsection