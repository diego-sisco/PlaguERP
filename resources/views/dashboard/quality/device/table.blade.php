

<table class="table table-bordered text-center">
    <thead>

            <tr>
                <th scope="col">#</th>
                <th scope="col">Id</th>
                <th scope="col">
                    Nombre
                </th>
                <th scope="col">Cantidad Total
                </th>
                <th class="col" scope="col">Codigo
                </th>
                <th class="col-4" scope="col">Planos
                </th>
                <th class="col-2" scope="col">Zonas
                </th>
                <th scope="col">{{ __('buttons.actions') }}</th>
            </tr>
    </thead>
    <tbody>
        @forelse ($deviceSummary as $index => $device)
            <tr id="device-{{ $device['id'] }}">
                <th scope="row"> {{ ++$index }} </th>
                <td class="text-primary"> {{ $device['id'] }} </td>
                <td> {{ $device['name'] }} </td>
                <td> {{ $device['count'] }} </td>
                <td> {{ $device['code'] }} </td>
                <td> {{ implode(', ', $device['floorplans'])}} </td>
                <td> {{ implode(', ', $device['zones'])}} </td>
                <td> 
                    @can('write_order')
                        <a class="btn btn-secondary btn-sm" href="">
                            <i class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}
                        </a>
                    @endcan
                </td>
            </tr>
        @empty
            <td colspan="8">No hay dispositivos por el momento.</td>
        @endforelse
    </tbody>
</table>


<script>
    $(function() {
        $('#search-date').daterangepicker({
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
            alwaysShowCalendars: true,
            autoUpdateInput: false,
        });
    });

    $('#search-date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });
</script>
