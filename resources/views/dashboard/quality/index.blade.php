@extends('layouts.app')
@section('content')
    @php
        $page = $customers->currentPage();
        $index = $size * ($page - 1) + 1;
    @endphp

    <div class="row w-100 justify-content-between m-0 h-100">
        <div class="col-1 m-0" style="background-color: #343a40;">
            @include('dashboard.quality.navigation')
        </div>
        <div class="col-11">
            <div class="container-fluid pt-3">
                <div class="row justify-content-end">
                    <div class="col-4">
                        <div type="browser" class="row mb-3">
                            @include('dashboard.quality.browser')
                        </div>
                    </div>
                </div>
                @include('messages.alert')
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Id</th>
                                <th scope="col">
                                    Cliente
                                </th>
                                <th scope="col">Tipo</th>
                                <th scope="col-2">Administrado por</th>
                                <th scope="col">{{ __('buttons.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @foreach ($customers as $customer)
                                <tr id="customer{{ $customer->id }}">
                                    <th scope="row">{{ $index }}</th>
                                    <td class="text-primary">{{ $customer->id }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>
                                        {{ $customer->serviceType->name }}
                                    </td>
                                    <td> {{ $customer->administrative->name ?? 'S/A' }} </td>
                                    <td>
                                        @can('write_order')
                                            <a href="{{ route('quality.customer', ['id' => $customer->id]) }}" class="btn btn-info btn-sm"><i class="bi bi-eye-fill"></i>
                                                {{ __('buttons.show') }}</a>
                                        @endcan
                                    </td>
                                </tr>
                                @php $index++  @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $customers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
