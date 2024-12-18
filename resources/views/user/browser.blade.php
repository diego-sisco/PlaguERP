<form class="input-group d-flex" method="GET" action="{{ route('user.search', ['type' => $type, 'page' => 1]) }}"
    enctype="multipart/form-data">
    @csrf
    <input class="form-control border-secondary border-opacity-50 rounded-0 rounded-start-2" id="search" name="search"
        type="search" placeholder="{{ __('pagination.search') }}" aria-label="Search" autocomplete="off">
    <button type="submit" class="btn btn-success rounded-0 rounded-end-2" data-bs-toggle="tooltip"
        data-bs-placement="bottom" data-bs-title=" {{ __('user.tips.search') }}"> {{ __('buttons.search') }} </button>
</form>

<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>