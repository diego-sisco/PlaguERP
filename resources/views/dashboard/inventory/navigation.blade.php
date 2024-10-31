<style>
    .sidebar {
        color: white;
        text-decoration: none
    }

    .sidebar:hover {
        background-color: #e9ecef;
        color: #212529;
    }

    .flat-btn {
        background-color: #55ff00;
    }
</style>

<div class="row">
    <a href="{{ route('warehouse.index', ['is_active' => 1]) }}" class="sidebar col-12 p-2 text-center">
        Almacenes
    </a>
    <a href="{{ route('lot.index') }}" class="sidebar col-12 p-2 text-center">
        Lotes
    </a>
    <a href="{{ route('warehouse.movements.show') }}" class="sidebar col-12 p-2 text-center">
        Movimientos
    </a>
</div>
