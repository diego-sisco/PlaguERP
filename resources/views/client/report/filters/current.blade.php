<form class="border rounded bg-body-tertiary p-3" class="modal-content" method="POST"
    action="{{ route('client.report.search') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-3 mb-3">
            <label for="sede" class="form-label is-required">Sede</label>
            <select class="form-select" id="sede" name="sede" required>
                @if ($user->role_id == 5)
                    @forelse ($user->customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @empty
                        <option value="">Sin sede</option>
                    @endforelse
                @else
                    @forelse ($sedes as $sede)
                        <option value="{{ $sede->id }}">{{ $sede->name }}</option>
                    @empty
                        <option value="">Sin sede</option>
                    @endforelse
                @endif
            </select>
        </div>
        <div class="col-auto mb-3">
            <label for="business-line" class="form-label is-required">Línea de negocio</label>
            <select class="form-select" id="business-line" name="business_line" required>
                @foreach ($business_lines as $business_line)
                    <option value="{{ $business_line->id }}" {{ $business_line->id == 2 ? 'selected' : '' }}>
                        {{ $business_line->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto mb-3">
            <label for="report-id" class="form-label">No. Reporte (#)</label>
            <input type="number" class="form-control" id="report" name="report" placeholder="1, 2 , 3 , 4..."
                min="1" />
        </div>
        
        <div class="col mb-3">
            <label for="service" class="form-label">Servicio</label>
            <input type="text" class="form-control" id="service" name="service"
                placeholder="Control de roedores, aplicación quimica..." />
        </div>
    </div>
    <div class="row">
        <div class="col-auto mb-3">
            <label for="date" class="form-label is-required">Fecha</label>
            <input type="text" class="form-control" id="date-range" name="date" value="" required/>
        </div>
        <div class="col-auto mb-3">
            <label for="time" class="form-label">Hora</label>
            <input type="time" class="form-control" id="time" name="time" />
        </div>
    </div>

    <div class="row">
        <a class="link-primary mb-3" data-bs-toggle="collapse" href="#collapseOptions" role="button"
            aria-expanded="false" aria-controls="collapseOptions">
            Opciones adicionales
        </a>
        <div class="collapse mb-3" id="collapseOptions">
            <div class="row">
                <div class="col-auto">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tracking_type" value="{{ true }}" checked>
                        <label class="form-check-label" for="is-mip">
                            Programación MIP
                        </label>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tracking_type" value="{{ false }}">
                        <label class="form-check-label" for="is-tracking">
                            Seguimiento
                        </label>
                    </div>
                </div>
            </div>
            <!--div class="row">
                <div class="col-auto mb-3">
                    <label for="sede" class="form-label">¿Esta firmado?</label>
                    <select class="form-select" id="has-signature" name="has_signature">
                        <option value="1">Si</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div-->
        </div>
    </div>


    <button type="submit" class="btn btn-primary">
        Buscar
    </button>
</form>

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
