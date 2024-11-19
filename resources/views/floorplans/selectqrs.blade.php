@extends('layouts.app')
@section('content')
    @php
        // action="{{ route('floorplans_print') }}"
        $vals = [];
    @endphp

    <div class="container-fluid">
        <div class="row border-bottom p-3 mb-3">
            <a href="{{ Route('customer.edit', ['id' => $floorplan->customer->id, 'type' => $type, 'section' => 8]) }}"
                class="col-auto btn-primary p-0 fs-3"><i class="bi bi-arrow-left m-3"></i></a>
            <h1 class="col-auto fs-2 fw-bold m-0">Selecciona los dispositivos: [{{ $floorplan->filename }}]</h1>
        </div>

        @include('layouts.alert')

        <div class="border rounded bg-body-tertiary p-3 m-3">
            <div class="row mb-3">
                <div class="col">
                    <select class="form-select border-secondary border-opacity-25" id="version" name="version">
                        @foreach ($floorplan->versions as $floorVersion)
                            <option value="{{ $floorVersion->id }}">
                                {{ $floorVersion->version }} - ({{ $floorVersion->created_at }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <select class="form-select border-secondary border-opacity-25" id="point" name="point">
                        <option value="" selected disabled>Seleccionar tipo</option>
                        @foreach ($control_points as $point)
                            <option value="{{ $point->id }}">
                                {{ $point->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <select class="form-select border-secondary border-opacity-25" id="zone" name="zone">
                        <option value="" selected disabled>Seleccionar zona</option>
                        @foreach ($zones_areas as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-success btn-sm" onclick="searchDevices()">Buscar</button>
        </div>

        <div class="row m-3">
            <div class="form-check">
                <input class="form-check-input border-secondary" type="checkbox" id="select-all"
                    onchange="selectAllDevices(this.checked)">
                <label class="form-check-label fw-bold" for="select-all">
                    Seleccionar todo
                </label>
            </div>

            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th class="col-2" scope="col"># (Número)</th>
                        <th scope="col">Color</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Zona</th>
                        <th scope="col">Version</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @foreach ($devices as $device)
                        <tr>
                            <td>
                                <input class="form-check-input border-secondary" type="checkbox"
                                    value="{{ $device->id }}" onchange="selectDevice(this)">
                            </td>
                            <td>{{ $device->nplan }}</td>
                            <td class="d-flex justify-content-center">
                                <div class="rounded"
                                    style="width: 40px; height: 20px; background-color: {{ $device->color }};"></div>
                            </td>
                            <td id="{{ $device->type_control_point_id }}">{{ $device->controlPoint->name }}
                            </td>
                            <td id="{{ $device->application_area_id }}">{{ $device->applicationArea->name }}
                            </td>
                            <td>{{ $device->version }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form class="p-0" method="POST" action="{{ route('floorplan.qr.print', ['id' => $floorplan->id]) }}">
                @csrf
                <input type="hidden" id="selected-devices" name="selected_devices" value="">
                <button type="submit" class="btn btn-primary col-auto" onclick="setDevices()">Generar</button>
            </form>
        </div>

        <script>
            var selected_devices = [];

            function selectAllDevices(isChecked) {
                if (isChecked) {
                    selected_devices = $('#table-body input[type="checkbox"]')
                        .prop('checked', true)
                        .map(function() {
                            return this.value;
                        }).get();
                } else {
                    $('#table-body input[type="checkbox"]').prop('checked', false);
                    selected_devices = [];
                }
            }

            function selectDevice(element) {
                const value = parseInt(element.value);
                const isChecked = element.checked;
                if (isChecked) {
                    if (!selected_devices.includes(value)) {
                        selected_devices.push(value);
                    }
                } else {
                    if (selected_devices.includes(value)) {
                        selected_devices = selected_devices.filter(item => item != value);
                    }
                }
            }

            function setDevices() {
                $('#selected-devices').val(JSON.stringify(selected_devices));
            }

            function searchDevices() {
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                var formData = new FormData();
                var point = $('#point').val();
                var zone = $('#zone').val();
                var version = $('#version').val();
                var html = '';

                formData.append('point', point);
                formData.append('zone', zone);
                formData.append('version', version);

                $.ajax({
                    url: "{{ route('ajax.search.devices', ['floorplan_id' => $floorplan->id]) }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function(response) {
                        const devices = response.data;
                        console.log(devices)
                        if (devices.length > 0) {
                            $('#table-body').html('');

                            devices.forEach(device => {
                                html += `
                                    <tr>
                                        <td>
                                            <input class="form-check-input border-secondary" type="checkbox"
                                                value="${device.device_id}" onchange="selectDevice(this)" />
                                        </td>
                                        <td>${device.nplan}</td>
                                        <td class="d-flex justify-content-center">
                                            <div class="rounded"
                                                style="width: 40px; height: 20px; background-color: ${device.color};"></div>
                                        </td>
                                        <td>${device.type}
                                        </td>
                                        <td>${device.zone}
                                        </td>
                                        <td>${device.version}</td>
                                    </tr>
                                `;
                            });
                        }

                        $('#table-body').html(html);
                    },
                    error: function(error) {
                        console.error(error);
                    },
                });
            }
        </script>
    @endsection
