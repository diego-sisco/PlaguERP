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
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID</th>
                            <th scope="col"> {{ __('customer.data.name') }} </th>
                            <th scope="col"> {{ __('customer.data.type') }}</th>
                            <th scope="col">{{ __('customer.data.origin') }}</th>
                            <th scope="col"> Pendientes </th>
                            <th scope="col"> Finalizados </th>
                            <th scope="col"> Verificadas </th>
                            <th scope="col"> Aprobadas </th>
                            <th scope="col"> {{ __('buttons.actions') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $index => $customer)
                            <tr>
                                <th scope="row">{{ ++$index }}</th>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->serviceType->name }}</td>
                                <td>{{ isset($customer->matrix->name) ? $customer->matrix->name : 'Matriz/Unico' }}
                                </td>
                                <td class="fw-bold text-danger">
                                    {{ $customer->countOrdersbyStatus(1) }}
                                </td>
                                <td class="fw-bold text-info">
                                    {{ $customer->countOrdersbyStatus(3) }}
                                </td>
                                <td class="fw-bold text-primary">
                                    {{ $customer->countOrdersbyStatus(4) }}
                                </td>
                                <td class="fw-bold text-success">
                                    {{ $customer->countOrdersbyStatus(5) }}
                                </td>
                                <td>
                                    <a href="{{ route('customer.show', ['id' => $customer->id, 'type' => 1, 'section' => 1]) }}"
                                        class="btn btn-info btn-sm mb-1">
                                        <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                                    </a>
                                    @can('write_customer')
                                        <a href="{{ route('customer.edit', ['id' => $customer->id, 'type' => 1, 'section' => 1]) }}"
                                            class="btn btn-secondary btn-sm mb-1">
                                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row p-3 pt-0 m-0 justify-content-center">
                {{-- @include('layouts.pagination.quality') --}}
            </div>
        </div>
    </div>
@endsection
