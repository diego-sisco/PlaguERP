@extends('layouts.app')
@section('content')
    <style>
        #zoom-image {
            user-select: none;
        }

        .point {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            padding: 3px;
        }
    </style>

    <div class="row p-3 border-bottom">
        <a href="{{ Route('customer.edit', ['id' => $floorplan->customer->id, 'type' => $type, 'section' => 8]) }}"
            class="col-auto btn-primary p-0 fs-3"><i class="bi bi-arrow-left m-3"></i></a>
        <h1 class="col-auto fs-2 fw-bold m-0">{{ __('customer.floorplan.title.print') }} [{{ $floorplan->customer->name }}]
        </h1>
    </div>

    <div class="container pt-3">
        <button id="print-button" class="btn btn-primary mb-3">Generar PDF</button>

        <div id="image-container"
            style="
                width: 100%;
                position: relative;
                overflow-y: auto;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
            ">
            <img id="zoom-image" class="border rounded" src="{{ route('image.show', ['filename' => $floorplan->path]) }}"
                alt="Plano"
                style="
                max-width: none;
                width: auto;
                transition: transform 0.3s ease;
                transform-origin: 0 0;
            " />
            <div id="points-container" style="position: absolute; top: 0; left: 0"></div>
        </div>
    </div>
    <script>
        // Tu código previo para crear puntos
        var devices = @json($devices);
        var legend = @json($legend);
        var container = document.getElementById('points-container');
        var img = document.getElementById('zoom-image');
        var points = [];
        var sizes = [];
        var color = '';
        var isImgLong = false;

        function createPoint(x, y, index, nplan, width, height) {
            const newWidth = img.width;
            const newHeight = img.height;

            // Calcular los factores de escala
            const scaleX = newWidth / width; // Factor de escala para el ancho
            const scaleY = newHeight / height; // Factor de escala para la altura

            // Escalar las coordenadas del punto
            const scaledX = x * scaleX;
            const scaledY = y * scaleY;

            // Crear el elemento del punto
            var pointElement = document.createElement('div');
            pointElement.className = 'point';
            pointElement.style.backgroundColor = color;

            // Ajustar la posición del punto en base a las coordenadas escaladas
            pointElement.style.left = (scaledX - 5) + 'px';
            pointElement.style.top = (scaledY - 5) + 'px';
            pointElement.innerText = nplan.toString();

            container.appendChild(pointElement);

            // Guardar el punto escalado en la lista de puntos
            points.push({
                x: scaledX,
                y: scaledY,
                index,
                color,
                nplan,
                element: pointElement
            });
        }

        function setDevices() {
            devices.forEach(function(device) {
                pointID = device.type_control_point_id;
                color = device.color;
                sizes.push({
                    x: device.img_tamx,
                    y: device.img_tamy,
                });
                createPoint(device.map_x, device.map_y, device.itemnumber, device.nplan, device.img_tamx, device
                    .img_tamy);
            });
        }

        $(document).ready(function() {
            img.onload = function() {
                console.log(`Original dimensions - x: ${img.width}, y: ${img.height}`);

                var width = img.width;

                /*const pageWidthMm = 210; // A4 width in mm
                const pageHeightMm = 297; // A4 height in mm
                const mmToPoints = 2.83465; // Conversion factor from mm to points

                // Convert A4 dimensions to points
                const pageWidthPoints = pageWidthMm * mmToPoints;
                const pageHeightPoints = pageHeightMm * mmToPoints;

                // Calculate the scale to fit the image within A4 dimensions
                const widthScale = pageWidthPoints / img.width;
                const heightScale = pageHeightPoints / img.height;
                const scale = Math.min(widthScale, heightScale);

                // Calculate new dimensions maintaining aspect ratio
                const resizedWidth = img.width * scale;
                const resizedHeight = img.height * scale;

                console.log(
                    `Resized dimensions - x: ${resizedWidth.toFixed(2)}, y: ${resizedHeight.toFixed(2)}`);

                // Apply new dimensions to the image
                img.style.width = `${resizedWidth}px`;
                img.style.height = `${resizedHeight}px`;

                // Apply additional logic based on specific conditions
                if (resizedWidth < 800) {
                    img.style.width = '800px';
                } else if (resizedWidth > 1200 && resizedWidth <= 1700) {
                    img.style.width = '1200px';
                } else if (resizedWidth > 1700) {
                    img.style.width = '1000px';
                    isImgLong = true; // Preserving your condition
                }*/

                if (width < 800) {
                    img.style.width = '800px';
                } else if (width > 1200 && width <= 1700) {
                    img.style.width = '1200px';
                } else if (width > 1700) {
                    img.style.width = '1000px';
                    isImgLong = true; // Preserving your condition
                }

                setDevices(); // Call any additional logic
            };

            if (img.complete) {
                img.onload();
            }
        });

        function checkSize() {
            if (sizes.length == 0) return false;
            const firstX = sizes[0].x;
            const firstY = sizes[0].y;
            return sizes.every(size => size.x == firstX && size.y == firstY);
        }

        function formatRange(numbers) {
            numbers.sort((a, b) => a - b);
            let result = [];
            let start = numbers[0];
            let end = numbers[0];

            for (let i = 1; i < numbers.length; i++) {
                if (numbers[i] == end + 1) {
                    end = numbers[i];
                } else {
                    if (start == end) {
                        result.push(start);
                    } else {
                        result.push(`${start}-${end}`);
                    }
                    start = numbers[i];
                    end = numbers[i];
                }
            }

            if (start == end) {
                result.push(start);
            } else {
                result.push(`${start}-${end}`);
            }

            // Unir los resultados con coma
            return result.join(',');
        }

        function splitArrayIntoChunks(array) {
            let result = [];
            let chunk = [];
            let maxSize = 6;

            array.forEach(word => {
                if (chunk.length == maxSize) {
                    result.push(chunk.join(' '));
                    chunk = [];
                }
                chunk.push(word);
            });

            // Agregar el último chunk si no está vacío
            if (chunk.length > 0) {
                result.push(chunk.join(' '));
            }

            return result;
        }

        // Convertir la vista en un PDF
        document.getElementById('print-button').addEventListener('click', function() {
            if (!checkSize()) {
                alert('No hay puntos seleccionados o los puntos no corresponden')
                return;
            }

            const originalWidth = sizes[0].x;
            const originalHeight = sizes[0].y;
            const newWidth = img.width;
            const newHeight = img.height;

            const scaleX = newWidth / originalWidth; // Factor de escala para el ancho
            const scaleY = newHeight / originalHeight;

            var doc = new jspdf.jsPDF('landscape'); // 'landscape' para orientación horizontal

            // Obtener el contenido de la imagen
            var imgElement = document.getElementById('zoom-image');
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');
            canvas.width = imgElement.width;
            canvas.height = imgElement.height;
            ctx.drawImage(imgElement, 0, 0, imgElement.width, imgElement.height);

            var imgData = canvas.toDataURL('image/png'); // Convertir la imagen a data URI

            // Agregar la imagen al PDF
            doc.addImage(imgData, 'PNG', 10, 10, imgElement.width / 4, imgElement.height / 4); // Ajustar el tamaño

            // Agregar los puntos al PDF ajustando la escala
            devices.forEach(function(point) {
                const adjustedX = point.map_x * scaleX; // Ajustar la coordenada X
                const adjustedY = point.map_y * scaleY; // Ajustar la coordenada Y

                doc.setFillColor(point.color);
                doc.circle(10 + adjustedX / 4, 10 + adjustedY / 4, 1, 'F'); // Ajustar posición y tamaño
                doc.setFontSize(10);
                doc.text(point.nplan.toString(), 10 + adjustedX / 4 + 2, 10 + adjustedY / 4 + 2);
            });

            // Crear la simbología
            const symbolSize = 2;
            const lineHeight = 5;
            var startY = 0;
            var startX = 0;
            var count = 0;

            if (isImgLong) {
                startX = 15;
                startY = 180;
            } else {
                startX = doc.internal.pageSize.width - 65;
                startY = 15;
            }

            const maxWidth = doc.internal.pageSize.width - startX - 10;
            const textLines = doc.splitTextToSize(legend.customer, maxWidth);
            const totalTitle = textLines.length * lineHeight;
            const arrTitle = splitArrayIntoChunks(legend.customer.split(" "));
            var titleY = startY;

            doc.setFont('Helvetica', 'bold');
            doc.setFontSize(12);

            if (isImgLong && arrTitle.length > 1) {
                var y = 0;
                arrTitle.forEach((title, index) => {
                    y = titleY + (index * 5);
                    doc.text(title, startX, y, {
                        maxWidth: maxWidth,
                        align: "left"
                    });
                });

                titleY = y;

            } else {
                doc.text(legend.customer, startX, startY, {
                    maxWidth: maxWidth,
                    align: "left"
                });
                titleY += totalTitle;
            }


            doc.setFont('Helvetica', 'normal');
            doc.setFontSize(10);
            doc.text(`Perímetro: ${legend.name}`, startX, titleY + lineHeight);
            doc.text(`Cantidad de dispositivos: ${legend.quantity}`, startX, titleY + (lineHeight * 2));

            var newY = isImgLong ? startY : startY + (lineHeight * 5.5);
            doc.text(``, startX, newY);

            console.log(legend)

            legend.data.forEach(function(item, index) {
                var range = formatRange(item.numbers);
                var xPos = isImgLong ? startX * 8 : startX;
                var yPos = newY + (index * (lineHeight + 5));
                xPos = count == 3 ? xPos + 60 : xPos;
                yPos = count == 3 ? newY : yPos;
                doc.setFillColor(item.color);
                doc.circle(xPos, yPos, symbolSize, 'F');
                doc.setFontSize(10);
                doc.text(`${item.label} [${item.count}]`, xPos + (symbolSize * 2), yPos +
                    1); // Texto con la descripción, desplazado a la derecha
                doc.text(`Numeración: ${range}`, xPos + (symbolSize * 2), yPos +
                    5); // Texto con la descripción, desplazado a la derecha
                count++;
            });
            const filename = `Plano_${legend.name}_${legend.customer}.pdf`;
            doc.save(filename);
        });
    </script>
@endsection
