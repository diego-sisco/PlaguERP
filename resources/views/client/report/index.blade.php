@extends('layouts.app') @section('content') 
@if (!auth()->check()) 
    <?php 
        header('Location: /login'); 
        exit(); 
    ?> 
@endif 
@php 
    function splitFileName($filename) { 
        $fileParts = explode('_', $filename);
        return $fileParts;
    } 
@endphp

<div class="row p-3">
    <div class="col-12 mb-3">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $section == 1 ? 'active' : '' }}" href="{{ route('client.reports.index', ['section' => 1]) }}">{{ __('order.navbar.new_reports')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $section == 2 ? 'active' : '' }}" href="{{ route('client.reports.index', ['section' => 2]) }}">{{ __('order.navbar.history')}}</a>
            </li>
        </ul>
    </div>
    <div class="col-12">
        @if($section == 1) 
            @include('client.report.filters.current')
        @else
            @include('client.report.filters.history')
        @endif
    </div>
    <div class="col-12">
        @if($section == 1) 
            @include('client.report.tables.current')
        @else
            @include('client.report.tables.history')
        @endif

    </div>
</div>

@endsection
