<style>
    .sidebar {
        color: white;
        text-decoration: none
    }

    .sidebar:hover {
        background-color: #e9ecef;
        color: #212529;
    }
</style>

<div class="col-1 m-0" style="background-color: #343a40;">
    <div class="row">
        <a class="sidebar col-12 p-2 text-center" data-bs-toggle="collapse" href="#collapse-customer" role="button"
            aria-expanded="false" aria-controls="collapse-customer">
            Seguimiento de clientes
        </a>
        <div class="collapse" id="collapse-customer" style="background-color: #495057;">
            <div class="row">
                <a href="{{ route('crm.tracking', ['type' => 1]) }}" class="sidebar col-12 p-2 text-center"> Activos
                </a>
            </div>

            <div class="row">
                <a href="{{ route('crm.tracking', ['type' => 0]) }}" class="sidebar col-12 p-2 text-center"> Potenciales
                </a>
            </div>
        </div>
        <a class="sidebar col-12 p-2 text-center" data-bs-toggle="collapse" href="#collapse-orders" role="button"
            aria-expanded="false" aria-controls="collapse-orders">
            Ordenes de servicio
        </a>
        <div class="collapse" id="collapse-orders" style="background-color: #495057;">
            @foreach ($order_status as $status)
                <div class="row">
                    <a href="{{ route('crm.orders', ['status' => $status->id]) }}"
                        class="sidebar col-12 p-2 text-center"> {{ $status->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
