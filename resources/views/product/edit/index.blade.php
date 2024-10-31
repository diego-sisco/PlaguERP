<form method="POST" action="{{ route('product.update', ['id' => $product->id, 'section' => $section]) }}"
    enctype="multipart/form-data">
    @csrf
    <div class="row mb-3">
        @if ($section == 1)
            @include('product.edit.basics.index')
        @endif

        @if ($section == 2)
            @include('product.edit.basics.technical')
        @endif

        @if ($section == 3)
            @include('product.edit.basics.toxicity')
        @endif

        @if ($section == 4)
            @include('product.edit.basics.unids')
        @endif


        @if ($section == 5)
           @include('product.edit.pests')
        @endif

        @if ($section == 6)
           @include('product.edit.inputs')
        @endif

        @if ($section == 7)
        @include('product.edit.files')
    @endif
    </div>
    @if ($section < 4 || $section > 10)
        <button type="submit" class="btn btn-primary mb-3">{{ __('modals.button.save') }}</button>
    @endif
</form>

<script src="{{ asset('js/service/control.min.js') }}"></script>
