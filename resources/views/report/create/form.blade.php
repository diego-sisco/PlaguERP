@php
    $service_ids = $services->pluck('id')->toArray();
    $question_ids = [];
    $i = 0;

    function getOptions($id, $answers)
    {
        foreach ($answers as $answer) {
            if ($answer['id'] == $id) {
                return $answer['options'];
            }
        }
        return [];
    }

@endphp

<form id="report_form" class="form p-5 pt-3" method="POST" action="{{ route('report.store', ['orderId' => $order->id]) }}"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="service-details" name="service_details" value="">
    <input type="hidden" id="recommendations" name="recommendations" value="">

    <div class="row mb-4">
        <h5 class="border-bottom pb-1 mb-1 fw-bold">Datos de la orden </h5>
        <div class="col-12 p-0">
            @can('write_order')
                <a class="btn btn-link" href="{{ route('order.edit', ['id' => $order->id]) }}">
                    {{ __('buttons.edit') }} orden
                </a>
            @endcan
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('order.data.programmed_date') }}: </span>
            <span class="col">{{ $order->programmed_date }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('order.data.completed_date') }}: </span>
            <span class="col">{{ $order->completed_date }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('order.data.start_time') }}: </span>
            <span class="col">{{ $order->start_time }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('order.data.end_time') }}: </span>
            <span class="col">{{ $order->end_time }}</span>
        </div>
    </div>

    <div class="row mb-4">
        <h5 class="border-bottom pb-1 mb-1 fw-bold">Cliente </h5>
        <div class="col-12 p-0">
            @can('write_customer')
                <a href="{{ route('customer.edit', ['id' => $order->customer->id, 'type' => 1, 'section' => 1]) }}"
                    class="btn btn-link">
                    {{ __('buttons.edit') }} cliente
                </a>
            @endcan
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.name') }}:</span>
            <span class="col">{{ $order->customer->name }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.data.type') }}:</span>
            <span class="col">{{ $order->customer->serviceType->name }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.address') }}:</span>
            <span class="col">{{ $order->customer->address }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.correo') }}:</span>
            <span class="col">{{ $order->customer->email }}</span>
        </div>
        <div class="row">
            <span class="col fw-bold">{{ __('customer.customer_table.rfc') }}:</span>
            <span class="col">{{ $order->customer->rfc }}</span>
        </div>
    </div>

    <div class="accordion mb-3" id="accordionReview">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseServices" aria-expanded="true" aria-controls="collapseServices">
                    Servicios
                </button>
            </h2>
            <div id="collapseServices" class="accordion-collapse collapse show" data-bs-parent="#accordionReview">
                <div class="accordion-body">
                    @include('report.create.services')
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseProducts" aria-expanded="false" aria-controls="collapseProducts">
                    Productos
                </button>
            </h2>
            <div id="collapseProducts" class="accordion-collapse collapse" data-bs-parent="#accordionReview">
                <div class="accordion-body">
                    @include('report.create.products')
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapsePests" aria-expanded="true" aria-controls="collapsePests">
                    Plagas atacadas (Aplicación química)
                </button>
            </h2>
            <div id="collapsePests" class="accordion-collapse collapse" data-bs-parent="#accordionReview">
                <div class="accordion-body">
                    @include('report.create.pests')
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseRecoms" aria-expanded="false" aria-controls="collapseRecoms">
                    Recomendaciones
                </button>
            </h2>
            <div id="collapseRecoms" class="accordion-collapse collapse" data-bs-parent="#accordionReview">
                @include('report.create.recommendations')
            </div>
        </div>
    </div>

    <!--div class="row mb-3">
        <div class="col-12">
            <label for="additional_comments" class="mb-2 fw-bold">{{ __('order.data.comments') }}:
            </label>
            <textarea class="form-control border-secondary border-opacity-25" id="additional_comments" name="additional_comments"
                style="height: 100px">{{ $order->comments }}</textarea>
        </div>
    </div-->

    <button type="submit" class="btn btn-primary mt-3" onclick="setData()">{{ __('buttons.generate') }}</button>
    </div>
</form>

@include('report.create.modals')

{{-- @endif --}}

<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300, // altura del editor
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['table', ['table']], // Incluye el botón de tabla
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['misc', ['undo', 'redo', 'fullscreen', 'codeview']]
            ],
            // Puedes agregar más configuraciones si es necesario
        });
    });
</script>

<script>
    const service_ids = @json($service_ids);
    var service_details = [];
    var incidents = [];
    var recommendations = [];

    $(document).ready(function() {
        $('.recommendations:checked').each(function() {
            recommendations.push(parseInt($(this).val()));
        });

    });

    function setData() {
        service_ids.forEach(service_id => {
            value = $(`#service${service_id}-details`).val();
            if (value) {
                data = {
                    id: service_id,
                    details: value,
                };

                service_details.push(data);
            }
        });


        $('#service-details').val(JSON.stringify(service_details));
        $('#recommendations').val(JSON.stringify(recommendations));
    }

    function setQuestion(service_id, device_id, question_id, value, type) {
        const found_incident = incidents.find(incident => incident.device_id == device_id && incident.question_id ==
            question_id);
        if (!found_incident) {
            incidents.push({
                device_id: device_id,
                question_id: question_id,
                type: type,
                value: value
            });
        } else {
            found_incident.value = value;
        }
    }

    function setRecommendations(value, isChecked) {
        value = parseInt(value);
        recommendations = isChecked ? [...recommendations, value].filter((v, i, arr) => arr.indexOf(v) == i) :
            recommendations.filter(rec => rec != value);
    }
</script>
