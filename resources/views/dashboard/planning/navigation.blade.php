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
        <a href="{{ Route('planning.schedule') }}"
            class="sidebar col-12 p-2 text-center">
            Cronograma
        </a>
        <a href="{{ Route('planning.activities') }}"
            class="sidebar col-12 p-2 text-center">
            Asignación de actividades
        </a>
    </div>
</div>