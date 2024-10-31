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
        <a href="{{ Route('quality.control', ['page' => $page]) }}"
            class="sidebar col-12 p-2 text-center">
            Control de actividades
        </a>
        <a href="{{ Route('quality.customers', ['page' => $page]) }}"
            class="sidebar col-12 p-2 text-center">
            Gestión de clientes
        </a>
        <a class="sidebar col-12 p-2 text-center" data-bs-toggle="collapse" href="#collapseCustomers" role="button"
            aria-expanded="false" aria-controls="collapseCustomers">
            Listado de ordenes
        </a>
        <div class="collapse" id="collapseCustomers" style="background-color: #495057;">
            <div class="row">
                <a href="{{ Route('quality.orders', ['status' => 1, 'page' => $page]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Pendientes
                </a>
                <a href="{{ Route('quality.orders', ['status' => 3, 'page' => $page]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Finalizadas
                </a>
                <a href="{{ Route('quality.orders', ['status' => 4, 'page' => $page]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Verificadas
                </a>
                <a href="{{ Route('quality.orders', ['status' => 5, 'page' => $page]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Aprobadas
                </a>
                <a href="{{ Route('quality.orders', ['status' => 6, 'page' => $page]) }}"
                    class="sidebar col-12 p-2 text-center">
                    Canceladas
                </a>
            </div>
        </div>

    </div>
</div>
