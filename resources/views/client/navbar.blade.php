<div class="col-1 m-0" style="background-color: #343a40;">
    <div class="row">
        <a href="{{ Route('client.index', ['section' => 1]) }}" class="sidebar col-12 p-2 text-center">
            {{ __('client.navbar.folders') }}
        </a>
    </div>

    <div class="row">
        <a href="{{ Route('client.index', ['section' => 2]) }}" class="sidebar col-12 p-2 text-center">
            {{ __('client.navbar.reports') }}
        </a>
    </div>
</div>