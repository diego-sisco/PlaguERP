<?php

namespace App\Http\Controllers;

use App\Models\DevicePest;
use App\Models\DeviceStates;
use App\Models\FloorPlans;
use App\Models\Order;
use App\Models\OrderIncidents;
use App\Models\Device;
use App\Models\OrderProduct;
use App\Models\OrderRecommendation;
use App\Models\PestCatalog;
use App\Models\ProductCatalog;
use App\Models\ServiceDetails;
use App\Models\Service;

use App\Models\Lot;
use App\PDF\MyPDF;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;



class ReportController extends Controller
{

    private $file_answers_path = 'datas/json/answers.json';

    private function getOptions($id, $answers)
    {
        foreach ($answers as $answer) {
            if ($answer['id'] == $id) {
                return $answer['options'];
            }
        }
        return [];
    }

    public function autoreview(int $orderId, int $floorplanId)
    {
        $answers = json_decode(file_get_contents(public_path($this->file_answers_path)), true);
        $order = Order::find($orderId);
        $floorplan = FloorPlans::find($floorplanId);
        $version = $floorplan->version($order->programmed_date);
        $devices = $floorplan->devices($version)->get();

        $incidents = OrderIncidents::where('order_id', $order->id)
            ->whereIn('device_id', $devices->pluck('id')->toArray())->get();

        $check_devices = array_diff(
            $devices->pluck('id')->toArray(),
            $incidents->pluck('device_id')->toArray()
        );

        $devices = Device::whereIn('id', $check_devices)->get();

        foreach ($devices as $device) {
            $questions = $device->questions()->get();
            foreach ($questions as $question) {
                $options = $this->getOptions($question->question_option_id, $answers);

                OrderIncidents::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'device_id' => $device->id,
                        'question_id' => $question->id
                    ],
                    [
                        'answer' => $options[0] ?? null,
                        'updated_at' => now()
                    ]
                );
            }

