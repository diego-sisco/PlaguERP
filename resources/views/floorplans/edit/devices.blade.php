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
        padding: 5px;
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

        <div id="image-container" class="w-100 border" style="position: relative; overflow-y: auto;">
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
            var foundObject = pointNames.find(obj => obj.id == id);
            return foundObject ? foundObject.name : null;
        }

        function findAreaName(id) {
            var foundObject = areaNames.find(obj => obj.id == id);
            return foundObject ? foundObject.name : null;
        }

        function findZone(id) {
            var area = '';
            var foundPoint = points.find(item => item.pointID == id)
            if (foundPoint) {
                var foundArea = areaNames.find(item => item.id == foundPoint.areaID);
                if (foundArea) {
                    area = foundArea.name;
                }
            }
            return area;
        }

        function findProduct(id) {
            const foundPoint = points.find(item => item.pointID == id);
            return foundPoint.productID ?? false;
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

            var pointElement = document.createElement('div');
            pointElement.id = 'popoverPoint' + index;
            pointElement.className = 'point';
            pointElement.style.backgroundColor = color;

            // Guardar coordenadas en relación con el tamaño original de la imagen
            var img_tamx = img.width;
            var img_tamy = img.height;

            var pointX = x;
            var pointY = y;

            pointElement.style.left = (pointX - 5) + 'px';
            pointElement.style.top = (pointY - 5) + 'px';
            pointElement.innerText = count.toString();
            pointElement.addEventListener('mousedown', startDragging);

            container.appendChild(pointElement);

            var newPoint = {
                index: index,
                pointID: pointID,
                areaID: areaID,
                productID: productID,
                pointCount: count,
                x: pointX,
                y: pointY,
                color: color,
                img_tamx: img_tamx,
                img_tamy: img_tamy,
                element: pointElement,
                revisions: revisions
            }

            points.push(newPoint);

            var popover = new bootstrap.Popover(pointElement, {
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

            sortPoints();
            setStoragePoint(parseInt(pointID));
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

        function setStoragePoint(point) {
            if (!addedPoints.includes(point)) {
                addedPoints.push(point);
            }
        }

        function sortPoints() {
            points.sort((a, b) => a.index - b.index);
        }

        function updatePoint() {
            var point = points.find(item => item.index == indexModal);
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

            let popoverElement = $('#popoverPoint' + indexModal);
            let popover = bootstrap.Popover.getInstance(popoverElement);

            if (point) {
                point.pointID = updatePoint;
                point.areaIconsoleD = updateArea;
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
                    title: `Punto de control ${indexModal}`,
                    content: `  
                            <div class="row mb-3">
                                <span id="span-edit-point-${indexModal}" class="col-12">Tipo: ${pointName} </span>
                                <span id="span-edit-area-${indexModal}" class="col-12">Área: ${areaName} </span>
                                <span id="span-edit-product-${indexModal}" class="col-12">Producto: ${productName} </span>
                            </div>   
                            <div class="row mb-3">
                                <div class="col-12"><strong>Revisiones:</strong></div>
                                ${revisionsHtml}
                            </div>                         
                            <div class="d-flex flex-wrap justify-content-around">
                                <a href="#!" class="btn btn-secondary btn-sm popover-edit" id="btn-edit-${indexModal}"> <i class="bi bi-pencil-square"></i> Editar</a>
                                <a href="#!" class="btn btn-danger btn-sm popover-delete" id="btn-delete-${indexModal}"> <i class="bi bi-x"></i> Eliminar</a>
                            </div>
                        `,
                    placement: 'right',
                });

                countPoints = {};
                countLegend();
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
                addedPoints.forEach(point => {
                    if (countPoints[point] != undefined && countPoints[point] != null) {
                        html += `
                            <tr>
                                <!--td>
                                    <p class="w-100 rounded m-0 p-0" style="height: 2em; background-color: ${findColor(point)}"></p>
                                </td-->
                                <td>
                                    <input type="color" class="form-control border-secondary border-opacity-25" style="height: 2em;" value="${findColor(point)}" onchange="updateColor(this.value, ${point})">
                                </td>
                                <td>${findPointName(point)}</td>
                                <td>${findAreaName(point)}</td>
                                <td>
                                    <select class="form-select" id="point${point}-product" onchange="setProducts(${point}, this.value)">
                                        ${
                                            productNames.map(item => `<option value="${item.id}" ${findProduct(point) == item.id ? 'selected' : ''}>${item.name}</option>`).join('')
                                        }
                                    </select>
                                </td>
                                <td>${countPoints[point]}</td>
                            </tr>
                        `;
                    } else {
                        delete countPoints[point];
                    }
                });
            }
            $('#table-body').html(html);
        }

        function updateColor(newColor, point_id) {
            points.filter(point => point.pointID == point_id)
                .forEach(point => {
                    point.color = newColor;
                });

            countPoints = {}

            setDevices(1);
        }

        function setProducts(point, value) {
            var fetched_points = points.filter(item => item.pointID == point);
            fetched_points.forEach(item => {
                item.productID = parseInt(value);
            });
            setPoints();
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
                    pointID = device.type_control_point_id
                    areaID = device.application_area_id;
                    productID = device.product_id;
                    color = color_op ? findColor(pointID) : device.color;
                    index = device.itemnumber;
                    createPoint(device.map_x, device.map_y, device.nplan);
                    setStoragePoint(pointID);
                    countIndexs.push(device.itemnumber);
                });
                hasPoints = true;
                createLegend();
            }
        }

        function setPoints() {
            if (points.length > 0) {
                aux_points = points;
                points = [];
                countPoints = {};
                hasPoints = false;
                container.innerHTML = '';
                if (aux_points.length > 0) {
                    aux_points.forEach(function(point) {
                        pointID = point.pointID
                        areaID = point.areaID;
                        productID = point.productID
                        color = point.color;
                        index = point.index;
                        createPoint(point.x, point.y, point.pointCount);
                        setStoragePoint(pointID);
                        countIndexs.push(point.index);
                    });
                    hasPoints = true;
                    createLegend();
                }
            }
        }

        zoomRange.addEventListener('input', function() {
            applyZoom();
        });

        document.getElementById('image-container').addEventListener('dblclick', function(event) {
            var zoomLevel = parseFloat(zoomRange.value); // Obtén el nivel de zoom actual.
            var rect = event.target.getBoundingClientRect();
            var offsetX = (event.clientX - rect.left) / zoomLevel; // Ajusta la posición x basándote en el zoom.
            var offsetY = (event.clientY - rect.top) / zoomLevel;
            var count = 0;
            if (numPoints > 0) {
                const device = countDevices.find(d => d.type == pointID);
                if (device) {
                    count = ++device.count;
                } else {
                    if (!addedPoints.includes(parseInt(pointID))) {
                        countDevices.push({
                            type: pointID,
                            count: 1,
                        });
                        count = 1;
                    }
                }

                index = countDevices.reduce((acc, p) => acc + p.count, 0);
                countIndexs.push(count);
                createPoint(offsetX, offsetY, count);
                numPoints--;
                $('#countPoints').text('Puntos restantes: ' + numPoints);
            }
        });

        document.addEventListener('click', function(e) {
            // Eliminar punto
            if (e.target.classList.contains('popover-delete')) {
                if (confirm("¿Estás seguro de eliminar el punto?")) {
                    var index = extractId(e.target.id);
                    var pointElement = document.getElementById('popoverPoint' + index);
                    if (pointElement) {
                        removePoint(pointElement);
                    }
                    countIndexs = countIndexs.filter(item => item != index);
                    countPoints = {};
                    countLegend();
                    createLegend();
                }
            }

            // Editar punto
            if (e.target.classList.contains('popover-edit')) {
                var index = extractId(e.target.id);
                var pointElement = document.getElementById('popoverPoint' + index);
                var popover = bootstrap.Popover.getInstance(pointElement);
                var point = points.find(item => item.index == index);

                indexModal = index;

                if (popover) {
                    popover.hide();
                }

                if (point) {
                    $(`#update-control-point option[value="${point.pointID}"]`).prop('selected', true);
                    $(`#update-area option[value="${point.areaID}"]`).prop('selected', true);
                    $(`#update-product option[value="${point.productID}"]`).prop('selected', true);
                }

                $('#editPointModalLabel').text(`Punto de control ${index}`)
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
