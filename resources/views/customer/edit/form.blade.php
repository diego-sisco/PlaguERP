<style>
    .card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card-body {
        flex-grow: 1;
    }

    .btn-group {
        display: flex;
        flex-wrap: wrap;
    }
</style>

<form method="POST" action="{{ route('customer.update', ['id' => $customer->id, 'type' => $type]) }}"
    enctype="multipart/form-data">
    @csrf
    @switch($section)
        @case(1)
            @include('customer.edit.customer')
        @break

        @case(2)
            @if ($customer->service_type_id > 1)
                @include('customer.edit.range_area')
            @endif
        @break

        @case(3)
            @include('customer.edit.portal')
        @break

        @case(4)
            @if ($type != 0)
                @include('customer.edit.sedes')
            @endif
        @break

        @case(5)
            @include('customer.edit.reference')
        @break

        @case(6)
            @include('customer.edit.files')
        @break

        @case(7)
            @include('customer.edit.zones')
        @break

        @case(8)
            @include('customer.edit.floorplans')
        @break

        @case(9)
            @include('customer.edit.properties')
        @break

        @default
            <p>Sección no encontrada</p>
    @endswitch


    @if ($section < 4 || $section == 9)
        <button type="submit" class="btn btn-primary">{{ __('buttons.update') }}</button>
    @endif
</form>