            if (!$device->is_product_changed) {
                $device->update([
                    'is_product_changed' => true
                ]);
            }
        }

        return back();

        //dd($answers[0]['options'][0]);
    }

    public function storeIncidents(Request $request, string $orderId): JsonResponse
    {
        $incidents = json_decode($request->input('incidents'));
        $pests_found = json_decode($request->input('pests_found'));
        $is_product_changed = json_decode($request->input('is_product_changed'));
        $is_device_changed = json_decode($request->input('is_device_changed'));
        $lotId = json_decode($request->input('lot_id'));
        $serviceId = json_decode($request->input('service_id'));
        $amount = json_decode($request->input('amount'));
        $deviceId = json_decode($request->input('deviceId'));
        $reviews = [];

        try {
            $order = Order::find($orderId);
            $service = Service::find($serviceId);
            $device = Device::find($deviceId);

            foreach ($incidents as $incident) {
                OrderIncidents::updateOrCreate(
                    [
                        'order_id' => $orderId,
                        'device_id' => $incident->device_id,
                        'question_id' => $incident->question_id
                    ],
                    [
                        'answer' => $incident->value,
                        'updated_at' => now()
                    ]
                );
            }

            $pest_ids = [];
            $pests = $pests_found->pests;

            $fetched_device = DeviceStates::where('device_id', $device->id)->where('order_id', $orderId)->first();
            if ($fetched_device) {
                $fetched_device->is_product_changed = $is_product_changed;
                $fetched_device->is_device_changed = $is_device_changed;
                $fetched_device->save();
            } else {
                DeviceStates::create([
                    'order_id' => $orderId,
                    'device_id' => $device->id,
                    'is_product_changed' => $is_product_changed,
                    'is_device_changed' => $is_device_changed,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            if (count($pests) > 0) {
                foreach ($pests as $pest) {
                    $pest_ids[] = $pest->id;
                }

                DevicePest::where('order_id', $orderId)
                    ->where('device_id', $device->id)
                    ->whereNotIn('pest_id', $pest_ids)
                    ->delete();

                foreach ($pests as $pest) {
                    $fetched_pest = PestCatalog::find($pest->id);
                    DevicePest::updateOrCreate([
                        'order_id' => $orderId,
                        'device_id' => $device->id,
                        'pest_id' => $fetched_pest->id,
                    ], [
                        'total' => $pest->value,
                        'updated_at' => now()
                    ]);

                    $reviews[] = "({$pest->value}) {$fetched_pest->name}";
                }
            }

            $product = ProductCatalog::find($device->product_id);
            $order_product = OrderProduct::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->where('service_id', $service->id)->first();
            $appMethod = $product->applicationMethods()->first();

            if (!$order_product) {
                $order_product = OrderProduct::create(
                    [
                        'order_id' => $orderId,
                        'service_id' => $service->id,
                        'product_id' => $product->id,
                        'application_method_id' => $appMethod->id,
                        'lot_id' => $lotId,
                    ]
                );
            }
            $order_product->amount = DeviceStates::where('order_id', $order->id)->where('is_product_changed', true)->count();
            $order_product->save();


            $product = ProductCatalog::find($device->controlPoint->product->id);
            $order_product = OrderProduct::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->where('service_id', $service->id)->first();
            $appMethod = $product->applicationMethods()->first();
            if (!$order_product) {
                $order_product = OrderProduct::create(
                    [
                        'order_id' => $orderId,
                        'service_id' => $service->id,
                        'product_id' => $product->id,
                        'application_method_id' => $appMethod->id,
                        'lot_id' => $lotId,
                        'amount' => $amount,
                    ]
                );
            }

            $order_product->amount = DeviceStates::where('order_id', $order->id)->where('is_device_changed', true)->count();
            $order_product->save();   // Guarda los cambios

            return response()->json([
                'success' => true,
                'message' => 'Incidencias guardadas correctamente.',
                'reviews' => $reviews,
                'is_changed' => $order_product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar las incidencias: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request, string $orderId)
    {
        //dd($request->all());
        $service_details = json_decode($request->input('service_details'));
        $selected_recommendations = json_decode($request->input('recommendations'));
        $order = Order::find($orderId);

        if (!$order) {
            return back()->withErrors(['error' => 'Order not found.']);
        }

        $order->technical_observations = $request->input('observations');
        $order->comments = $request->input('additional_comments');
        $order->save();

        $recommendations = OrderRecommendation::where('order_id', $order->id)->get()->pluck('recommendation_id')->toArray();
        $delete_recommendations = array_diff($recommendations, $selected_recommendations);
        $insert_recommendations = array_diff($selected_recommendations, $recommendations);
        OrderRecommendation::where('order_id', $order->id)->whereIn('recommendation_id', $delete_recommendations)->delete();
        foreach ($insert_recommendations as $recomendationId) {
            OrderRecommendation::insert([
                'order_id' => $order->id,
                'recommendation_id' => $recomendationId,
            ]);
        }

        if (!empty($service_details)) {
            foreach ($service_details as $data) {
                $service_id = $data->id;
                $service_details = $data->details;

                ServiceDetails::updateOrCreate(
                    [
                        'service_id' => $service_id,
                        'contract_id' => $order->contract_id != 0 ? $order->contract_id : null,
                    ],
                    [
                        'details' => $service_details,
                    ]
                );
            }
        }

        return redirect()->route('report.print', ['orderId' => $orderId]);
    }

    public function searchProduct(Request $request)
    {
        try {
            $search = $request->input('search');
            if (empty($search)) {
                return response()->json(['error' => 'Search term is required.'], 400);
            }

            $searchTerm = '%' . $search . '%';

            $products = ProductCatalog::where('name', 'LIKE', $searchTerm)
                ->select('id', 'name', 'dosage')
                ->get();

            if ($products->isEmpty()) {
                return response()->json(['message' => 'No products found.'], 404);
            }

            $lots = Lot::whereIn('product_id', $products->pluck('id')->toArray())->where('amount', '>', 0)->get();

            $data = [
                'products' => $products,
                'lots' => $lots,
            ];

            return response()->json(['data' => $data], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while searching for products.', 'details' => $e->getMessage()], 500);
        }
    }

    public function setProduct(Request $request, string $orderId)
    {
        $data = $request->all();

        OrderProduct::updateOrCreate(
            [
                'order_id' => $orderId,
                'product_id' => $data['product_id'],
                'service_id' => $data['service_id'],
            ],
            [
                'application_method_id' => $data['application_method_id'],
                'lot_id' => $data['lot_id'],
                'amount' => $data['amount'],
                'dosage' => $data['dosage'],
            ]
        );

        return back();
    }

    public function destroyProduct(string $incident_id)
    {
        OrderProduct::find($incident_id)->delete();
        return back();
    }

    public function print(string $orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $pdf_name = 'Certificado_' . $order->id . '_' . $order->customer->name . '.pdf';
            $pdf = new MyPDF($order->id);
            $pdf->AddPage();
            $pdf->Customer();
            $pdf->Services();
            $pdf->Products();
            $pdf->Devices();
            $pdf->Recommendations();
            $pdf->Signature();
            $pdf->Output($pdf_name, 'I');
        }
    }
}

