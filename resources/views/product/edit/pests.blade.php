<div class="row mb-4">
    <div class="accordion accordion-flush row p-0" id="accordionPest">
        @foreach ($pest_categories as $i => $pest_category)
            <div class="accordion-item col-4 border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $i }}" aria-expanded="true"
                        aria-controls="collapse{{ $i }}">
                        {{ $pest_category->category }}
                    </button>
                </h2>
                @if (!$pest_category->pests->isEmpty())
                    <div id="collapse{{ $i }}" class="accordion-collapse collapse"
                        data-bs-parent="#accordionPest">
                        <div class="accordion-body">
                            @foreach ($pest_category->pests as $pest)
                                <div class="form-check">
                                    <input class="pest form-check-input " type="checkbox"
                                        value="{{ $pest->id }}" onchange="setPests()"
                                        {{ $product->hasPest($pest->id) ? 'checked' : '' }} />
                                    <label class="form-check-label" for="pest-{{ $pest->id }}">
                                        {{ $pest->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div id="collapse{{ $i }}" class="accordion-collapse collapse"
                        data-bs-parent="#accordionPest">
                        <div class="accordion-body text-danger">
                            No hay plagas asociadas.
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    <input type="hidden" id="pest-select" name="pestSelected" value="" />
</div>
<script src="{{ asset('js/service/control.min.js') }}"></script>