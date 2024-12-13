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
    </div>
    <script>
        // Tu código previo para crear puntos
        const devices = @json($devices);
        const legend = @json($legend);
        const img_src = "{{ route('image.show', ['filename' => $floorplan->path]) }}";
        const logo_src = "{{ asset('images/logo.png') }}";

        var points = [];
        var size = {};
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
            console.log(devices.length)
            if (devices.length > 0) {
                const {
                    jsPDF
                } = window.jspdf;
                const img = getImageSize();
                print(jsPDF, img);
            }
        });

        async function getImageAsBase64(url) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.crossOrigin = "Anonymous"; // Permite el acceso desde diferentes dominios
                img.src = url;
                img.onload = () => {
                    const canvas = document.createElement("canvas");
                    canvas.width = img.width;
                    canvas.height = img.height;
                    const ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0);
                    const dataURL = canvas.toDataURL("image/jpeg"); // Convierte a Base64
                    resolve({
                        base64: dataURL,
                        width: img.width,
                        height: img.height
                    });
                };
                img.onerror = (err) => reject(err);
            });
        }

        function getImageSize() {
            const equalX = devices.map(item => item.img_tamx).every(val => val == devices[0].img_tamx);
            const equalY = devices.map(item => item.img_tamy).every(val => val == devices[0].img_tamy);

            if (equalX && equalY) {
                return {
                    x: devices[0].img_tamx,
                    y: devices[0].img_tamy
                };
            } else {
                const maxImgTamX = Math.max(...devices.map(item => item.img_tamx));
                const maxImgTamY = Math.max(...devices.map(item => item.img_tamy));
                return {
                    x: maxImgTamX,
                    y: maxImgTamY
                };
            }
        }

        function print(jsPDF, img) {
            const orientation = img.x > img.y ? "l" : "p";
            const pdf = new jsPDF({
                orientation: orientation,
                unit: 'mm',
                format: 'a4' // A4 es 210 x 297 mm
            });

            const originalWidth = img.x;
            const originalHeight = img.y;

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            // Tamaño mínimo requerido para la simbología
            const minSymbolWidth = 50; // Ancho mínimo necesario para la simbología
            const minSymbolHeight = 80; // Alto mínimo necesario para la simbología

            let scale =Math.min(pageWidth / originalWidth, pageHeight / originalHeight)/1.225;

            let imgWidth = originalWidth * scale;
            let imgHeight = originalHeight * scale;

            let spaceWidth = pageWidth - imgWidth - 7;
            let spaceHeight = pageHeight - imgHeight - 7;

            // Si el espacio es insuficiente para la simbología, reduce la escala para la imagen
            if ((spaceWidth > spaceHeight && spaceWidth < minSymbolWidth) || (spaceHeight < minSymbolHeight && spaceWidth < spaceHeight)) {
                const additionalScaleFactor = Math.min(
                    (pageWidth - minSymbolWidth) / originalWidth,
                    (pageHeight - minSymbolHeight) / originalHeight
                );
                scale = Math.min(scale, additionalScaleFactor);

                // Recalcular las dimensiones de la imagen
                imgWidth = originalWidth * scale;
                imgHeight = originalHeight * scale;

                // Recalcular el espacio restante
                spaceWidth = pageWidth - imgWidth - 7;
                spaceHeight = pageHeight - imgHeight - 7;
            }

            const x = 0;
            const y = 0;

            // Agregar la imagen al PDF
            pdf.addImage(img_src, 'JPEG', x, y, imgWidth, imgHeight);

            // Calcular escalas para los puntos
            const scaleX = imgWidth / originalWidth;
            const scaleY = imgHeight / originalHeight;

            // Agregar puntos al PDF
            devices.forEach(function(point) {
                const adjustedX = x + point.map_x * scaleX; // Ajustar la coordenada X
                const adjustedY = y + point.map_y * scaleY; // Ajustar la coordenada Y

                // Dibujar el punto
                pdf.setFillColor(point.color);
                pdf.circle(adjustedX, adjustedY, 1, 'F'); // Ajustar posición y tamaño

                // Agregar el texto junto al punto
                pdf.setFontSize(10);
                pdf.text(point.nplan.toString(), adjustedX + 2, adjustedY + 2);
            });

            const offsetX = spaceWidth * 0.075;
            const offsetY = spaceHeight * 0.075;
            const marginX = offsetX + offsetX;
            const marginY = offsetY + offsetY;

            const simbologyX = imgWidth + offsetX;
            const simbologyY = imgHeight + offsetY;

            // Medidas del logo
            const logoWidth = 602, logoHeight = 180;
            const scaleLogo = Math.min(
                spaceWidth > spaceHeight 
                    ? spaceWidth / logoWidth
                    : spaceHeight / logoHeight,
                0.11
            );

            const logoResizeWidth = (logoWidth - marginX) * scaleLogo;
            const logoResizeHeight = (logoHeight - marginY) * scaleLogo;

               // Texto dinámico para la simbología
            let textTest = legend.customer + "\n\n";
            textTest += "Perímetro: " + legend.name +
                "\n" +
                "Cantidad de dispositivos: " + legend.quantity +
                "\n\n";

            legend.data.forEach(function (item, index) {
                const range = formatRange(item.numbers);
                textTest +=
                    "Tipo de dispositivo/objetivo: " + item.label +
                    "\n" +
                    "Producto tipo de equipo: " + item.product +
                    "\n" +
                    "Numeración: " + range +
                    "\n\n";
            });

            textTest +=
                "Versión del plano: " + legend.floorplan_version +
                "\nFecha de actualizacion: " + legend.date_version;

            if (spaceWidth > spaceHeight) {
                // El espacio esta a la derecha
                pdf.addImage(logo_src, 'PNG', simbologyX, offsetX, logoResizeWidth, logoResizeHeight);
                addWrappedText(pdf, textTest, simbologyX, offsetX + logoResizeHeight + 3, pageWidth - simbologyX - 10, pageHeight - offsetY - 10);
            } else {
                // El espacio esta abajo
                pdf.addImage(logo_src, 'PNG', offsetY, simbologyY, logoResizeWidth, logoResizeHeight);
                if(orientation == "p")
                    addColumnText(pdf, textTest, offsetY, simbologyY + logoResizeHeight + 3, pageWidth / 4 - 5, pageHeight - offsetY + 2, 4);
                else
                    addColumnText(pdf, textTest, offsetY, simbologyY + logoResizeHeight + 3, pageWidth / 5 - 5, pageHeight - offsetY + 2, 5);
            }

            // Guardar el archivo PDF
            const filename = `Plano_${legend.name}_${legend.customer}.pdf`;
            pdf.save(filename);
        }

        // Funcion para formatear texto en negritas
        function formatBoldText(pdf, fontSize = 10) {
            pdf.setFont("helvetica", "bold");
            pdf.setFontSize(fontSize);
            // return text;
        }
        
        // Funcion para formatear texto en negritas
        function formatNormalText(pdf, fontSize = 10) {
            pdf.setFont("helvetica", "normal");
            pdf.setFontSize(fontSize);
            // return text;
        }
        
        // Ajusta el texto por lineas
        function addWrappedText(pdf, text, x, y, maxWidth, maxHeight) {
            const lines = pdf.splitTextToSize(text, maxWidth);
            let currentY = y;

            for (const line of lines) {
                if (currentY + 10 > maxHeight) break; // Detener si excede la altura máxima
                legend.customer.includes(line) ? formatBoldText(pdf, 12) : formatNormalText(pdf, 10);
                pdf.text(line, x, currentY);
                currentY += 5; // Espaciado entre líneas
            }
        }

        // Ajustar texto en columnas
        function addColumnText(pdf, text, startX, startY, columnWidth, maxHeight, numColumns) {
            const lines = pdf.splitTextToSize(text, columnWidth);
            let currentX = startX;
            let currentY = startY;

            for (const line of lines) {
                if (currentY + 10 > maxHeight) {
                    // Mover a la siguiente columna
                    currentX += columnWidth + 5; // Espaciado entre columnas
                    currentY = startY;

                    if (currentX > startX + columnWidth * numColumns) break; // Detener si excede el número de columnas
                }
                legend.customer.includes(line) ? formatBoldText(pdf, 12) : formatNormalText(pdf, 10);
                pdf.text(line, currentX, currentY);
                currentY += 5;
            }
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
                result.push(`${start} - ${end}`);
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

            /*
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
            */
        });
    </script>
@endsection
