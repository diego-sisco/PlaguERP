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
        @role('SupervisorCalidad|AdministradorDireccion')
        <a href="{{ Route('quality.control') }}"
            class="sidebar col-12 p-2 text-center">
            Control de actividades
        </a>
        @endrole
        <a href="{{ Route('quality.customers') }}"
            class="sidebar col-12 p-2 text-center">
            Gestión de clientes
        </a>
        <a class="sidebar col-12 p-2 text-center" data-bs-toggle="collapse" href="#collapseCustomers" role="button"
            aria-expanded="false" aria-controls="collapseCustomers">
            Ordenes de servicio

        </a>
        <div class="collapse" id="collapseCustomers" style="background-color: #495057;">
            <div class="row">
                <a href="{{ Route('quality.orders', ['status' => 1]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Pendientes
                </a>
                <a href="{{ Route('quality.orders', ['status' => 3]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Finalizadas
                </a>
                <a href="{{ Route('quality.orders', ['status' => 4]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Verificadas
                </a>
                <a href="{{ Route('quality.orders', ['status' => 5]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Aprobadas
                </a>
                <a href="{{ Route('quality.orders', ['status' => 6]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Canceladas
                </a>
            </div>
        </div>

    </div>
</div>
