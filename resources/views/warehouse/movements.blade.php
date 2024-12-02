@extends('layouts.app')

@section('content')
    <div class="row w-100 h-100 m-0">
        <div class="col-1 m-0" style="background-color: #343a40;">
            @include('dashboard.inventory.navigation')
        </div>
        <div class="col-11 p-3 m-0">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="table-responsive">
                        <table class="table text-center table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Movimiento</th>
                                    <th>Tipo de Movimiento</th>
                                    <th>Almacén Origen</th>
                                    <th>Almacén Destino</th>
                                    <th>Producto</th>
                                    <th>Creado por</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $movement)
                                    <tr>
                                        <td>{{ $movement->date }}</td>
                                        <td>{{ $movement->time }}</td>
                                        <td
                                            class="fw-bold {{ $movement->movement_id <= 4 && $movement->movement_id >= 1 ? 'text-success' : 'text-danger' }}">
                                            {{ $movement->movement_id <= 4 && $movement->movement_id >= 1 ? 'Entrada' : 'Salida' }}
                                        </td>
                                        <td>{{ $movement->movementType ? $movement->movementType->name : 'N/A' }}</td>
                                        <td>{{ $warehouse->name ?? 'N/A' }}</td>
                                        <td>{{ $movement->destinationWarehouse ? $movement->destinationWarehouse->name : 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $movement->product->name }}: <span class="fw-bold {{ $movement->movement_id <= 4 && $movement->movement_id >= 1 ? 'text-success' : 'text-danger' }} ">{{ $movement->amount }} {{ $movement->product->metric->value }} </span>
                                        </td>
                                        <td>{{ $movement->user ? $movement->user->name : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('warehouse.pdf', ['id' => $movement->id]) }}"
                                                class="btn btn-dark btn-sm"><i class="bi bi-file-pdf-fill"></i> Imprimir</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-danger" colspan="8">No hay movimientos en este almacén.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
