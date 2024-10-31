<div class="col-1 m-0" style="background-color: #343a40;">
    @foreach ($mip_directories as $i => $mip_name)
        <div class="row">
            <a href=" {{ route('client.mip.index', ['index' => $i]) }}" class="sidebar col-12 p-2 text-center"> {{ $mip_name }}
            </a>
        </div>
    @endforeach
</div>
