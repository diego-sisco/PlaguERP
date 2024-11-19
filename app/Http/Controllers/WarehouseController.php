<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Technician;
use App\Models\Warehouse;
use App\Models\MovementType;
use App\Models\ProductCatalog;
use App\Models\Lot;
use App\Models\Service;
use App\Models\WarehouseMovement;
use App\Models\WarehouseMovementProduct;

use Illuminate\Http\Request;
use TCPDF;

class WarehouseController extends Controller
{
    private $states_route = 'datas/json/Mexico_states.json';

    private $cities_route = 'datas/json/Mexico_cities.json';

    private $size = 50;

    private function createStock(string $id)
    {
        $stocks = [];
        $warehouse_input_movements = WarehouseMovement::where('warehouse_id', $id)->whereBetween('movement_id', [1, 4])->get();
        $warehouse_output_movements = WarehouseMovement::where('warehouse_id', $id)->whereNotBetween('movement_id', [1, 4])->get();

        foreach ($warehouse_input_movements as $input) {
            $existingProductKey = array_search($input->product_id, array_column($stocks, 'product_id'));
            if ($existingProductKey !== false) {
                $stocks[$existingProductKey]['cant'] += $input->amount;
            } else {
                $stocks[] = [
                    'product_id' => $input->product_id,
                    'product_name' => $input->product->name,
                    'product_metric' => $input->product->metric,
                    'cant' => $input->amount
                ];
            }
        }

        foreach ($warehouse_output_movements as $input) {
            $existingProductKey = array_search($input->product_id, array_column($stocks, 'product_id'));
            if ($existingProductKey !== false) {
                $stocks[$existingProductKey]['cant'] -= $input->amount;
            } else {
                $stocks[] = [
                    'product_id' => $input->product_id,
                    'product_name' => $input->product->name,
                    'product_metric' => $input->product->metric,
                    'cant' => $input->amount
                ];
            }
        }

        return $stocks;
    }

