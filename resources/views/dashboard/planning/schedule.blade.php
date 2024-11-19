@extends('layouts.app')
@section('content')
    @if (!auth()->check())
        <?php header('Location: /login');
        exit(); ?>
    @endif

    <div class="row w-100 justify-content-between m-0 h-100">
        @include('dashboard.planning.navigation')
        <div class="col-11 p-3 container">
            <div class="col-12">
                <div class="row justify-content-between mb-3">
                    <div class="col-auto">
                        <form class="form input-group rounded" action="{{ route('planning.schedule') }}" method="GET">
                            <input type="text" class="form-control" id="date-range" name="date"
                                value="{{ $date }}" />
                            <button class="btn btn-success btn-sm " type="submit" id="button-addon1">
                                {{ __('buttons.search') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-4 text-center">
                                <span><i class="bi bi-square-fill" style="color:#c0392b"></i> Domestico</span>
                            </div>
                            <div class="col-4 text-center">
                                <span><i class="bi bi-square-fill" style="color:#196f3d"></i> Comercial</span>
                            </div>
                            <div class="col-4 text-center">
                                <span><i class="bi bi-square-fill" style="color:#1f618d"></i> Industrial</span>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table text-center">
                    <thead>
                        <tr>
                            <th scope="col">Hora</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">#</th>
                            <th scope="col">Hora programada</th>
                            <th scope="col">Fecha programada</th>
                            <th scope="col">Cliente(s)</th>
                            <th scope="col">Status</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daily_program as $daily)
                            @php
                                $size = count($daily['activities']);
                            @endphp
                            <tr>
                                <th class="text-center" scope="row" rowspan="{{ $size != 0 ? $size+1 : 1 }}">
                                    {{ $daily['hour'] }}
                                </th>
                                @if ($size == 1)
                                    @for ($i = 0; $i < $size; $i++)
                                        <td> <i class="bi bi-square-fill"
                                                style="color: {{ $daily['activities'][$i]['service_type'] == 1 ? '#c0392b' : ($daily['activities'][$i]['service_type'] == 2 ? '#196f3d' : '#1f618d') }};"></i>
                                        </td>
                                        <td> {{ $daily['activities'][$i]['id'] }} </td>
                                        <td> {{ $daily['activities'][$i]['start_time'] }} </td>
                                        <td> {{ $daily['activities'][$i]['programmed_date'] }} </td>
                                        <td>
                                            {{ $daily['activities'][$i]['customer'] }}
                                        </td>
                                        <td
                                            class="fw-bold 
                                            {{ $daily['activities'][$i]['status_id'] == 1
                                                ? 'text-warning'
                                                : ($daily['activities'][$i]['status_id'] == 2 || $daily['activities'][$i]['status_id'] == 3 || $daily['activities'][$i]['status_id'] == 5
                                                    ? 'text-primary'
                                                    : ($daily['activities'][$i]['status_id'] == 4
                                                        ? 'text-success'
                                                        : 'text-danger')) }}
                                        ">
                                            {{ $daily['activities'][$i]['status'] }}
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('order.show', ['id' => $daily['activities'][$i]['id'], 'section' => 1]) }}">
                                                <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                                            </a>
                                            @can('write_order')
                                                <a class="btn btn-secondary btn-sm"
                                                    href="{{ route('order.edit', ['id' => $daily['activities'][$i]['id']]) }}">
                                                    <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                                </a>
                                            @endcan
                                            @can('write_order')
                                                <a class="btn btn-dark btn-sm"
                                                    href="{{ route('report.review', ['id' => $daily['activities'][$i]['id']]) }}">
                                                    <i class="bi bi-file-pdf-fill"></i> {{ __('buttons.report') }}
                                                </a>
                                            @endcan
                                        </td>
                                    @endfor
                                @endif
                            </tr>
                            @if ($size >= 2)
                                @for ($i = 0; $i < $size; $i++)
                                    <tr>
                                        <td> <i class="bi bi-square-fill"
                                            style="color: {{ $daily['activities'][$i]['service_type'] == 1 ? '#c0392b' : ($daily['activities'][$i]['service_type'] == 2 ? '#196f3d' : '#1f618d') }};"></i>
                                    </td>
                                        <td> {{ $daily['activities'][$i]['id'] }} </td>
                                        <td> {{ $daily['activities'][$i]['start_time'] }} </td>
                                        <td> {{ $daily['activities'][$i]['programmed_date'] }} </td>
                                        <td>
                                            {{ $daily['activities'][$i]['customer'] }}
                                        </td>
                                        <td
                                            class="fw-bold 
                                            {{ $daily['activities'][$i]['status_id'] == 1
                                                ? 'text-warning'
                                                : ($daily['activities'][$i]['status_id'] == 2 || $daily['activities'][$i]['status_id'] == 3 || $daily['activities'][$i]['status_id'] == 5
                                                    ? 'text-primary'
                                                    : ($daily['activities'][$i]['status_id'] == 4
                                                        ? 'text-success'
                                                        : 'text-danger')) }}
                                        ">
                                            {{ $daily['activities'][$i]['status'] }}
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('order.show', ['id' => $daily['activities'][$i]['id'], 'section' => 1]) }}">
                                                <i class="bi bi-eye-fill"></i> {{ __('buttons.show') }}
                                            </a>
                                            @can('write_order')
                                                <a class="btn btn-secondary btn-sm"
                                                    href="{{ route('order.edit', ['id' => $daily['activities'][$i]['id']]) }}">
                                                    <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                                                </a>
                                                <a class="btn btn-dark btn-sm"
                                                    href="{{ route('report.review', ['id' => $daily['activities'][$i]['id']]) }}">
                                                    <i class="bi bi-file-pdf-fill"></i> {{ __('buttons.report') }}
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endfor
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('#date-range').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'DD/MM/YYYY' // Cambiar el formato aquí
                },
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Esta semana': [moment().startOf('week'), moment().endOf('week')],
                    'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                    'Este mes': [moment().startOf('month'), moment().endOf('month')],
                    'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                    'Este año': [moment().startOf('year'), moment().endOf('year')],
                },
                alwaysShowCalendars: true
            });
        });
    </script>
@endsection
