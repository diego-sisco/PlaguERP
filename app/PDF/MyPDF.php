<?php
namespace App\PDF;

use App\Models\ApplicationArea;
use App\Models\ApplicationMethod;
use App\Models\ControlPoint;
use App\Models\DevicePest;
use App\Models\DeviceStates;
use App\Models\OrderArea;
use App\Models\OrderIncidents;
use App\Models\OrderProduct;
use App\Models\LineBusiness;
use App\Models\Branch;
use App\Models\Order;
use App\Models\FloorPlans;
use App\Models\Device;
use App\Models\OrderName;
use App\Models\Recommendations;
use App\Models\ServiceDetails;
use App\Models\UserFile;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use TCPDF;

class MyPDF extends TCPDF
{
    private $orderId, $margin, $widthPage, $heightPage, $startX, $startY;
    private $states_route = 'datas/json/Mexico_states.json';


    public function __construct($orderId)
    {
        parent::__construct();
        $margin = 10;
        $this->orderId = $orderId;
        $this->margin = $margin;
        $this->widthPage = $this->getPageWidth() - $margin;
        $this->heightPage = $this->getPageHeight() - $margin;
        $this->SetMargins($margin, $margin * 5, $margin); // Margen izquierdo, margen superior, margen derecho
        $this->SetAutoPageBreak(true, $margin); // Habilitar el ajuste automático de página
    }

    // Añadimos la función para la marca de agua con imagen
    private function watermark()
    {
        $this->SetAlpha(0.1);

        $pageWidth = $this->getPageWidth() + ($this->margin * 2);
        $pageHeight = $this->getPageHeight() + ($this->margin * 2);

        $imageWidth = 220;
        $imageHeight = 300;

        $x = ($pageWidth - $imageWidth) / 2;
        $y = ($pageHeight - $imageHeight) / 2;

        // Añade la imagen
        $this->Image(public_path('images/marcadeagua.png'), $x, $y, $imageWidth, $imageHeight, '', '', '', false, 300, '', false, false, 0);
        $this->SetAlpha(1);
    }

    private function getNameByKey($key, $data)
    {
        $result = collect($data)->firstWhere('key', $key);
        return $result ? $result['name'] : null; // O puedes retornar un mensaje personalizado
    }

    private function getSignature($signature_base64)
    {
        $signature = base64_decode($signature_base64);
        $temp_file = tempnam(sys_get_temp_dir(), 'signature_') . '.png';
        file_put_contents($temp_file, $signature);
        return $temp_file;
    }

    private function isEndPage()
    {
        //dd('F: ' . $this->PageBreakTrigger . ' Y: ' . $this->GetY() + 20);
        if ($this->GetY() + 20 > $this->PageBreakTrigger) {
            $this->AddPage();
        }
    }

