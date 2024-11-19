<div class="row">
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal" onclick="cleanForm()"><i
                class="bi bi-plus-lg"></i> {{ __('buttons.add') }} </button>
    </div>
    <div class="col-12">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Servicio usado</th>
                    <th scope="col">Método de aplicación</th>
                    <th scope="col">Cantidad usada</th>
                    <th scope="col">Cantidad por ltrs aplicados</th>
                    <th scope="col">Lote</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->products as $i => $order_product)
                @php
                    $data = [
                        'product_id' => $order_product->product_id,
                        'service_id' => $order_product->service_id,
                        'application_method_id' => $order_product->application_method_id,
                        'metric_id' => $order_product->metric_id,
                        'amount' => $order_product->amount,
                        'lot_id' => $order_product->lot_id,
                    ]
                @endphp
                    <tr>
                        <th scope="row">{{ $i + 1 }}</th>
                        <td>{{ $order_product->product->name ?? 'N/A' }}</td>
                        <td>{{ $order_product->service->name ?? 'N/A' }}</td>
                        <td>{{ $order_product->appMethod->name ?? 'N/A' }}</td>
                        <td>{{ $order_product->amount . ' ' . $order_product->product->metric->value  }}</td>
                        <td>{{ $order_product->dosage ? $order_product->dosage : ($order_product->product->dosage ? $order_product->product->dosage : 'N/A') }}</td>
                        <td>{{ $order_product->product->lot->lot_number ?? 'N/A' }}</td>
                        <td>
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#productModal" onclick="setProduct({{$order_product->id}})"><i
                                    class="bi bi-pencil-square"></i> {{ __('buttons.edit') }}</button>
                            <a href="{{ route('report.destroy.product', ['incidentId' => $order_product->id]) }}" class="btn btn-danger btn-sm"
                                onclick="return confirm('{{ __('messages.are_you_sure_delete') }}')">
                                <i class="bi bi-trash-fill"></i> {{ __('buttons.delete') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
