<?php



namespace App\Http\Controllers;

use App\Models\Administrative;
use App\Models\ApplicationArea;
use App\Models\ApplicationMethodService;
use App\Models\Contract;
use App\Models\ControlPointQuestion;
use App\Models\ControlPoint;
use App\Models\Customer;
use App\Models\Device;
use App\Models\DevicePest;
use App\Models\DeviceStates;
use App\Models\FloorPlans;
use App\Models\Order;
use App\Models\OrderIncidents;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\OrderStatus;
use App\Models\OrderTechnician;
use App\Models\OrderPest;
use App\Models\Question;
use App\Models\Lot;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AppController extends Controller
{
	private function removeDuplicatesById($arr)
	{
		$uniqueArr = [];
		foreach ($arr as $data) {
			if (!isset($uniqueArr[$data['id']])) {
				$uniqueArr[$data['id']] = $data;
			}
		}
		return array_values($uniqueArr);
	}

	private function filterDevices($devices)
	{
		$data = [];

		$newDevices = $this->removeDuplicatesById($devices);

		foreach ($newDevices as $newDevice) {

			$questionIds = ControlPointQuestion::where('control_point_id', $newDevice['type_control_point_id'])->get()->pluck('question_id');
			$questions = Question::whereIn('id', $questionIds)->select(['id', 'question', 'question_option_id'])->get();

			$data[] = [
				'id' => $newDevice['id'],
				'floorplanID' => $newDevice['floorplan_id'],
				'name' => ControlPoint::find($newDevice['type_control_point_id'])->name,
				'number' => $newDevice['nplan'],
				'area' => ApplicationArea::find($newDevice['application_area_id'])->name,
				'version' => $newDevice['version'],
				'is_scanned' => $newDevice['is_scanned'],
				'updated_at' => $newDevice['updated_at'],
				'questions' => $questions,
			];
		}

		return $data;
	}

	function getCsrfToken()
	{
		return response()->json(Session::token());
	}

	public function authentication(Request $request)
	{
		$data = $request->all();
		$user = User::where('email', $data['email'])->first();

		if ($user && $user->status_id == 2 && Hash::check($data['password'], $user->password)) {
			return response()->json(['userID' => $user->id, 'success' => true]);
		} else {
			return response()->json(['success' => false]);
		}
	}

	public function getOrders(int $id, string $date)
	{
		$devices = $questions = $floorplan = $pests = $products = $data = $orderData = $serviceData = $productData = $pestData = $appMethodData = [];
		$version = 0;
		$hasDevices = false;

		$user = User::find($id);

		if ($user) {
			// Verifica si el usuario es técnico (rol = 3 para técnicos)
			if ($user->role_id == 3) {
				$tech = Technician::where('user_id', $user->id)->first();
				$orderIds = OrderTechnician::where('technician_id', $tech->id)->pluck('order_id');
				// Calcula la fecha actual y la fecha después de 3 días
				$currentDate = date($date);
				$threeDaysAfter = date('Y-m-d', strtotime($currentDate . ' +3 days'));
				// Filtra las órdenes por fecha programada dentro del rango deseado
				$orders = Order::whereIn('id', $orderIds)
					->where('programmed_date', '>=', $currentDate)
					->where('programmed_date', '<=', $threeDaysAfter)
					->get();
			} else {
				// Calcula la fecha actual y la fecha después de 3 días
				$currentDate = date($date);
				$threeDaysAfter = date('Y-m-d', strtotime($currentDate . ' +3 days'));

				// Filtra las órdenes por fecha programada dentro del rango deseado
				$orders = Order::where('programmed_date', '>=', $currentDate)
					->where('programmed_date', '<=', $threeDaysAfter)
					->get();
			}
		}

		// Ajustes para un nuevo JSON
		foreach ($orders as $order) {
			$hasDevices = false;
			$products = [];
			$pests = [];

			$customer = $order->customer()->select('id', 'name', 'phone', 'email', 'address', 'zip_code', 'city', 'state', 'map_location_url')->first();
			$services = $order->services()->get();

			// Obtener los productos para cada servicio
			foreach ($services as $service) {
				$products = $service->products(0)->select('id', 'name', 'description', 'execution_indications', 'metric', 'updated_at')->get();
				$productData = array_merge($productData, $products->toArray());

				$pests = $service->pests()->get(['pest_catalog.id', 'pest_catalog.name', 'pest_catalog.updated_at']);
				$pestData = array_merge($pestData, $pests->toArray());

				$application_methods = $service->appMethods()->get(['application_method.id', 'application_method.name', 'application_method.updated_at']);
				$appMethodData = array_merge($appMethodData, $application_methods->toArray());

				if ($order->customer->service_type_id != 1 && $service->prefix == 1) {
					$floorplan = FloorPlans::where('service_id', $service->id)->where('customer_id', $customer->id)->first();
					if ($floorplan) {
						$version = $floorplan->version($order->programmed_date);
						if ($version) {
							$devices = Device::where('floorplan_id', $floorplan->id)->where('version', $version)->get();
							$hasDevices = !empty($devices);
						}
					}
				}

				$serviceData[] = [
					'id' => $service->id,
					'prefix' => $service->prefix,
					'name' => $service->name,
					'type' => $service->service_type_id,
					'cost' => $service->cost,
					'updated_at' => $service->updated_at,
					"productsID" => !empty($products) ? $products->pluck('id') : [],
					"pestsID" => !empty($pests) ? $pests->pluck('id') : [],
					"application_method_id" => !empty($products) ? $application_methods->pluck("id") : [],
					"devicesID" => !empty($devices) ? $devices->pluck('id') : [],
				];
			}

			$orderData[] = [
				'id' => $order->id,
				"statusID" => $order->status_id,
				"contractID" => $order->contract_id,
				"start_time" => $order->start_time,
				"end_time" => $order->end_time,
				"programmed_date" => $order->programmed_date,
				"execution" => $order->execution,
				"areas" => $order->areas,
				"additional_comments" => $order->additional_comments,
				"price" => $order->price,
				"type" => $order->customer->service_type_id,
				"hasDevices" => $hasDevices,
				"updated_at" => $order->updated_at,
				'customer' => $customer,
				"servicesID" => !empty($services) ? $services->pluck('id') : [],
			];
		}

		$data = [
			'orders' => $orderData,
			'services' => $this->removeDuplicatesById($serviceData),
			'products' => $this->removeDuplicatesById($productData),
			'pests' => $this->removeDuplicatesById($pestData),
			'applicationMethods' => $this->removeDuplicatesById($appMethodData),
			'devices' => $this->filterDevices($devices),
		];

		return response()->json($data);
	}

	public function getUsers()
	{
		$data = User::where('role_id', 3)->get();
		return response()->json($data);
	}

	public function updateOrderStatus(Request $request)
	{
		$data = $request->all();
		$order = Order::find($data['order_id']);

		if ($order) {
			$order->status_id = $data['status_id'];
			$order->save();
			$statusCode = 200;
		} else {
			$statusCode = 500;
		}

		return response()->json(['status' => $statusCode]);
	}

	public function setChemicalApplications(Request $request)
	{
		$response_code = 200;
		try {
			$data = $request->all();
			if ($data) {
				foreach ($data as $d) {
					$order = Order::find($d['orderID']);
					$order->recommendations = $d['recs'];
					$order->technical_observations = $d['tech_obs'];
					$order->comments = $d['comments'];
					$order->customer_signature = $d['signature'];
					$order->signature_name = $d['signatureName'];
					$order->end_time = $d['end_time'];
					$order->completed_date = $d['completed_date'];
					$order->status_id = 3;
					$user_id = $d['user_id'];
					$order->save();

					$pests = $d['pests'];
					$resources = $d['resources'];

					foreach ($pests as $pest) {
						$service_id = $pest['key'];
						$selected_pests = $pest['values'];

						foreach ($selected_pests as $selected_pest) {
							OrderPest::updateOrInsert(
								[
									'order_id' => $order->id,
									'service_id' => $service_id,
									'pest_id' => $selected_pest['id']
								],
								[
									'total' => $selected_pest['value'],
									'updated_at' => now(),
								]
							);
						}
					}

					foreach ($resources as $resource) {
						$service_id = $resource['service_id'];
						$products = $resource['array_ids'];

						foreach ($products as $product) {
							OrderProduct::updateOrInsert(
								[
									'order_id' => $order->id,
									'service_id' => $service_id,
									'product_id' => $product['product_id'],
									'application_method_id' => $product['appMethod_id'],
								],
								[
									'amount' => $product['value'], // Se actualiza o inserta el valor
									'updated_at' => now(),
								]
							);
						}
					}

					$technician = Technician::where('user_id', $user_id)->first();
					if($technician) {
						$user_id = $technician->id;
						$fetched_orders = OrderTechnician::where('order_id', $order->id)->where('technician_id', $user_id)->get();
						if($fetched_orders) {
							OrderTechnician::whereNotIn('id', $fetched_orders->pluck('id'))->where('order_id', $order->id)->delete();
						} else {
							OrderTechnician::create([
								'technician_id'=> $user_id,
								'order_id'=> $order->id,
								'updated_at' => now(),
							]);
						}
					} 
				}
			} else {
				$response_code = 204;
			}
		} catch (\Exception $e) {
			$response_code = 500;
		}

		return response($response_code);
	}

	public function setDevices(Request $request)
	{
		$response_code = 200;
		try {
			$data = $request->all();
			if ($data) {
				foreach ($data as $d) {
					$products_count = [];

					$order = Order::find($d['orderID']);
					$order->recommendations = $d['recs'];
					$order->technical_observations = $d['tech_obs'];
					$order->comments = $d['comments'];
					$order->customer_signature = $d['signature'] ?? $order->customer_signature;
					$order->signature_name = $d['signatureName'] ?? $order->signature_name;
					$order->end_time = $d['end_time'];
					$order->completed_date = $d['completed_date'];
					$order->status_id = 3;
					$order->save();

					$incidents = $d['incidents'];
					foreach ($incidents as $incident) {
						$device = Device::find($incident['device_id']);
						$is_product_changed = $incident['product_change'];
						$is_scanned = $incident['is_scanned'];
						$questions = $incident['questions'];
						$pests = $incident['pests'];
						$observs = $incident['observs'];

						foreach ($questions as $question) {
							OrderIncidents::updateOrInsert(
								[
								'order_id' => $order->id,
								'question_id' => $question['id'],
								'device_id' => $device->id,
							], [
								'answer' => $question['value'],
								'updated_at' => now(),
							]);
						}

						foreach ($pests as $pest) {
							DevicePest::updateOrInsert(
								[
									'order_id' => $order->id,
									'device_id' => $device->id,
									'pest_id' => $pest['id'],
								],
								[
									'total' => $pest['value'],
									'created_at' => now(),
									'updated_at' => now(),
								]
							);
						}

						DeviceStates::updateOrInsert(
							[
								'order_id' => $order->id,
								'device_id' => $device->id
							],
							[
								'observations' => $observs,
								'is_scanned' => $is_scanned,
								'is_product_changed' => $is_product_changed,
								'updated_at' => now(),
							]
						);

						if ($is_product_changed) {
							$device_product = $device->product;
						
							if (empty($products_count)) {
								$products_count[] = [
									'product_id' => $device_product->id,
									'count' => 1
								];
							} else {
								$found_product_key = array_search($device_product->id, array_column($products_count, 'product_id'));
						
								if ($found_product_key === false) {
									$products_count[] = [
										'product_id' => $device_product->id,
										'count' => 1
									];
								} else {
									$products_count[$found_product_key]['count']++;
								}
							}
						}						
					}

					if(!empty($products_count)) {
						foreach ($products_count as $value) {
							$lot = Lot::where('product_id', $value['product_id'])->where('amount', '>' , 0)->first();
							$service = $order->services()->first();
							OrderProduct::updateOrInsert(
								[
									'order_id' => $order->id,
									'service_id' => $service->id,
									'product_id' => $value['product_id']
								],
								[
									'amount' => $value['count'],
									'lot' => $lot->id,
									'metric' => 'Unidades (Uds)',
								]
							);							
						}
					}
				}

				
			} else {
				$response_code = 204;
			}
		} catch (\Exception $e) {
			$response_code = 500;
		}

		return response($response_code);
	}
}