    // Sobrescribimos la función AddPage para incluir la marca de agua
    public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false)
    {
        parent::AddPage($orientation, $format, $keepmargins, $tocpage);
        $this->watermark();
    }

    public function Header()
    {
        $order = Order::find($this->orderId);
        $step = 20;

        if ($order) {
            $x = $this->GetX();
            $y = $this->GetY() + $step;
            $this->SetFont('helveticaB', '', 15);
            $this->SetTextColor(190, 205, 97);
            $this->Cell($x, $y, 'Certificado de Servicio Nº ' . $order->id, 0, 1, 'L');

            $img_width = 80;
            $img_height = 25;
            $imagePath = public_path('images/logo.png');
            $this->Image($imagePath, $this->widthPage - $img_width, 0, $img_width, $img_height, 'PNG');

            $this->SetFontSize(size: 8);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('helvetica');
            $this->Cell($x, 1, 'CONTROL DE PLAGAS', 0, 1, 'L');

            $y = $this->GetY();
            $this->SetFont('helveticaB');
            $this->SetXY($x, $y);
            $this->Cell($x, 1, 'Fecha de ejecución: ' . date('d-m-Y', strtotime($order->programmed_date)), 0, 1, 'L');

            $this->SetFont('helvetica');
            $middleX = ($this->widthPage / 2) + ($this->margin * 2);
            $y = $this->GetY() - $step / 4;

            $this->SetXY($middleX, $y);
            $this->Cell(0, 1, $order->customer->branch->fiscal_name, 0, 1, 'C');
            $y = $this->GetY();

            $this->SetXY($middleX, $y);
            $this->Cell(0, 1, $order->customer->branch->address, 0, 1, 'C');
            $y = $this->GetY();

            $this->SetXY($middleX, $y);
            $this->Cell(0, 1, $order->customer->branch->colony . ' #' . $order->customer->branch->zip_code . ', ' . $order->customer->branch->state . ', ' . $order->customer->branch->country, 0, 1, 'C');
            $y = $this->GetY();

            $this->SetXY($middleX, $this->GetY());
            $this->Cell(0, 1, $order->customer->branch->email, 0, 1, 'C');

            $this->SetFillColor(255, 255, 255);
            $cont = 'Licencia Sanitaria nº : ' . $order->customer->branch->license_number;
            $nameTel = $order->customer->branch->name . ' Tel. ' . $order->customer->branch->phone . " ";
            $this->SetXY($middleX, $this->GetY());
            $this->MultiCell(0, 1, $cont . " " . $nameTel . " ", 0, 'C');

            $y = $this->GetY();
            $this->SetXY($x, $y);

            $this->startY = $y;

            //dd('Y: ' . $y . ' Max Y: ' . $this->getPageHeight());
        } else {
            throw new \Exception("Order not found.");
        }
    }

    public function Customer()
    {
        $states = json_decode(file_get_contents(public_path($this->states_route)), true);
        $order = Order::find($this->orderId);
        $step = 20;

        $x = $this->GetX();
        $y = $this->GetY();
        $width = ($this->widthPage / 2) - $this->margin;
        $middleX = ($this->widthPage / 2) + $this->margin;

        $this->SetFont('helvetica', '', 9);

        $this->SetXY($x, $y);
        $this->Ln();
        $this->SetFillColor(130, 178, 221);
        $this->Cell($width, 5, 'FECHA Y HORARIO', 0, 0, 'L', true);
        $this->Ln();
        $this->SetFontSize(8);
        $this->Cell($width, 5, 'Fecha de Comienzo: ' . Carbon::createFromFormat('Y-m-d', $order->programmed_date)->format('d/m/Y') . ' - ' . Carbon::createFromFormat('H:i:s', $order->start_time ?? '00:00:00')->format('H:i'), 0, 0, 'L');
        $this->Ln(4);
        $this->Cell($width, 5, 'Fecha de Finalizacion: ' . ($order->completed_date ? Carbon::createFromFormat('Y-m-d', $order->completed_date)->format('d/m/Y') : '') . ' ' . Carbon::createFromFormat('H:i:s', $order->end_time ?? '00:00:00')->format('H:i'), 0, 0, 'L');

        $this->Ln(6);
        $y = $this->GetY();
        $this->SetFont('helvetica', '', 9);
        $this->SetFillColor(130, 178, 221);

        $this->SetXY($x, y: $y);
        $this->Cell($width, 5, 'DATOS DEL CLIENTE Y SU SEDE', 0, 0, 'L', true);

        $this->SetXY($middleX, y: $y);
        $this->Cell($width, 5, 'PRESTA EL SERVICIO', 0, 0, 'L', true);
        $this->Ln();

        $this->SetFontSize(8);

        $y = $this->GetY();
        $this->SetXY($x, y: $y);
        $this->Cell($width, 5, 'Razón Social: ' . $order->customer->tax_name, 0, 0, 'L');

        $this->SetXY($middleX, y: $y);
        $this->Cell($width, 5, $order->customer->branch->fiscal_name, 0, 0, 'L');
        $this->Ln(4);

        $y = $this->GetY();
        $this->SetXY($x, y: $y);
        $this->Cell($width, 5, 'Sede: ' . $order->customer->name, 0, 0, 'L');

        $this->SetXY($middleX, y: $y);
        $this->Cell($width, 5, $order->customer->branch->address, 0, 0, 'L');
        $this->Ln(4);

        $y = $this->GetY();
        $this->SetXY($x, y: $y);
        $this->Cell($width, 5, 'Dirección: ' . $order->customer->address, 0, 0, 'L');

        $this->SetXY($middleX, y: $y);
        $this->Cell($width, 5, $order->customer->branch->colony . ' ' . $order->customer->zip_code . ' ' . $order->customer->branch->city . ' ' . $this->getNameByKey($order->customer->state, $states), 0, 0, 'L');
        $this->Ln(4);

        $y = $this->GetY();
        $this->SetXY($x, y: $y);
        $this->Cell($width, 5, 'Municipio: ' . $order->customer->city, 0, 0, 'L');

        $this->SetXY($middleX, $y);
        $this->SetFont('helveticaB');
        $this->Cell($width, 5, 'Licencia Sanitaria ROESB con nº ' . $order->customer->branch->license_number, 0, 0, 'L');
        $this->SetFont('helvetica');
        $this->Ln(4);

        $this->Cell($width, 5, 'Estado/Entidad: ' . $this->getNameByKey($order->customer->state, $states), 0, 0, 'L');
        $this->Ln(4);
        $this->Cell($width, 5, 'Tel: ' . $order->customer->phone, 0, 0, 'L');

        $this->Ln(6);
        $y = $this->GetY();
        $this->SetXY($x, $y);
    }

    public function Services()
    {
        $order = Order::find($this->orderId);
        $step = 10;
        $x = $this->GetX();
        $y = $this->GetY();
        $width = $this->widthPage - $this->margin;
        $this->SetFont('helvetica', '', 9);

        $this->SetXY($x, y: $y);
        $this->Cell($width, 5, 'TRATAMIENTOS', 0, 0, 'L', true);
        $this->Ln();

        if ($order->services->isNotEmpty()) {
            foreach ($order->services as $service) {
                if ($service->prefix == 2) {
                    $service_details = ServiceDetails::where('service_id', $service->id);
                    if ($order->contract_id) {
                        $service_details = $service_details->where('contract_id', $order->contract_id);
                    }
                    $service_details = $service_details->first();

                    $this->Ln(2);
                    $newY = $this->GetY();
                    $this->SetXY($x + $step, $newY);
                    $this->SetFillColor(130, 178, 221);
                    $this->Rect($x, $newY, 4, 4, 'F');

                    $this->setTextColor(33, 97, 140);
                    $this->SetFont('helveticaB', '', 10);
                    $this->Cell(($width - $this->margin) / 2, 4, $service->name, 0, 0, 'L');
                    $this->setTextColor(0, 0, 0);
                    $this->Ln(6);

                    $newY = $this->GetY();
                    $this->SetXY($x, $newY);
                    $this->SetFont('helvetica', '', 9);
                    $this->writeHTML($service_details->details ?? 'S/A', true, false, true, false, '');

                    $newY = $this->GetY();
                    $this->SetXY($x, $newY);
                } else {
                    $newY = $this->GetY();
                    $this->SetXY($x, $newY);
                    $this->SetFont('helveticaB', '', 8);
                    $this->Cell(($width - $this->margin) / 2, 4, 'Sin tratamientos', 0, 0, 'L');
                }
            }
        }

        $this->Ln(6);
        $y = $this->GetY();
        $this->SetXY($x, y: $y);
    }

    public function Products()
    {
        $headers = ['Materia activa', 'No Registro', 'Plazo seguridad', 'Método de aplicación', 'Dosificación', 'Consumo', 'Lote'];
        $order_products = OrderProduct::where('order_id', $this->orderId)->get();
        $step = 10;
        $startX = $this->GetX();
        $y = $this->GetY();
        $width = $this->widthPage - $this->margin;
        $width_td = $width / count($headers);

        $this->SetFont('helvetica', '', 9);

        $this->SetXY($startX, y: $y);
        $this->Cell($width, 5, 'PRODUCTOS UTILIZADOS', 0, 0, 'L', true);
        $this->Ln();

        $this->SetFontSize(8);
        $y = $this->GetY();
        $this->SetY($y);

        if ($order_products->isNotEmpty()) {
            $this->SetFillColor(202, 207, 210);
            $this->Ln(2);
            foreach ($headers as $header) {
                $x = $this->GetX() + $width_td;
                $y = $this->GetY();
                $this->MultiCell($width_td, 8, $header, 0, 'L', true);
                $this->SetXY($x, $y);
            }

            $y = $this->GetY() + $step;
            $this->SetXY($startX, y: $y);
            $this->SetFillColor(242, 243, 244);
            foreach ($order_products as $order_product) {
                // Guardar la posición inicial
                $x = $this->GetX();
                $y = $this->GetY();
                $dosage = $order_product->dosage ? $order_product->dosage : $order_product->product->dosage;
                $metric = $order_product->metric ? $order_product->metric : $order_product->product->metric;

                // Imprimir los datos con MultiCell (esto ajustará el texto a múltiples líneas si es necesario)
                $this->MultiCell($width_td, 6, $order_product->product->active_ingredient ?? 'N/A', 0, 'L', true);
                $this->SetXY($x + $width_td, $y); // Mover el cursor a la derecha para la siguiente celda

                $x = $this->GetX(); // Actualizar la posición X después de mover
                $this->MultiCell($width_td, 6, $order_product->product->register_number ?? 'N/A', 0, 'L', true);
                $this->SetXY($x + $width_td, $y);

                $x = $this->GetX();
                $this->MultiCell($width_td, 6, $order_product->product->safety_period ?? 'N/A', 0, 'L', true);
                $this->SetXY($x + $width_td, $y);

                $x = $this->GetX();
                $this->MultiCell($width_td, 6, $order_product->appMethod->name ?? 'N/A', 0, 'L', true);
                $this->SetXY($x + $width_td, $y);

                $x = $this->GetX();
                $this->MultiCell($width_td, 6, $dosage ?? 'N/A', 0, 'L', true);
                $this->SetXY($x + $width_td, $y);

                $x = $this->GetX();
                $this->MultiCell($width_td, 6, ($order_product->amount ?? 'N/A') . ' ' . ($metric->value ?? ''), 0, 'L', true);
                $this->SetXY($x + $width_td, $y);

                $x = $this->GetX();
                $this->MultiCell($width_td, 6, $order_product->lot->registration_number ?? 'N/A', 0, 'L', true);

                // Saltar a la siguiente fila
                $this->Ln(2);
            }
            $this->Ln(2);
        } else {
            $y = $this->GetY();
            $this->SetXY($startX, $y);
            $this->SetFont('helveticaB', '', 8);
            $this->Cell(($width - $this->margin) / 2, 4, 'Sin productos', 0, 0, 'L');
            $this->Ln(6);
        }
        $y = $this->GetY();
        $this->SetXY($startX, y: $y);
    }


    public function Devices()
    {
        $headers = ['Zona', 'Código', 'Producto y consumo', 'Valor revisión'];
        $order = Order::find($this->orderId);
        $services = $order->services;
        $floorplans = FloorPlans::where('customer_id', $order->customer->id)->whereIn('service_id', $services->pluck('id')->toArray())->get();
        $step = 8;
        $startX = $this->GetX();
        $startY = $this->GetY();
        $width = $this->widthPage - $this->margin;

        $this->SetFont('helvetica', '', 9);
        $this->SetXY($startX, y: $startY);
        $this->SetFillColor(130, 178, 221);
        $this->Cell($width, 5, 'REVISION DE DISPOSITIVOS DE CONTROL', 0, 0, 'L', true);
        $this->Ln();

        if ($floorplans->isNotEmpty()) {
            $y = $this->GetY();
            $this->SetFont('helveticaB', '', 8);
            $this->SetY($y);
            $this->Cell($width, 5, 'Sede: ' . $order->customer->name, 0, 0, 'L');
            $this->Ln();

            $this->SetFont('helvetica');
            foreach ($floorplans as $floorplan) {
                $version = $floorplan->version($order->programmed_date);
                if (!$version) {
                    return;
                }
                $devices = $floorplan->devices($version)->get();

                $y = $this->GetY();
                $this->SetY($y);
                $this->Cell($width, 5, 'Plano: ' . $floorplan->filename, 0, 0, 'L');
                $this->Ln();

                $control_types = array_unique($devices->pluck('type_control_point_id')->toArray());
                $points = ControlPoint::whereIn('id', $control_types)->get();

                foreach ($points as $point) {
                    $questions = $point->questions()->get();
                    $new_headers = array_merge($headers, $questions->pluck('question')->toArray());
                    $selected_devices = $devices->filter(fn($device) => $device->type_control_point_id == $point->id);

                    $y = $this->GetY() + $step / 4;
                    $this->SetFont('helveticaB');
                    $this->SetY($y);
                    $this->Cell($width, 5, 'Punto de control: ' . $point->name, 0, 0, 'L');
                    $this->Ln();
                    $this->SetFont('helvetica');

                    $width_td = $width / count($new_headers);
                    $y = $this->GetY() + $step / 4;

                    $this->SetY($y);
                    $this->SetFillColor(202, 207, 210);

                    $x = $startX;
                    foreach ($new_headers as $header) {
                        $this->MultiCell($width_td, $step, $header, 0, 'C', true);
                        $x = $x + $width_td;
                        $this->SetXY($x, $y);
                    }

                    $this->Ln(2);

                    $y = $this->GetY() + $step;
                    $this->SetXY($startX, y: $y);

                    $this->SetFillColor(242, 243, 244);

                    foreach ($selected_devices as $device) {
                        // Guardamos la posición Y actual
                        $reviews = [];
                        $product_name = '';
                        $pest_reviews = DevicePest::where('device_id', $device->id)->where('order_id', $this->orderId)->get();
                        foreach ($pest_reviews as $pest_review) {
                            $reviews[] = "({$pest_review->total}) {$pest_review->pest->name}";
                        }

                        $device_state = $device->states($this->orderId);
                        $product_name = ($device_state && $device_state->is_product_changed && $device->product->name)
                            ? $device->product->name . ' (1 uds)'
                            : null;

                        if ($y > $this->heightPage - $step) {
                            $this->AddPage();
                            $y = $this->GetY();
                        }

                        $this->SetY($y);
                        $this->MultiCell($width_td, $step, $device->applicationArea->name, 0, 'C', true);
                        $newX = $this->GetX() + $width_td;

                        $this->SetXY($newX, $y);
                        $this->MultiCell($width_td, $step, $device->nplan, 0, 'C', true);
                        $newX = $newX + $width_td;

                        $this->SetXY($newX, $y);
                        $this->setFontSize($product_name ? 6 : 8);
                        $product_name = !$product_name ? 'N/A' : $product_name;
                        $this->MultiCell($width_td, $step, $product_name, 0, 'C', true);
                        $this->setFontSize(8);
                        $newX = $newX + $width_td;

                        $this->SetXY($newX, $y);
                        $this->setFontSize(!empty($reviews) ? 6 : 8);
                        $this->MultiCell($width_td, $step, !empty($reviews) ? implode(', ', $reviews) : 'N/A', 0, 'C', true);
                        $this->setFontSize(8);
                        $newX = $newX + $width_td;

                        foreach ($questions as $question) {
                            $incident = OrderIncidents::where('order_id', $this->orderId)
                                ->where('device_id', $device->id)
                                ->where('question_id', $question->id)
                                ->first();

                            $this->SetXY($newX, $y); // Posición X para las respuestas
                            $this->MultiCell($width_td, $step, $incident->answer ?? 'N/A', 0, 'C', true);

                            $newX = $newX + $width_td;
                        }

                        $this->Ln(2);
                        $y = $this->GetY();
                        $this->SetXY($x, $y);
                    }
                }
            }
            $this->SetXY($startX, y: $y);
        } else {
            $y = $this->GetY();
            $this->SetFont('helveticaB');
            $this->SetXY($startX, y: $y);
            $this->Cell($width, 5, 'Sin revisiones de dispositivos', 0, 0, 'L');
            $this->Ln();
        }
        $this->Ln(2);
    }

    public function Recommendations()
    {
        $order = Order::find($this->orderId);
        $recommendations = $order->reportRecommendations;
        $recommendations = Recommendations::whereIn('id', $recommendations->pluck('recommendation_id'))->get();
        $step = 5;
        $x = $this->GetX();
        $y = $this->GetY();
        $width = $this->widthPage - $this->margin;

        $this->SetFont('helvetica', '', 9);
        $this->SetFillColor(130, 178, 221);
        $this->SetXY($x, y: $y);
        $this->Cell($width, 5, 'RECOMENDACIONES', 0, 0, 'L', true);
        $this->Ln(6);
        $this->setFontSize(8);

        foreach ($recommendations as $recommendation) {
            $y = $this->GetY();
            $this->SetXY($x, $y);
            $this->MultiCell(
                $width,
                5,
                $recommendation->description,
                0,
                'L',
                false
            );
            $this->Ln(1);
        }
    }

    public function Signature()
    {
        $order = Order::find($this->orderId);
        $width = ($this->widthPage - $this->margin) / 2;
        $step = 10;

        if ($this->GetY() + $step * 4 > $this->heightPage) {
            $this->AddPage();
        }

        $x = $this->GetX();
        $y = $this->GetY() + ($step * 1.5);

        $this->SetXY($x, $y);

        $img_width = 60;
        $img_height = 25;
        $imagePath = public_path('images/logo.png');

        $signtaure = $order->customer_signature;

        if (empty($signtaure)) {
            $found_orders = Order::where('customer_id', $order->customer_id)->where('customer_signature', '!=', null)->get();
            $signtaure = $found_orders->first()->customer_signature ?? '';
        }

        $path = $this->getSignature($signtaure);
        $imgX = $x + ($img_width / 3);
        $this->Image($path, $imgX, $y - $step, $img_width, $img_height, 'PNG');

        $imgX = $this->GetX() + $img_width;
        $this->SetXY($imgX, $y);

        /*$path = Storage::disk('public')->path('users/signatures/firma_0_Jacobo.png');
        $this->SetXY(x: $imgX, y: $y);
        $this->Image($path, $imgX, $y - $step, $img_width, $img_height, 'PNG');*/

        $technician = $order->technicians()->first();
        $user_id = $technician->user_id;
        $userfile = UserFile::where('user_id', $user_id)->where('filename_id', 15)->first();
        $path = !empty($userfile->path) ? Storage::disk('public')->path($userfile->path) : '';

        $imgX = $this->GetX() + ($width / 2);
        $this->Image($path, $imgX, $y - $step, $img_width, $img_height, 'PNG');

        $y = $this->GetY() + $step * 2;
        $this->SetXY($x, $y);
        $this->Cell($width, 5, 'Recibí del cliente: ' .  $order->signature_name, 0, 0, 'C');
        //$this->Cell($width, 5, 'Nombre y firma del responsable técnico', 0, 0, 'C');
        $this->Cell($width, 5, 'Nombre y firma del técnico aplicador', 0, 0, 'C');

        $y = $this->GetY() + $step / 2;
        $this->SetXY($x, $y);
        $this->Cell($width, 5, $order->customer->name, 0, 0, 'C');
        //$this->Cell($width, 5, 'JACOBO SAMUEL QUINTERO CURIEL', 0, 0, 'C');
        $this->Cell($width, 5, $technician->user->name, 0, 0, 'C');

        $y = $this->GetY() + $step / 2;
        $this->SetXY($x, $y);
        $this->Cell($width, 5, 'RFC: ' . $order->customer->rfc, 0, 0, 'C');
        //$this->Cell($width, 5, 'RFC: ' . 'QUCJ770110PP4', 0, 0, 'C');
        $this->Cell($width, 5, 'RFC: ' . $technician->rfc, 0, 0, 'C');

    }

    public function Footer()
    {
        $this->SetY($this->margin * -1);
        $this->SetAutoPageBreak(true, 15);
        $this->SetFontSize(8);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 1, 'SISCOPLAGAS', 0, false, 'C');
        $this->Cell(0, 1, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, false, 'R');
    }

    public function agregarContenido()
    {
        $this->SetY(45);
    }
}
