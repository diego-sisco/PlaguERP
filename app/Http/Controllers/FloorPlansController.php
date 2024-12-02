<?php

namespace App\Http\Controllers;

use App\Models\OrderIncidents;
use Illuminate\Http\Request;
use TCPDF;
use App\PDF\PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\FloorPlans;
use App\Models\ProductCatalog;
use App\Models\ControlPoint;
use App\Models\Device;
use App\Models\CustomerContract;
use App\Models\Customer;
use App\Models\Contract;
use App\Models\ContractService;
use App\Models\Service;
use App\Models\ApplicationMethod;
use App\Models\ApplicationMethodService;
use App\Models\ApplicationArea;
use App\Models\ProductPest;
use App\Models\FloorplanVersion;
use App\Models\OrderInsidences;
use App\Models\Branch;
use App\Models\OrderName;

use Mpdf\Mpdf;

use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCode;
use Intervention\Image\ImageManagerStatic as Image;

class FloorPlansController extends Controller
{
    private $path = 'floorplans/';

    private function countControlPoints($nestedDevices)
    {
        $result = [];
        foreach ($nestedDevices as $devices) {
            foreach ($devices as $control_point_id) {
                if (!isset($result[$control_point_id])) {
                    $result[$control_point_id] = ['control_point_id' => $control_point_id, 'count' => 1];
                } else {
                    $result[$control_point_id]['count']++;
                }
            }
        }
        return array_values($result);
    }

