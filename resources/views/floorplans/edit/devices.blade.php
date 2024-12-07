<style>
    #zoom-image {
        user-select: none;
    }

    #zoom-wrapper {
        width: 100%;
        height: 100%;
    }

    .point {
        position: absolute;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        padding: 6px;
        cursor: pointer;
        font-weight: bold;
    }

    .sidebar {
        color: white;
        text-decoration: none
    }

    .sidebar:hover {
        background-color: #e9ecef;
        color: #212529;
    }
</style>

@if ($floorplan->service_id == null)
    <h5 class="text-danger fw-bold"> Se requiere la selección previa de un servicio </h5>
@else
    <div class="row mb-3">
        <div class="col-6">
            <label for="exampleFormControlInput1" class="form-label">Versión del plano: </label>
            @if (!$floorplan->versions->isEmpty())
                <div class="input-group">
                    <select class="form-select border-secondary border-opacity-25 " id="version" name="version">
                        @foreach ($floorplan->versions as $floorVersion)
                            <option value="{{ $floorVersion->id }}">
                                {{ $floorVersion->version }} -
                                ({{ $floorVersion->created_at }})
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-success" type="button" onclick="searchVersion()">Buscar</button>
                </div>
            @else
                <input type="text" class="form-control border-secondary border-opacity-25" value="Sin versiones"
                    id="version-none" disabled>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <label for="exampleFormControlInput1" class="form-label">Servicio: </label>
            <input type="text" class="form-control border-secondary border-opacity-25"
                value="{{ $floorplan->service->name }}" disabled>
        </div>
    </div>
    <div class="row">
        <div class="col-6 mb-2">
            <label for="exampleFormControlInput1" class="form-label">Zonas o áreas de la
                empresa:
            </label>
            @if (!$customer->applicationAreas->isEmpty())
                <select class="form-select border-secondary border-opacity-25 " id="area" name="area">
                    @foreach ($customer->applicationAreas as $area)
                        @php
                            $areaNames[] = [
                                'id' => $area->id,
                                'name' => $area->name,
                            ];
                        @endphp
                        <option value="{{ $area->id }}">
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            @else
                <input type="text" class="form-control border-secondary border-opacity-25"
                    value="Sin zonas o áreas disponibles" id="area" disabled>
            @endif
        </div>

        <div class="col-4 mb-2">
            <label for="exampleFormControlInput1" class="form-label">Puntos de control
                asociados:
            </label>
            <select class="form-select border-secondary border-opacity-25 " id="control-points" name="control_points">
                @foreach ($ctrlPoints as $point)
                    @php
                        $pointNames[] = [
                            'id' => $point->id,
                            'name' => $point->name,
                        ];
                    @endphp
                    <option value="{{ $point->id }}">
                        {{ $point->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-2 mb-3">
            <label for="exampleFormControlInput1" class="form-label">Puntos a generar:
            </label>
            <input type="number" id="numPoints" class="form-control border-secondary border-opacity-25" min=0
                value="0">
        </div>
    </div>

    <button type="button" class="btn btn-primary btn-sm" id="create-points" onclick="generatePoints()">
        Generar
    </button>

    <div class="row m-0 mb-3">
        <div class="col-12">
            <table class="table text-center align-middle">
                <thead>
                    <tr>
                        <th class="col-1" scope="col">Color</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Código</th>
                        <th scope="col">Area</th>
                        <th class="col-4" scope="col">Producto</th>
                        <th scope="col">Cantidad</th>
                    </tr>
                </thead>
                <tbody id="table-body"> </tbody>
            </table>
        </div>
    </div>

    <div class="w-100 border border-secondary p-1 rounded">
        <div class="row mb-3 p-3">
            <div class="col-12">
                <p id="countPoints" class="fw-bold p-2 bg-secondary-subtle border rounded">Puntos
                    restantes: </p>
            </div>

            <div class="col-12">
                <label for="zoom-range" class="col-sm-1 form-label fw-bold">Zoom: </label>
                <input type="range" id="zoom-range" min="1" max="4" step="0.1" value="1"
                    class="form-range border border-secondary rounded">
            </div>
        </div>

        <div id="image-container" class="w-100 border p-3" style="position: relative; overflow-y: auto;">
            <img id="zoom-image" class="w-100" src="{{ route('image.show', ['filename' => $floorplan->path]) }}"
                alt="Plano" style="transition: transform 0.3s ease; transform-origin: 0 0;">
            <div id="points-container" style="position: absolute; top: 0; left: 0;"></div>
        </div>
    </div>

    <input type="hidden" id="points" name="points" value="">

    <button type="submit" class="btn btn-primary mt-3" onclick="return submitForm();">
        {{ __('buttons.update') }}
    </button>
@endif

<div class="modal fade" id="editPointModal" tabindex="-1" aria-labelledby="editPointModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editPointModalLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-2">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label is-required">Punto(s) de control:
                        </label>
                        <select class="form-select border-secondary border-opacity-25 " id="update-control-point"
                            name="update_control_point">
                            @foreach ($ctrlPoints as $point)
                                <option value="{{ $point->id }}">
                                    {{ $point->product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label is-required"> Zona o área: </label>
                        <select class="form-select border-secondary border-opacity-25 " id="update-area"
                            name="update_area">
                            @foreach ($customer->applicationAreas as $area)
                                <option value="{{ $area->id }}">
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="exampleFormControlInput1" class="form-label"> Producto asociado: </label>
                        <select class="form-select border-secondary border-opacity-25 " id="update-product"
                            name="update_product">
                            <option value="0" selected>
                                Sin producto
                            </option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" id="editPointIndex" value="" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    onclick="updatePoint()">{{ __('buttons.update') }}</button>
            </div>
        </div>
    </div>
</div>


@if ($section == 2 && !$floorplan->service_id == null)
    <script>
        var data = @json($ctrlPoints);
        var devices = @json($devices);
        var countDevices = @json($countDevices);
        var count = @json($nplan);
        var pointNames = @json($pointNames);
        var areaNames = @json($areaNames);
        var productNames = @json($productNames);
        var deviceRevisions = @json($deviceRevisions);
        var container = document.getElementById('points-container');
        var imageContainer = document.getElementById('image-container');
        var zoomRange = document.getElementById('zoom-range');
        var img = document.getElementById('zoom-image');
        var addedPoints = [];
        var points = [];
        var pointsBackup = [];
        var countIndexs = [];
        var countPoints = {};
        var color = '';
        var indexModal = 0;
        var numPoints = 0;
        var pointID = 0;
        var areaID = 0;
        var productID = 0;
        var index = 1;
        var hasPoints = false;

        function submitForm() {
            if (confirm('{{ __('messages.new_devices') }}')) {
                if (points.length > 0) {
                    $('#points').val(JSON.stringify(points));
                }
                return true;
            }
            return false;
        }

        function isNumber(num) {
            return typeof num == 'number' && !isNaN(num);
        }

        function isGreaterThanZero(num) {
            return num > 0;
        }

        function findColor(id) {
            var foundObject = []
            if (points.some(obj => obj.pointID == id)) {
                foundObject = points.find(obj => obj.pointID == id);
            } else {
                foundObject = data.find(obj => obj.id == id);
            }
            return foundObject ? foundObject.color : null;
        }

        function findPointName(id) {
            return pointNames.find(obj => obj.id == id)?.name ?? null;
        }

        function findAreaName(id) {
            return points.find(obj => obj.pointID == id)?.areaID ?
                areaNames.find(obj => obj.id == points.find(obj => obj.pointID == id).areaID)?.name ?? null :
                null;
        }

        function findZone(id) {
            return points.find(item => item.pointID == id)?.areaID ?
                areaNames.find(item => item.id == points.find(item => item.pointID == id)?.areaID)?.name ?? '' :
                '';
        }

        function findProduct(point_id, area_id) {
            const foundPoint = points.find(item => item.pointID == point_id && item.areaID == area_id);
            return foundPoint.productID ?? false;
        }

        function findArea(id) {
            const foundPoint = points.find(item => item.pointID == id);
            return foundPoint.areaID ?? false;
        }

        function findCode(id) {
            return data.find(item => item.id == id)?.code ?
                data.find(item => item.id == id).code : 'Sin código';
        }

        function verifyInputs(point_id, area_id, num_points) {
            if (isNumber(parseInt(point_id)) && isNumber(parseInt(area_id)) && isNumber(parseInt(num_points))) {
                if (isGreaterThanZero(point_id) && isGreaterThanZero(area_id) && isGreaterThanZero(num_points)) {
                    return true;
                }
            }
            return false;
        }

        function createPoint(x, y, count) {
            var pointName = (pointNames.find(item => item.id == pointID)).name;
            var areaName = (areaNames.find(item => item.id == areaID)).name;
            var productName = productID > 0 ? (productNames.find(item => item.id == productID)).name : 'S/N';

            var revisions = deviceRevisions[pointID] || [];
            var revisionsHtml = '';
            revisions.forEach(function(revision) {
                revisionsHtml +=
                    `<div><strong>${revision.answer}</strong> <small>${revision.updated_at}</small></div>`;
            });

            var img_tamx = img.width;
            var img_tamy = img.height;
            var pointX = x;
            var pointY = y;
            var code = data.find(item => item.id == pointID)?.code ?
                `${data.find(item => item.id == pointID).code}-${index}` : null;

            var pointElement = document.createElement('div');

            pointElement.style.left = (pointX - 5) + 'px';
            pointElement.style.top = (pointY - 5) + 'px';
            //pointElement.innerText = code;
            pointElement.id = 'popoverPoint' + index;
            pointElement.className = 'point';
            pointElement.innerText = count;
            pointElement.style.backgroundColor = color;
            pointElement.style.textAlign = 'center';
            pointElement.addEventListener('mousedown', startDragging);

            var newPoint = {
                index: index,
                pointID: parseInt(pointID),
                areaID: parseInt(areaID),
                productID: productID,
                pointCount: count,
                x: pointX,
                y: pointY,
                color: color,
                code: code,
                img_tamx: img_tamx,
                img_tamy: img_tamy,
                element: pointElement,
                revisions: revisions
            }

            new bootstrap.Popover(pointElement, {
                container: 'body',
                html: true,
                title: `Punto de control ${count}`,
                content: `  
                    <div class="row mb-3">
                        <span id="span-edit-point-${index}" class="col-12">Tipo: ${pointName} </span>
                        <span id="span-edit-area-${index}" class="col-12">Área: ${areaName} </span>
                        <span id="span-edit-product-${index}" class="col-12">Producto: ${productName} </span>
                    </div>   
                    <div class="row mb-3">
                        <div class="col-12"><strong>Revisiones:</strong></div>
                        ${revisionsHtml}
                    </div>                      
                    <div class="d-flex flex-wrap justify-content-around">
                        <a href="#!" class="btn btn-secondary btn-sm popover-edit" id="btn-edit-${index}"> <i class="bi bi-pencil-square"></i> Editar</a>
                        <a href="#!" class="btn btn-danger btn-sm popover-delete" id="btn-delete-${index}"> <i class="bi bi-x"></i> Eliminar</a>
                    </div>`,
                placement: 'right',
            });

            points.push(newPoint);
            container.appendChild(pointElement);

            sortPoints();
            setStoragePoint(parseInt(pointID), parseInt(areaID));
            incrementCount(pointID);
            createLegend();
            applyZoom();
        }

        function generatePoints() {
            var nums = document.getElementById('numPoints').value;
            pointID = document.getElementById('control-points').value;
            areaID = document.getElementById('area').value;
            color = findColor(pointID);

            if (verifyInputs(pointID, areaID, nums)) {
                if (!hasPoints) {
                    container.innerHTML = '';
                }

                numPoints = document.getElementById('numPoints').value;
                hasPoints = numPoints > 0;
                $('#countPoints').text('Puntos restantes: ' + numPoints);
            }
        }

        function setStoragePoint(point, area) {
            const foundPoint = addedPoints.find(item => item.pointID == point && item.areaID == area);
            if (!foundPoint) {
                addedPoints.push({
                    'pointID': point,
                    'areaID': area
                });
            }
        }

        function sortPoints() {
            points.sort((a, b) => a.index - b.index);
        }

        function updatePoint() {
            var pointIndex = $('#editPointIndex').val();
            var point = points.find(item => item.index == pointIndex);
            var updatePoint = parseInt($('#update-control-point').val());
            var updateArea = parseInt($('#update-area').val());
            var updateProduct = parseInt($('#update-product').val());

            var pointName = (pointNames.find(item => item.id == updatePoint)).name;
            var areaName = (areaNames.find(item => item.id == updateArea)).name;
            var productName = updateProduct > 0 ? (productNames.find(item => item.id == updateProduct)).name : 'S/N';

            // Obtener revisiones
            var revisions = deviceRevisions[updatePoint] || [];
            var revisionsHtml = '';
            revisions.forEach(function(revision) {
                revisionsHtml +=
                    `<div><strong>${revision.answer}</strong> <small>${revision.updated_at}</small></div>`;
            });

            if (point) {
                let popoverElement = $('#popoverPoint' + point.index);
                let popover = bootstrap.Popover.getInstance(popoverElement);

                point.pointID = updatePoint;
                point.areaID = updateArea;
                point.productID = updateProduct;
                color = findColor(updatePoint);
                popoverElement.css('backgroundColor', color);

                if (popover) {
                    popover.hide();
                    popover.dispose();
                }

                popoverElement.popover({
                    container: 'body',
                    html: true,
                    title: `Punto de control ${point.index}`,
                    content: `  
                            <div class="row mb-3">
                                <span id="span-edit-point-${point.index}" class="col-12">Tipo: ${pointName} </span>
                                <span id="span-edit-area-${point.index}" class="col-12">Área: ${areaName} </span>
                                <span id="span-edit-product-${point.index}" class="col-12">Producto: ${productName} </span>
                            </div>   
                            <div class="row mb-3">
                                <div class="col-12"><strong>Revisiones:</strong></div>
                                ${revisionsHtml}
                            </div>                         
                            <div class="d-flex flex-wrap justify-content-around">
                                <a href="#!" class="btn btn-secondary btn-sm popover-edit" id="btn-edit-${point.index}"> <i class="bi bi-pencil-square"></i> Editar</a>
                                <a href="#!" class="btn btn-danger btn-sm popover-delete" id="btn-delete-${point.index}"> <i class="bi bi-x"></i> Eliminar</a>
                            </div>
                        `,
                    placement: 'right',
                });

                //countPoints = {};
                //countLegend();

                createLegend();
            }

            $('#editPointModal').modal('hide');
        }

        function startDragging(event) {
            var point = event.target;
            var zoomLevel = parseFloat(zoomRange.value); // Obtener el nivel de zoom actual

            // Compensar las posiciones con el zoom aplicado
            var offsetX = (event.clientX - parseFloat(point.style.left)) / zoomLevel;
            var offsetY = (event.clientY - parseFloat(point.style.top)) / zoomLevel;

            function movePoint(event) {
                // Ajustar las posiciones con el zoom aplicado
                var newX = (event.clientX - offsetX * zoomLevel) / zoomLevel;
                var newY = (event.clientY - offsetY * zoomLevel) / zoomLevel;

                // Actualizar la posición en el DOM
                point.style.left = (newX * zoomLevel - 5) + 'px';
                point.style.top = (newY * zoomLevel - 5) + 'px';

                // Actualizar la posición del punto en el array
                var index = points.findIndex(function(p) {
                    return p.element == point;
                });
                if (index !== -1) {
                    points[index].x = newX;
                    points[index].y = newY;
                }
            }

            function stopDragging() {
                document.removeEventListener('mousemove', movePoint);
                document.removeEventListener('mouseup', stopDragging);
            }

            document.addEventListener('mousemove', movePoint);
            document.addEventListener('mouseup', stopDragging);
        }

        function extractId(string) {
            var parts = string.split('-');
            return parseInt(parts[parts.length - 1]);
        }


        function removePoint(pointElement) {
            var index = points.findIndex(function(p) {
                return p.element == pointElement;
            });
            if (index != -1) {
                var popover = bootstrap.Popover.getInstance(pointElement);
                if (popover) {
                    popover.hide();
                }
                points.splice(index, 1);
                pointElement.remove();
            }
        }

        function incrementCount(pointID) {
            if (countPoints[pointID] == undefined) {
                countPoints[pointID] = 1;
            } else {
                countPoints[pointID]++;
            }
        }

        function countLegend() {
            if (points.length > 0) {
                points.forEach(point => {
                    incrementCount(point.pointID);
                });
            }
        }

        function createLegend() {
            var html = '';
            if (addedPoints.length > 0) {
                console.log('addP: ', addedPoints)
                console.log('P: ', addedPoints)
                addedPoints.forEach(point => {
                    var count = points.filter(item => item.pointID == point.pointID && item.areaID == point.areaID)
                        .length;
                    if (count > 0) {
                        html += `
                            <tr>
                                <td>
                                    <input type="color" class="form-control border-secondary border-opacity-25" style="height: 2em;" value="${findColorLegend(point.pointID, point.areaID)}" onchange="updateColor(this.value, ${point.pointID}, ${point.areaID})">
                                </td>
                                <td>${findPointName(point.pointID)}</td>
                                <td class="fw-bold text-primary">${findCode(point.pointID)}</td>
                                <td>
                                    <select class="form-select" onchange="setArea(${point.pointID}, ${point.areaID}, this.value)">
                                    ${
                                        areaNames.map(item => `<option value="${item.id}" ${point.areaID == item.id ? 'selected' : ''}>${item.name}</option>`).join('')
                                    }
                                    </select>    
                                </td>
                                <td>
                                    <select class="form-select" onchange="setProduct(${point.pointID}, ${point.areaID}, this.value)">
                                        <option>Sin producto</option>
                                        ${
                                            productNames.map(item => `<option value="${item.id}" ${findProduct(point.pointID, point.areaID) == item.id ? 'selected' : ''}>${item.name}</option>`).join('')
                                        }
                                    </select>
                                </td>
                                <td>${count}</td>
                            </tr>
                        `;
                    }
                });
            }

            $('#table-body').html(html);
        }

        function findColorLegend(point_id, area_id) {
            var found_points = points.filter(point => point.pointID == point_id && point.areaID == area_id);
            if (found_points.length <= 0) {
                found_points = points.filter(point => point.pointID == point_id)
            }

            return found_points[0].color;
        }

        function updateColor(newColor, point_id, area_id) {
            points.filter(point => point.pointID == point_id && point.areaID == area_id)
                .forEach(point => {
                    point.color = newColor;
                });
            setPoints();
        }

        function setProduct(point_id, area_id, value) {
            points.filter(item => item.pointID == point_id && item.areaID == area_id).forEach(item => {
                item.productID = parseInt(value);
            });

            points.forEach(point => {
                $('#editPointIndex').val(point.index);
                $('#update-control-point').val(point.pointID);
                $('#update-area').val(point.areaID);
                $('#update-product').val(point.productID);
                updatePoint();
            })
        }

        function setArea(point_id, area_id, value) {
            const newAreaID = parseInt(value);

            // Actualizar el área en `points` si coincide el `pointID` y `areaID`
            points.forEach(item => {
                if (item.pointID == point_id && item.areaID == area_id) {
                    item.areaID = newAreaID;
                }
            });

            // Actualizar o agregar el punto en `addedPoints`
            const index = addedPoints.findIndex(
                addedPoint => addedPoint.pointID == point_id && addedPoint.areaID == area_id
            );

            if (index !== -1) {
                // Si ya existe, actualizar `areaID` si es diferente
                if (addedPoints[index].areaID !== newAreaID) {
                    addedPoints[index].areaID = newAreaID;
                }
            } else {
                // Si no existe, agregarlo
                addedPoints.push({
                    pointID: point_id,
                    areaID: newAreaID
                });
            }

            // Eliminar duplicados en `addedPoints` (prevención extra)
            addedPoints = Array.from(new Set(addedPoints.map(JSON.stringify))).map(
                JSON.parse
            );

            // Actualizar elementos DOM solo una vez
            if (points.length > 0) {
                points.forEach(point => {
                    $('#editPointIndex').val(point.index);
                    $('#update-control-point').val(point.pointID);
                    $('#update-area').val(point.areaID);
                    $('#update-product').val(point.productID);
                    updatePoint();
                });
            }
        }

        function applyZoom() {
            var zoomLevel = parseFloat(zoomRange.value);
            $('#zoom-image').css('transform', `scale(${zoomLevel})`);

            // Actualizar la posición de todos los puntos
            points.forEach(point => {
                var pointX = point.x * zoomLevel;
                var pointY = point.y * zoomLevel;

                point.element.style.left = (pointX - 5) + 'px';
                point.element.style.top = (pointY - 5) + 'px';
            });
        }

        function searchVersion() {
            var formData = new FormData();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            var version = $('#version').val();
            formData.append('version', version);
            $.ajax({
                url: "{{ route('ajax.search.devices', ['floorplan_id' => $floorplan->id]) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function(response) {
                    devices = response;
                    points = [];
                    countPoints = {};
                    hasPoints = false;
                    container.innerHTML = '';
                    setDevices(0);
                },
                error: function(xhr, status, error) {
                    console.error('Error in AJAX request:', status, error);
                }
            });
        }

        function setDevices(color_op) {
            if (devices.length > 0) {
                devices.forEach(function(device) {
                    pointID = parseInt(device.type_control_point_id);
                    areaID = parseInt(device.application_area_id);
                    productID = parseInt(device.product_id);
                    index = parseInt(device.itemnumber);
                    color = color_op ? findColor(pointID) : device.color;
                    createPoint(device.map_x, device.map_y, device.nplan);
                    setStoragePoint(pointID, areaID);
                    //countIndexs.push(index);
                });
                index = countDevices;
                //count = devices.length;
                hasPoints = true;
                createLegend();
            }
        }

        function setPoints() {
            if (points.length > 0) {
                points.forEach(point => {
                    point.element.style.backgroundColor = point.color;
                });
            }
            hasPoints = true;
            createLegend();
        }

        zoomRange.addEventListener('input', function() {
            applyZoom();
        });

        document.getElementById('image-container').addEventListener('dblclick', function(event) {
            var zoomLevel = parseFloat(zoomRange.value); // Obtén el nivel de zoom actual.
            var rect = event.target.getBoundingClientRect();
            var offsetX = (event.clientX - rect.left) / zoomLevel; // Ajusta la posición x basándote en el zoom.
            var offsetY = (event.clientY - rect.top) / zoomLevel;
            //var count = 0;
            if (numPoints > 0) {
                index = points.length > 0 ? ++index : ++countDevices;
                count++;
                createPoint(offsetX, offsetY, count);
                numPoints--;
                $('#countPoints').text('Puntos restantes: ' + numPoints);
            } else {
                console.log(points);
            }
        });

        document.addEventListener('click', function(e) {
            // Eliminar punto
            if (e.target.classList.contains('popover-delete')) {
                if (confirm("¿Estás seguro de eliminar el punto?")) {
                    var pointIndex = extractId(e.target.id);
                    var pointElement = document.getElementById('popoverPoint' + pointIndex);
                    if (pointElement) {
                        removePoint(pointElement);
                    }
                    //countIndexs = countIndexs.filter(item => item != index);
                    //countPoints = {};
                    //countLegend();
                    index--;
                    count--;
                    createLegend();
                }
            }

            // Editar punto
            if (e.target.classList.contains('popover-edit')) {
                var pointIndex = extractId(e.target.id);
                var pointElement = document.getElementById('popoverPoint' + pointIndex);
                var popover = bootstrap.Popover.getInstance(pointElement);
                var point = points.find(item => item.index == pointIndex);

                indexModal = pointIndex;

                if (popover) {
                    popover.hide();
                }

                if (point) {
                    $(`#update-control-point option[value="${point.pointID}"]`).prop('selected', true);
                    $(`#update-area option[value="${point.areaID}"]`).prop('selected', true);
                    $(`#update-product option[value="${point.productID}"]`).prop('selected', true);
                }

                $('#editPointModalLabel').text(`Punto de control ${point.pointCount}`)
                $('#editPointIndex').val(pointIndex);
                $('#editPointModal').modal('show');
            }
        });


        $(document).ready(function() {
            img.onload = function() {
                var width = img.width;
                var height = img.height;
                setDevices(0);
                applyZoom();
            };

            img.onerror = function() {
                console.error("No se pudo cargar la imagen.");
            };

            if (img.complete) {
                img.onload();
            }
        });
    </script>
@endif