    public function index()
    {
        $stocks = [];
        $warehouses = Warehouse::orderBy('technician_id')->get();
        $input_movements = MovementType::whereBetween('id', [1, 4])->get();
        $output_movements = MovementType::whereBetween('id', [5, 10])->get();
        $products = ProductCatalog::all();
        $branches = Branch::all();
        $technicians = Technician::all();
        $lots = Lot::all();

        foreach ($warehouses as $warehouse) {
            $stocks[] = [
                'warehouse_id' => $warehouse->id,
                'stock' => $this->createStock($warehouse->id)
            ];
        }
        return view('warehouse.index', compact('warehouses', 'input_movements', 'output_movements', 'products', 'lots', 'stocks', 'branches', 'technicians'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $warehouse = new Warehouse();
        $warehouse->fill($request->all());
        $warehouse->save();

        return redirect()->route('warehouse.index');
    }

    public function storeMovement(Request $request)
    {
        try {
            $warehouse_movement = new WarehouseMovement($request->except('_token'));
            $warehouse_movement->user_id = auth()->user()->id;
            $warehouse_movement->time = now()->format('H:i:s');
            $warehouse_movement->save();
            session()->flash('success', 'Movimiento agregado exitosamente');
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un problema al agregar el movimiento: ' . $e->getMessage());
        }
        return back();
    }


    public function edit(string $id)
    {
        $warehouse = Warehouse::find($id);
        $branches = Branch::all();
        $states = json_decode(file_get_contents(public_path($this->states_route)), true);
        $cities = json_decode(file_get_contents(public_path($this->cities_route)), true);

        return view('warehouse.edit', compact('warehouse', 'branches', 'states', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($request->except('_token'));
        return back();
    }


    public function movements(string $id)
    {
        $warehouse = Warehouse::find($id);
        if (!$warehouse) {
            return back();
        }
        return view('warehouse.show.movements', compact('warehouse'));
    }

    public function allMovements()
    {
        $movements = WarehouseMovement::all();
        return view('warehouse.movements', compact('movements'));
    }
    
    public function show(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('warehouse.show', compact('warehouse'));
    }

    public function destroy(string $id)
    {
        $warehouse = Warehouse::find($id);
        $warehouse->delete();
        session()->flash('success', 'Almacen eliminado correctamente');
        return redirect()->back();
    }

    public function movement_print(string $id)
    {
        $movement = WarehouseMovement::with(['sourceWarehouse', 'destinationWarehouse', 'user', 'products.product', 'movementType'])
            ->where('id', $id)
            ->first();
        //almacen de donde se realizo el movimiento
        $warehouse = Warehouse::find($movement->source_warehouse_id);
        $products = WarehouseMovementProduct::where('warehouse_movement_id', $id)->get();
        //dd($products);
        $pdf = new TCPDF();
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->setPrintHeader(false);

        // Establece la información del documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Siscoplagas');
        $pdf->SetTitle('Movimiento de almacén');

        // Añade una página
        $pdf->AddPage();

        // Añadir header personalizado
        $this->addCustomHeader($pdf, $movement->date, $movement->time, $movement->id);

        // Márgenes
        $margin = 10;
        $heightPage = $pdf->getPageHeight() - ($margin * 2);
        $widthPage = $pdf->getPageWidth() - ($margin * 2);

        // Configura posición y tamaño
        $x = $margin;
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y + $margin);
        $y += 10;
        // Establecer grosor de la línea
        $pdf->SetLineWidth(0.5); // Grosor de la línea en mm
        // Establecer el color de la línea (por ejemplo, azul)
        $pdf->SetDrawColor(133, 141, 72); // RGB para azul

        // Establecer el grosor de la línea (más delgada)
        $pdf->SetLineWidth(0.25); // Grosor de la línea en mm

        // Dibujar una línea horizontal
        $xStart = $margin; // Coordenada X de inicio
        $yStart = $y += 5; // Coordenada Y de inicio
        $xEnd = $pdf->getPageWidth() - 10; // Coordenada X de fin
        $yEnd = $y; // Coordenada Y de fin (misma que la inicial para una línea horizontal)

        $pdf->Line($xStart, $yStart, $xEnd, $yEnd);

        // Establece fuente y color de fondo para los títulos
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(255, 255, 255);
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y + $margin);
        // Datos del Movimiento
        $pdf->MultiCell(0, 0, 'Datos del Movimiento', 0, 'L', 0, 1, $x, $y);
        $y += 10;
        $pdf->SetFont('helvetica', '', 9);
        $pdf->MultiCell(0, 0, "EMPLEADO: " . $movement->user->name, 0, 'L', 0, 1, $x, $y);
        $y += 5;
        $pdf->MultiCell(0, 0, "FECHA: " . $movement->date, 0, 'L', 0, 1, $x, $y);
        $y += 5;
        if ($movement->movement_type_id >= 1 && $movement->movement_type_id <= 5) {
            $pdf->MultiCell(0, 0, "E/S: Entrada", 0, 'L', 0, 1, $x, $y);
        } else {
            $pdf->MultiCell(0, 0, "E/S: Salida", 0, 'L', 0, 1, $x, $y);
        }
        $y += 5;
        $pdf->MultiCell(0, 0, "TIPO: " . $movement->movementType->name, 0, 'L', 0, 1, $x, $y);
        $y += 5;
        $pdf->MultiCell(0, 0, "ALMACÉN: " . $movement->sourceWarehouse->name, 0, 'L', 0, 1, $x, $y);
        $y += 5;
        if ($warehouse->source_warehouse_id) {
            $pdf->MultiCell(0, 0, "BLOQUEADO: SI", 0, 'L', 0, 1, $x, $y);
        } else {
            $pdf->MultiCell(0, 0, "BLOQUEADO: NO", 0, 'L', 0, 1, $x, $y);
        }
        $y += 5;
        if ($movement->destination_warehouse_id) {
            $pdf->MultiCell(0, 0, "ALMACÉN DE DESTINO: " . $movement->destination_warehouse_id, 0, 'L', 0, 1, $x, $y);
        } else {
            $pdf->MultiCell(0, 0, "ALMACÉN DE DESTINO: No aplica", 0, 'L', 0, 1, $x, $y);
        }
        $y += 5;
        $pdf->MultiCell(0, 0, "COMENTARIOS: " . $movement->remarks, 0, 'L', 0, 1, $x, $y);
        $pdf->SetDrawColor(0, 0, 0); // RGB para negro
        $y += 10;

        // Incrementar la posición Y para la siguiente línea de texto
        $y += 5;
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell(0, 0, 'Listado de productos', 0, 'L', 0, 1, $x, $y);
        // Establecer grosor de la línea
        $pdf->SetLineWidth(0.5); // Grosor de la línea en mm
        // Establecer el color de la línea (por ejemplo, azul)
        $pdf->SetDrawColor(133, 141, 72); // RGB para azul

        // Establecer el grosor de la línea (más delgada)
        $pdf->SetLineWidth(0.25); // Grosor de la línea en mm

        // Dibujar una línea horizontal
        $xStart = $margin; // Coordenada X de inicio
        $yStart = $y += 5; // Coordenada Y de inicio
        $xEnd = $pdf->getPageWidth() - 10; // Coordenada X de fin
        $yEnd = $y; // Coordenada Y de fin (misma que la inicial para una línea horizontal)

        $pdf->Line($xStart, $yStart, $xEnd, $yEnd);

        // Espacio para separar secciones
        $y += 10;
        $pdf->SetDrawColor(117, 170, 220); // RGB para azul

        // Definir el ancho de las celdas
        $cellWidth = $widthPage / 5;
        $pdf->Ln();
        // Encabezados de la tabla
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell($cellWidth, 7, 'Producto', 1, 0, 'C', 0);
        $pdf->Cell($cellWidth, 7, 'Cantidad', 1, 0, 'C', 0);
        $pdf->Cell($cellWidth, 7, 'Tipo', 1, 0, 'C', 0);
        $pdf->Cell($cellWidth, 7, 'Lote', 1, 0, 'C', 0);
        $pdf->Cell($cellWidth, 7, 'Fecha de Caducidad', 1, 1, 'C', 0);
        $pdf->SetFont('helvetica', '', 9);
        foreach ($products as $product) {
            $pdf->Cell($cellWidth, 7, $product->product->name, 1, 0, 'C', 0);
            $pdf->Cell($cellWidth, 7, $product->amount . ' ' . $product->unit, 1, 0, 'C', 0);
            $pdf->Cell($cellWidth, 7, $movement->movementType->name, 1, 0, 'C', 0);
            $pdf->Cell($cellWidth, 7, $product->getLotNumber(), 1, 0, 'C', 0);
            $pdf->Cell($cellWidth, 7, $product->getExpirationDate() ?? '-', 1, 1, 'C', 0); // Salto de línea para la siguiente fila
            $y += 7; // Añadir altura de la fila a la posición Y
        }

        $pdf->SetDrawColor(0, 0, 0); // RGB para negro
        $y += 10;

        // Registros de auditoría
        $y += 5;
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell(0, 0, 'Registros de auditoría', 0, 'L', 0, 1, $x, $y);
        // Establecer grosor de la línea
        $pdf->SetLineWidth(0.5); // Grosor de la línea en mm
        // Establecer el color de la línea (por ejemplo, azul)
        $pdf->SetDrawColor(133, 141, 72); // RGB para azul

        // Establecer el grosor de la línea (más delgada)
        $pdf->SetLineWidth(0.25); // Grosor de la línea en mm

        // Dibujar una línea horizontal
        $xStart = $margin; // Coordenada X de inicio
        $yStart = $y += 5; // Coordenada Y de inicio
        $xEnd = $pdf->getPageWidth() - 10; // Coordenada X de fin
        $yEnd = $y; // Coordenada Y de fin (misma que la inicial para una línea horizontal)

        $pdf->Line($xStart, $yStart, $xEnd, $yEnd);

        $y += 5;
        $pdf->SetFont('helvetica', '', 9);
        $pdf->MultiCell(0, 0, "Usuario: " . $movement->user->name, 0, 'L', 0, 1, $x, $y);

        $y += 5;
        $pdf->MultiCell(0, 0, "Correo: " . $movement->user->email, 0, 'L', 0, 1, $x, $y);
        $y += 5;
        $pdf->MultiCell(0, 0, "Fecha: " . $movement->date, 0, 'L', 0, 1, $x, $y);
        $y += 5;

        // Salida del PDF
        $pdf->Output('movimiento_almacen.pdf', 'I');

        // Envía el PDF al navegador
        $pdf->Output('ejemplo_tcpdf.pdf', 'I');
    }

    // Método para el header personalizado
    private function addCustomHeader($pdf, $date, $time, $idm)
    {
        $margin = 10;
        $heightPage = $pdf->getPageHeight() - ($margin * 2);
        $widthPage = $pdf->getPageWidth() - ($margin * 2);

        // Establecer la posición del header
        $pdf->SetY($margin); // Posición desde arriba
        $pdf->SetX($margin); // Posición desde la izquierda

        // Establecer fuente para el header
        $pdf->SetFont('helvetica', 'B', 12);

        // Agregar texto al header
        $pdf->Cell(0, 10, 'Fecha: ' . $date . ' Hora: ' . $time . '    Movimiento de Almacen: ' . $idm, 0, 1, 'L');



        // Configura la posición para la imagen
        $imageWidth = 30;  // Ancho de la imagen
        $imageHeight = 10; // Alto de la imagen
        $imageX = $margin;  // Coordenada X para la imagen
        $imageY = $pdf->GetY(); // Coordenada Y para la imagen (justo debajo del texto)

        // Ruta de la imagen
        $imagePath = public_path('images/logo.png');

        // Agregar la imagen
        $pdf->Image($imagePath, $imageX, $imageY, $imageWidth, $imageHeight, 'PNG');
    }
}
