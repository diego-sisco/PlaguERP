<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServiceModalLabel">Editar servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Servicio</label>
                    <input type="text" class="form-control" id="service-name" value="" disabled/>  
                </div>

                <div class="mb-3">
                    <label class="form-label is-required">Frecuencia</label>
                    <select class="form-select" id="frequency" onchange="controlInputs(this.value)">
                        @foreach ($exec_frecuencies as $exec)
                            <option value="{{ $exec->id }}"> {{ $exec->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Intervalo</label>
                    <select class="form-select" id="interval" onchange="controlDays(this.value)">
                            <option value="1">Por día</option>
                            <option value="2">Primera semana</option>
                            <option value="3">Segunda semana</option>
                            <option value="4">Tercera semana</option>
                            <option value="5">Ultima semana</option>
                            <option value="6">Quincenal</option>
                    </select>
                </div>

                <div class="mb-3" id="letter-days">
                    <label class="form-label">Días</label>
                    <input type="text" 
                           id="days" 
                           class="form-control border-secondary border-opacity-50 uppercase" 
                           placeholder="L, M, Mi, J, V, S, D" 
                           value = ""
                           oninput="validateLetterInput(this);"
                    />
                </div>

                <div class="mb-3" id="number-days">
                    <label class="form-label">Días</label>
                    <input type="text" 
                           id="days" 
                           class="form-control border-secondary border-opacity-50 uppercase" 
                           placeholder="1, 2, 3, 4 ...30, 31" 
                           value = ""
                           oninput="validateNumberInput(this);"
                    />
                </div>

                <input type="hidden" id="service-index" value="" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="setContractData()">{{ __('buttons.update') }}</button>
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </div>
    </div>
</div>