    public function getImage(string $filename)
    {
        $url = /*$this->path*/ '/' . $filename;

        if (!Storage::disk('public')->exists($url)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($url);
        $type = Storage::disk('public')->mimeType($url);

        return response($file, 200)->header('Content-Type', $type);
    }

    public function getDevicesVersion(Request $request, string $id)
    {
        $devices = null;
        $version = $request->input('version');

        if (empty($id) || empty($version)) {
            return response()->json('Faltan parámetros necesarios.');
        }

        $devices = Device::where('floorplan_id', $id)->where('version', $version)
            ->select('id', 'type_control_point_id', 'floorplan_id', 'application_area_id', 'nplan', 'latitude', 'itemnumber', 'longitude', 'map_x', 'map_y', 'color', 'code')
            ->get();
        return response()->json($devices);
    }

    public function index(string $id)
    {
        $error = $success = $warning = $contract = $status = null;
        $client = Customer::where('id', $id)->first();
        $floorplans = FloorPlans::where('customer_id', $id)->get();
        $contract = Contract::where('customer_id', $id)->first();

        if ($contract != null) {
            $status = true;
        } else {
            $warning = "El cliente no tiene un contrato activo, no podrás editar los puntos de control.";
            $status = false; //false;
        }

        return view('floorplans.index', compact('status', 'floorplans', 'client', 'error', 'success', 'warning'));
    }

    public function process(string $id)
    {
        $error = null;
        $success = null;
        $warning = null;
        $customerID = $id;
        $client = Customer::where('id', $id)->first();
        $floorplans = FloorPlans::where('customer_id', $id)->get();

        // Obtener los ID de contrato para un cliente específico
        $contractIds = Contract::where('customer_id', $id)->pluck('id')->toArray();

        // Si hay contratos, obtener los ID de servicio asociados
        $serviceIds = [];
        if (!empty($contractIds)) {
            $serviceIds = ContractService::whereIn('contract_id', $contractIds)->pluck('service_id')->toArray();
        }

        // Obtener los servicios correspondientes a los ID de servicio
        $services = Service::when($serviceIds, function ($query) use ($serviceIds) {
            return $query->whereIn('id', $serviceIds);
        })->get();

        return view('floorplans.create', compact('services', 'floorplans', 'client', 'error', 'success', 'warning'));
    }

    public function create(string $id)
    {
        $customer = Customer::find($id);
        return view('floorplans.create', compact('customer'));
    }

    public function print(string $id, string $type)
    {
        $data = [];
        $floorplan = FloorPlans::findOrFail($id);
        if ($floorplan->service_id) {
            $latestVersionNumber = $floorplan->versions()->latest('version')->value('version');
            $devices = Device::where('floorplan_id', $id)->where('version', $latestVersionNumber)
                ->select('id', 'type_control_point_id', 'floorplan_id', 'application_area_id', 'product_id', 'nplan', 'latitude', 'itemnumber', 'longitude', 'map_x', 'map_y', 'img_tamx', 'img_tamy', 'color', 'code')
                ->get();

            foreach ($devices as $device) {
                $type = $device->type_control_point_id;
                $filtered = array_filter($data, function ($item) use ($type) {
                    return $item['type'] == $type;
                });
                if ($filtered) {
                    $key = key($filtered);
                    $data[$key]['count']++;
                    $data[$key]['numbers'][] = $device->nplan;
                } else {
                    $data[] = [
                        'type' => $type,
                        'label' => $device->controlPoint->name,
                        'color' => $device->controlPoint->color,
                        'count' => 1,
                        'numbers' => [$device->nplan]
                    ];
                }
            }
            $legend = [
                'name' => $floorplan->filename,
                'customer' => $floorplan->customer->name,
                'service' => $floorplan->service->name,
                'quantity' => $devices->count(),
                'data' => $data
            ];
        } else {
            session()->flash('error', 'Impresion no permitida, sin servicio asociado.');
            return back();
        }

        return view('floorplans.print', compact('floorplan', 'devices', 'legend', 'type'));
    }

    public function store(Request $request, string $customerID, int $type)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:10000'
        ]);

        $file = $request->file('file');
        $url = $this->path . $customerID . '/' . time() . '_' . $file->getClientOriginalName();

        Storage::disk('public')->put($url, file_get_contents($file));

        $floorplan = new FloorPlans();
        $floorplan->fill($request->all());
        $floorplan->customer_id = $customerID;
        $floorplan->service_id = $request->input('service_id') != 0 ? $request->input('service_id') : null;
        $floorplan->path = $url;
        $floorplan->save();

        return redirect()->route('customer.edit', ['id' => $customerID, 'type' => $type, 'section' => 8]);
    }

    public function edit(string $id, string $customerID, int $type, int $section)
    {
        $data = [];
        $floorplan = FloorPlans::findOrFail($id);
        $customer = Customer::find($customerID);
        $floorplanIds = $customer->floorplans()->get()->pluck('id');
        $count = 1;
        /*foreach ($floorplanIds as $floorplanId) {
            $devices = Device::where('floorplan_id', $floorplanId)->get();
            foreach ($devices as $i => $device) {
                $index = $i + 1;
                $code = $device->code ?? ControlPoint::find($device->type_control_point_id)->code . '-' . $count;
                $data[] = [
                    'id' => $count,
                    'type_control_point_id' => $device->type_control_point_id,
                    'floorplan_id' => $floorplanId,
                    'application_area_id' => $device->application_area_id,
                    'product_id' => $device->product_id,
                    'qr' => QrCode::format('png')->size(200)->generate($code),
                    'itemnumber' => $count,
                    'nplan' => $index,
                    'version' => 1,
                    'latitude' => null,
                    'longitude' => null,
                    'map_x' => $device->map_x,
                    'map_y' => $device->map_y,
                    'img_tamx' => $device->img_tamx,
                    'img_tamy' => $device->img_tamy,
                    'color' => $device->color,
                    'code' => $code,
                    'created_at' => $device->created_at,
                    'updated_at' => now(),
                ];
                $count++;
            }

        }*/

        $latestVersionNumber = $floorplan->versions()->latest('version')->value('version');
        $customer = Customer::findOrFail($customerID);
        $services = Service::all();
        $applications_areas = ApplicationArea::where('customer_id', $customerID)->get();
        $devices = Device::where('floorplan_id', $id)->where('version', $latestVersionNumber)
            ->select('id', 'type_control_point_id', 'floorplan_id', 'application_area_id', 'product_id', 'nplan', 'latitude', 'itemnumber', 'longitude', 'map_x', 'map_y', 'img_tamx', 'img_tamy', 'color', 'code')
            ->get();

        // Obtener las últimas 4 revisiones para cada dispositivo
        $deviceRevisions = [];
        foreach ($devices as $device) {
            $revisions = OrderIncidents::where('device_id', $device->id)
                ->orderBy('updated_at', 'desc')
                ->limit(4)
                ->select('answer', 'updated_at')
                ->get();
            $deviceRevisions[$device->id] = $revisions;
        }

        $ctrlPoints = [];
        $countDevices = [];
        $product_names = [];
        $products = ProductCatalog::where('presentation_id', '!=', 1)->get();


        if ($floorplan->service != null) {
            $ctrlPoints = ControlPoint::all();
            $floorplans = Floorplans::where('customer_id', $customerID)->get();
            foreach ($floorplans as $f) {
                $latestVersionNumber = $f->versions()->latest('version')->value('version');
                $ds = Device::where('floorplan_id', $f->id)->where('version', $latestVersionNumber)
                    ->select('id', 'type_control_point_id', 'itemnumber')
                    ->get();
                foreach ($ds as $d) {
                    $type = $d->type_control_point_id;
                    $filtered = array_filter($countDevices, function ($item) use ($type) {
                        return $item['type'] == $type;
                    });
                    if ($filtered) {
                        $key = key($filtered);
                        $countDevices[$key]['count']++;
                    } else {
                        $countDevices[] = [
                            'type' => $type,
                            'count' => 1,
                        ];
                    }
                }
            }
        }

        // Compacta las variables para pasarlas a la vista
        return view('floorplans.edit', compact('ctrlPoints', 'applications_areas', 'services', 'customer', 'devices', 'deviceRevisions', 'floorplan', 'products', 'countDevices', 'type', 'section'));
    }

    public function searchDevices(Request $request, string $floorplanId)
    {
        try {
            $version = $request->input('version');
            $pointId = $request->input('point');
            $zoneId = $request->input('zone');

            $devices = Device::where('version', $version)->where('floorplan_id', $floorplanId);

            if ($pointId != "null") {
                $devices = $devices->where('type_control_point_id', $pointId);
            }

            if ($zoneId != "null") {
                $devices = $devices->where('application_area_id', $zoneId);
            }

            $devices = $devices->get();

            $data = [];
            foreach ($devices as $device) {
                $data[] = [
                    'device_id' => $device->id,
                    'nplan' => $device->nplan,
                    'color' => $device->color,
                    'code' => $device->code,
                    'type' => $device->controlPoint->name,
                    'zone' => $device->applicationArea->name,
                    'version' => $device->version,
                ];
            }
            return response()->json([
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while searching for devices.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id, int $section)
    {
        //dd($request);
        $request->validate([
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $floorplan = FloorPlans::find($id);

        if ($section == 1) {
            $floorplan->fill($request->except('file'));
            $floorplan->customer_id = $floorplan->customer->id;
            $floorplan->service_id = $request->input('service_id') != 0 ? $request->input('service_id') : null;

            if ($request->hasFile('file')) {
                // Si hay una imagen nueva, eliminar la imagen anterior si existe
                if ($floorplan->path && Storage::disk('local')->exists('public/floorplans/' . $floorplan->path)) {
                    Storage::disk('local')->delete('public/floorplans/' . $floorplan->path);
                }

                $newFilename = $floorplan->customer->id . '/' . $floorplan->customer->id . '_' . time() . '.' . $request->file('file')->getClientOriginalExtension();
                $request->file('file')->storeAs('public/floorplans/', $newFilename, 'local');
                $floorplan->path = 'floorplans/' . $newFilename;
            }
            $floorplan->save();
        }

        if ($section == 2) {
            $pointsData = json_decode($request->input('points'));
            $latestVersionNumber = $floorplan->versions()->latest('version')->value('version');
            $latestVersionNumber++;
            FloorplanVersion::insert([
                'floorplan_id' => $floorplan->id,
                'version' => $latestVersionNumber,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($pointsData as $point) {
                $found_point = ControlPoint::find($point->pointID);
                $code = $point->code ?? ($found_point->code . '-' . $found_point->nplan);
                $device = Device::updateOrCreate(
                    [
                        'type_control_point_id' => $point->pointID,
                        'floorplan_id' => $id,
                        'nplan' => $point->pointCount,
                        'version' => $latestVersionNumber,
                    ],
                    [
                        'application_area_id' => $point->areaID,
                        'product_id' => $point->productID > 0 ? $point->productID : null,
                        'itemnumber' => $point->index,
                        'map_x' => $point->x,
                        'map_y' => $point->y,
                        'color' => $point->color,
                        'code' => $code,
                        'img_tamx' => $point->img_tamx,
                        'img_tamy' => $point->img_tamy,
                    ]
                );
                //$code = 
                $device->qr = QrCode::format('png')->size(200)->generate($code);
                $device->save();
            }
        }

        return redirect()->back();
    }

    public function delete($id, $customerID, $type)
    {
        $exist_report_name = OrderName::find($id);

        if ($exist_report_name && $exist_report_name->order_id) {
            // Existe un reporte asociado
            $client_id = $exist_report_name->client_id;
            $mensaje = "No se puede eliminar este plano, ya tiene una orden asociada.";
        } else {
            // No hay órdenes asociadas, se puede eliminar
            Device::where('floorplan_id', $id)->delete();
            $floorplan = FloorPlans::find($id);

            if ($floorplan) {
                $floorplan->delete();
                $mensaje = "Plano y dispositivos borrados exitosamente.";
            } else {
                $mensaje = "No se encontró el plano.";
            }

            // Si $exist_report_name no existe, utiliza el $customerID proporcionado en la ruta
            $client_id = $customerID;
        }

        // Redirigir a la ruta 'customer.edit' con los parámetros adecuados
        return redirect()->route('customer.edit', ['id' => $client_id, 'type' => $type, 'section' => 8])->with('mensaje', $mensaje);
    }

    public function getQR(string $id)
    {
        $devices = $customer = null;
        $floorplan = FloorPlans::find($id);
        $version = $floorplan->versions()->latest('version')->value('version');

        $devices = Device::where('floorplan_id', $id)
            ->where('version', $version)
            ->orderBy('type_control_point_id')
            ->orderBy('application_area_id')
            ->get();

        $control_points = ControlPoint::all();

        $zone_ids = $devices->pluck('application_area_id')->unique();
        $zones_areas = ApplicationArea::whereIn('id', $zone_ids)->get();
        //dd($types);
        $customer = Customer::find($floorplan->customer_id);
        $type = $customer->service_type_id;
        return view('floorplans.selectqrs', compact('devices', 'floorplan', 'type', 'control_points', 'zones_areas'));
    }

    public function getVersionQR(Request $request, string $id)
    {
        $devices = null;
        $floorplan = FloorPlans::find($id);
        $version = intval($request->input('version'));

        $devices = Device::where('floorplan_id', $id)
            ->where('version', $version)
            ->orderBy('type_control_point_id')
            ->orderBy('application_area_id')
            ->get();

        return response()->json([
            'devices' => $devices,
            'floorplan' => $floorplan,
        ]);
    }

    public function printQR(Request $request, string $id)
    {
        $selected_devices = json_decode($request->input('selected_devices'));

        if (empty($selected_devices)) {
            session()->flash('error', 'Sin dispositivos seleccionados');
            return back();
        }

        $devices = Device::whereIn('id', $selected_devices)->get();
        $floorplan = Floorplans::find($id);

        $pdf = new TCPDF();
        $pdf->setPrintHeader(false);

        $margin = 5;

        // Establece el margen y agregar pagina
        $pdf->SetMargins($margin, $margin, $margin, $keepmargins = true);
        $pdf->SetAutoPageBreak(true, $margin);

        $pdf->AddPage();

        $width = $pdf->getPageWidth();
        $height = $pdf->getPageHeight();

        // Calcula el ancho de las celdas
        $cellWidth = ($width - $margin * 2) / 2;
        $cellHeight = ($height - $margin * 2) / 7;

        // Posición inicial de X y Y
        $startX = $pdf->GetX();
        $startY = $pdf->GetY();

        //dd('width: ' . $width .' height: '. $height);
        //dd('x: ' . $x . ' y: ' . $y);
        //dd('Cell width: ' . $cellWidth .' height: '. $cellHeight);

        $x = $startX;
        $y = $startY;
        foreach ($devices as $device) {
            if ($y + $cellHeight > $pdf->getPageHeight() - $margin) {
                $pdf->AddPage(); // Agrega una nueva página
                $x = $startX;
                $y = $startY;
            }

            // Ruta de la imagen
            $imagePath = public_path('images/logo.png');

            // Agrega la imagen al PDF
            $pdf->Rect($x, $y, $cellWidth - ($margin * 3), $cellHeight - $margin, 'D');
            $pdf->Image($imagePath, $x + $margin / 3, $y, $cellWidth / 2.5, $cellHeight / 3, 'png');

            //$y = $pdf->GetY() + $margin;
            $stepY = $pdf->GetY() + ($margin * 2.5);
            $pdf->setXY($x + $margin / 3, $stepY);

            $pdf->SetFont('helvetica', 'B', 8); // 'B' para negrita            
            $pdf->MultiCell(($cellWidth / 2) - $margin / 3, 0, $floorplan->customer->name, 0, 'L', false);
            $pdf->SetFont('helvetica', '', 8); // 'B' para negrita            
            $pdf->Ln(2);

            $stepY = $pdf->GetY();
            $pdf->setXY($x + $margin / 3, $stepY);

            $pdf->MultiCell(($cellWidth / 2) - $margin / 3, 0, $floorplan->filename, 0, 'L', false);
            $pdf->Ln(1);

            $stepY = $pdf->GetY();
            $pdf->setXY($x + $margin / 3, $stepY);

            $pdf->SetFont('helvetica', 'B', 12); // 'B' para negrita            
            $pdf->MultiCell(($cellWidth / 2) - $margin / 3, 0, $device->controlPoint->name . ' #' . $device->nplan, 0, 'L', false);
            $pdf->SetFont('helvetica', '', 10); // 'B' para negrita

            $qrFileName = 'qr_' . $device->id . '.png';
            $qrImagePath = public_path('qr_codes/' . $qrFileName);
            QrCode::format('png')->size(500)->generate($device->id, $qrImagePath);

            $imgX = $x + $cellWidth / 2;
            $imgY = $y + $margin / 2;

            $pdf->Image($qrImagePath, $imgX, $imgY, $cellHeight - ($margin * 2), $cellHeight - ($margin * 2), 'PNG');

            $x += $cellWidth;

            if ($x + $cellWidth > $pdf->getPageWidth() - $margin) {
                $x = $startX;
                $y += $cellHeight;
            }

            $pdf->setXY($x, $y);
        }

        $filename = $floorplan->customer->name . '_QR_PuntosControl_' . $floorplan->filename . '.pdf';
        // Cierra el documento PDF
        $pdf->Output($filename, 'I');
    }
}
