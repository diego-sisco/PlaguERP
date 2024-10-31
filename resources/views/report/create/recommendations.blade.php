<div class="row">
    <div class="col-12 p-3">
        <ul class="list-group list-group-flush">
            @foreach ($recommendations as $recommendation)
                <li class="list-group-item">
                    <div class="form-check">
                        <input class="form-check-input border-secondary recommendations" type="checkbox"
                            value="{{ $recommendation->id }}" onchange="setRecommendations(this.value, this.checked)"
                            {{ $order->hasRecommendation($recommendation->id) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            {{ $recommendation->description }}
                        </label>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
