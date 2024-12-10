<!-- Modal -->
<div class="modal fade" id="controlModal" tabindex="-1" aria-labelledby="controlModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('quality.control.store') }}">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="controlModalLabel">Relación de control</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label is-required">Encargado (Calidad)</label>
                    <select class="form-select" id="user" name="user_id" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label is-required">Cliente</label>
                    <select class="form-select" id="customer" name="customer_id" required>
                        @foreach ($customers as $customer)
                            @if ($customer->administrative_id == null)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('buttons.create') }}</button>
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </form>
    </div>
</div>
