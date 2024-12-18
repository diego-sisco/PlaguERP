<?php

namespace App\Http\Controllers;

use App\Models\Administrative;
use App\Models\ApplicationMethod;
use App\Models\ApplicationMethodService;
use App\Models\Customer;
use App\Models\Device;

use App\Models\DeviceStates;
use App\Models\Metric;
use App\Models\Order;

use App\Models\OrderService;
use App\Models\OrderStatus;
use App\Models\OrderTechnician;
use App\Models\PestCategory;

use App\Models\ProductCatalog;
use App\Models\Service;
use App\Models\Technician;
use App\Models\Contract;
use App\Models\Lot;
use App\Models\DatabaseLog;
use App\Models\User;
use App\Models\Recommendations;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class OrderController extends Controller
{

	private $files_path = 'files/customers';
	private $file_answers_path = 'datas/json/answers.json';
	private $size = 50;

	private function generateDate($date, $number, $frequency)
	{
		$newDate = Carbon::createFromFormat('Y-m-d', $date);
		switch ($frequency) {
			case 1: // Día(s)
				$newDate->addDays($number);
				break;
			case 2: // Semana(s)
				$newDate->addWeeks($number);
				break;
			case 3: // Mes(es)
				$newDate->addMonths($number);
				break;
			case 4: // Año(s)
				$newDate->addYears($number);
				break;
			default:
				// Si la frequency no es válida, devolver la date actual
				return $date;
		}

		return $newDate->format('Y-m-d');
	}

	public function index(): View
	{
		$orders = Order::orderBy('id', 'desc')->paginate($this->size);
		$order_status = OrderStatus::all();
		$size = $this->size;

		return view(
			'order.index',
			compact(
				'orders',
				'order_status',
				'size'
			)
		);
	}

	public function create(): View
	{
		$success = $warning = $error = null;
		$pest_categories = PestCategory::orderBy('category', 'asc')->get();
		$application_methods = ApplicationMethod::orderBy('name', 'asc')->get();
		$technicians = User::where('role_id', 3)->where('status_id', 2)->orderBy('name', 'asc')->get();
		$contracts = Contract::all();
		$order_status = OrderStatus::all();

		return view(
			'order.create',
			with(
				compact(
					'pest_categories',
					'application_methods',
					'technicians',
					'contracts',
					'order_status'
				)
			)
		);
	}

	public function store(Request $request)
	{
		$selected_services = json_decode($request->input('services'));
		$selected_technicians = json_decode($request->input('technicians'));

		if (!$request->input('customer_id')) {
			return back();
		}

		if (empty($selected_services)) {
			return back();
		}

		if (empty($selected_technicians)) {
			return back();
		}

		$order = new Order();
		$order->administrative_id = Administrative::where('user_id', auth()->user()->id)->first()->id;
		$order->customer_id = $request->input('customer_id');
		$order->start_time = $request->input('start_time');
		$order->end_time = $request->input('end_time');
		$order->programmed_date = $request->input('programmed_date');
		$order->status_id = 1;
		$order->contract_id = $request->input('contract_id');
		$order->execution = $request->input('execution');
		$order->areas = $request->input('areas');
		$order->additional_comments = $request->input('additional_comments');
		$order->price = $request->input('price');
		$order->created_at = now();
		$order->save();

		$order_services = [];
		$order_technicians = [];

		$order_services = array_map(function ($service_id) use ($order) {
			return [
				'service_id' => $service_id,
				'order_id' => $order->id,
			];
		}, $selected_services);

		if ($selected_technicians[count($selected_technicians) - 1] == 0) {
			$technicians = Technician::all();
			foreach ($technicians as $tech) {
				$order_technicians[] = [
					'technician_id' => $tech->id,
					'order_id' => $order->id,
				];
			}
		} else {
			foreach ($selected_technicians as $technicianId) {
				$technician = Technician::where('user_id', $technicianId)->first();
				$order_technicians[] = [
					'technician_id' => $technician->id,
					'order_id' => $order->id,
				];
			}
		}

		OrderService::insert($order_services);
		OrderTechnician::insert($order_technicians);
		/*OrderFrequency::insert([
									'order_id' => $order->id,
									'number' => $request->input('number'),
									'frequency' => $request->input('frequency'),
									'next_date' => $this->generateDate($order->programmed_date, $request->input('number'), $request->input('frequency')),
									'created_at' => now(),
									'updated_at' => now()
								]);*/

		$sql = 'INSERT_ORDER_' . $order->id;
		PagesController::log('store', 'Creacion de orden', $sql);

		return redirect()->route('order.index');
	}

	public function storeSignature(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg|max:5120'
    ]);

    $signature_name = $request->signature_name;
    $order_id = $request->order_id;

    $order = Order::find($order_id);
    $order->signature_name = $signature_name;

    $image = $request->file('image');
    $imageBinary = base64_encode(file_get_contents($image->getRealPath())); // Leer datos binarios
    $order->customer_signature = $imageBinary;

    $order->save();

    return redirect()->back()->with('success', 'Firma guardada correctamente.');
}

	public function search(Request $request)
	{
		$customer = $request->input('customer');
		$date = $request->input('date');
		$time = $request->input('time');
		$service = $request->input('service');
		$status = $request->input('status');

		$orders = Order::where('status_id', $status);

		if ($customer) {
			$searchTerm = '%' . $customer . '%';
			$customerIds = Customer::where('name', 'LIKE', $searchTerm)->get()->pluck('id');
			$orders = $orders->whereIn('customer_id', $customerIds);
		}

		if ($time) {
			$orders = $orders->whereTime('start_time', $time);
		}

		if ($date) {
			[$startDate, $endDate] = array_map(function ($d) {
				return Carbon::createFromFormat('d/m/Y', trim($d));
			}, explode(' - ', $date));
			$startDate = $startDate->format('Y-m-d');
			$endDate = $endDate->format('Y-m-d');
			$orders = $orders->whereBetween('programmed_date', [$startDate, $endDate]);
		}

		if ($service) {
			$serviceName = '%' . $service . '%';
			$serviceIds = Service::where('name', 'LIKE', $serviceName)->get()->pluck('id');
			$orderIds = OrderService::whereIn('service_id', $serviceIds)->get()->pluck('order_id');
			$orders = $orders->whereIn('id', $orderIds);
		}

		$size = $this->size;
		$order_status = OrderStatus::all();
		$orders = $orders->paginate($size)->appends([
			'customer' => $customer,
			'date' => $date,
			'time' => $time,
			'service' => $service,
			'status' => $status,
		]);

		return view(
			'order.index',
			compact(
				'orders',
				'order_status',
				'size'
			)
		);
	}
	public function searchService(Request $request, $type)
	{
		$serviceIdsArray = [];
		$services = [];

		if ($type == 0) {
			$pestsJson = $request->input('pests'); // Obtén la cadena JSON
			$app_methodsJson = $request->input('app_methods'); // Obtén la cadena JSON

			$pestsArray = json_decode($pestsJson, true); // Decodifica la cadena JSON a un arreglo asociativo
			$app_methodsArray = json_decode($app_methodsJson, true); // Decodifica la cadena JSON a un arreglo asociativo
			$has_pests = $request->input('has_pests');
			$has_app_methods = $request->input('has_app_methods');

			if ($has_pests != null && $has_app_methods != null) {
				if ($has_pests == 0 && $has_app_methods == 0) {
					$serviceIdsArray = Service::where('has_pests', $has_pests)->where('has_application_methods', $has_app_methods)->pluck('id')->toArray();
				} else {
					if (count($app_methodsArray) > 0) {
						if (count($pestsArray) <= 0) {
							$serviceIdsArray = ApplicationMethodService::whereIn('application_method_id', $app_methodsArray)->pluck('service_id')->toArray();
							$serviceIdsArray = Service::whereIn('id', $serviceIdsArray)->where('has_pests', 0)->pluck('id')->toArray();
						} else {
							$serviceIdsArray = DB::table('application_method_service')
								->join('pest_service', 'application_method_service.service_id', '=', 'pest_service.service_id')
								->whereIn('pest_service.pest_id', $pestsArray)
								->whereIn('application_method_service.application_method_id', $app_methodsArray)
								->pluck('application_method_service.service_id')
								->toArray();
						}
					}
				}
			}
		} else {
			$request->validate([
				'search_service_input' => 'required|string',
			]);
			$serviceIdsArray = Service::where('name', 'like', '%' . $request->input('search_service_input') . '%')->pluck('id');
		}

		$services = Service::whereIn('id', $serviceIdsArray)->get();
		$pests = [];
		$app_methods = [];
		$service_types = [];
		$business_lines = [];

		foreach ($services as $service) {
			$pests[] = $service->pests()->pluck('name');
			$app_methods[] = $service->appMethods()->pluck('name');
			$service_types[] = $service->serviceType()->pluck('name');
			$business_lines[] = $service->businessLine()->pluck('name');
		}

		return response()->json([
			'services' => $services,
			'pests' => $pests,
			'app_methods' => $app_methods,
			'service_types' => $service_types,
			'business_lines' => $business_lines,
			'pest_categories' => PestCategory::all(),
			'application_methods' => ApplicationMethod::all(),
			'show' => count($serviceIdsArray) > 0 ? true : false,
		]);
	}

	public function searchCustomer(Request $request)
	{
		$clients = [];
		$name = $request->input('customer_name');
		$phone = $request->input('customer_phone');
		$address = $request->input('customer_address');

		$sedes = Customer::when(!empty($name), fn($query) => $query->where('name', 'like', '%' . $name . '%'))
			->when(!empty($phone), fn($query) => $query->where('phone', 'like', '%' . $phone . '%'))
			->when(!empty($address), fn($query) => $query->where('address', 'like', '%' . $address . '%'))
			->where('status', '!=', 0)
			->where('general_sedes', '!=', 0)
			->get();

		$customers = Customer::when(!empty($name), fn($query) => $query->where('name', 'like', '%' . $name . '%'))
			->when(!empty($phone), fn($query) => $query->where('phone', 'like', '%' . $phone . '%'))
			->when(!empty($address), fn($query) => $query->where('address', 'like', '%' . $address . '%'))
			->where('status', '!=', 0)
			->where('general_sedes', 0)
			->get();

		if ($sedes->isNotEmpty() && $customers->isEmpty()) {
			foreach ($sedes as $sede) {
				$clients[] = Customer::find($sede->general_sedes);
			}
			$customers = $clients;
		}

		if ($sedes->isEmpty() && $customers->isNotEmpty()) {
			foreach ($customers as $customer) {
				$client = Customer::where('service_type_id', 3)->where('general_sedes', $customer->id)->get();
				if ($client->isNotEmpty()) {
					$clients[] = $client[0];
				}
			}
			$sedes = $clients;
		}

		$client_customers = [];
		foreach ($customers as $customer) {
			$client_customers[] = [
				'id' => $customer->id,
				'name' => $customer->name,
				'sedes' => $customer->sedes()->get(['id', 'name']),
			];
		}

		$result = [
			'customers' => $customers,
			'sedes' => $sedes,
			'client_customers' => $client_customers
		];

		return response()->json($result);
	}

	public function show(string $id, string $section)
	{
		$order = null;
		$order = Order::find($id);
		$searchTerm = '%' . 'ORDER_' . $id . '%';
		$tablelog = DatabaseLog::where('sql_command', 'LIKE', $searchTerm)->get();

		return view(
			'order.show',
			with(
				compact(
					'order',
					'section',
					'tablelog'
				)
			)
		);
	}

	public function selectItems($comp_arr, $data_arr)
	{
		foreach ($data_arr as $data) {
			$data->checked = in_array($data->id, $comp_arr);
		}
		return $data_arr;
	}

	public function edit(string $id): View
	{
		$services = $pest_categories = $application_methods = [];
		$order = Order::find($id);
		$orders = Order::orderBy('id', 'desc')->get();
		$order_status = OrderStatus::all();

		if (!isset($order->customer_id)) {
			$error = 'No se ha seleccionado un cliente.';
			return view(
				'order.index',
				with(
					compact(
						'orders',
						'order_status',
						'error',
						'success',
						'warning'
					)
				)
			);
		}
		$customer = Customer::find($order->customer_id);

		$technicians = User::where('role_id', 3)->where('status_id', 2)->orderBy('name', 'asc')->get();

		return view(
			'order.edit',
			compact(
				'order',
				'order_status',
				'customer',
				'technicians',
			)
		);
	}
	public function update(Request $request, string $id): RedirectResponse
	{
		$change = null;
		$order = Order::find($id);
		$customers = Customer::all();
		$services = Service::all();

		if ($request->missing('technicians')) {
			$error = 'No se ha seleccionado un técnico.';

			return back();
		}

		$order->fill($request->all());
		$order->updated_at = now();
		$order->save();

		$selected_services = json_decode($request->services);

		if (!empty($selected_services)) {
			$order_services = OrderService::where('order_id', $order->id)->get();

			$order_services_delete = array_diff($order_services->pluck('service_id')->toArray(), $selected_services);
			//dd($order_services_delete);
			$order_services_insert = array_diff($selected_services, $order_services->pluck('service_id')->toArray());
			//dd($order_services_insert);

			OrderService::where('order_id', $order->id)->whereIn('service_id', $order_services_delete)->delete();

			foreach ($order_services_insert as $serviceId) {
				OrderService::updateOrInsert(
					[
						'order_id' => $order->id,
						'service_id' => $serviceId,
					],
					[
						'updated_at' => now(),
					]
				);
			}
		}

		$ot_array = json_decode($request->input('technicians'));

		if (!empty($ot_array)) {
			if ($ot_array[count($ot_array) - 1] == 0) {
				$technicians = Technician::pluck('id')->toArray();
				$technicianSelected = OrderTechnician::where('order_id', $id)->pluck('technician_id')->toArray();
				$tDiff = array_diff($technicians, $technicianSelected);

				if (!empty($tDiff)) {
					foreach ($tDiff as $tID) {
						OrderTechnician::create([
							'technician_id' => $tID,
							'order_id' => $id,
						]);
					}
				}
			} else {
				foreach ($ot_array as $technicianId) {
					$technician = Technician::where('user_id', $technicianId)->first();
					OrderTechnician::updateOrCreate(
						['technician_id' => $technicianId, 'order_id' => $id],
						['technician_id' => $technicianId, 'order_id' => $id]
					);
				}

				OrderTechnician::where('order_id', $id)
					->whereNotIn('technician_id', $ot_array)
					->delete();
			}
			//$changes .= 'Técnicos actualizados';
		}

		/*$sql = 'UPDATE_ORDER_' . $order->id;
												  PagesController::log('update', $changes, $sql);*/

		return back();
	}

	public function destroy(string $id): RedirectResponse
	{
		$order = Order::find($id);

		if ($order) {
			$order->status_id = 6;
			$order->save();
		}

		return back();
	}

	private function setFile($data, $name, $extension = 'png')
	{
		$file_name = $name . '.' . $extension;
		$directory = storage_path($this->files_path);
		if (!file_exists($directory)) {
			mkdir($directory, 0777, true);
		}
		$path = $directory . '/' . $file_name;
		file_put_contents($path, $data);
		return $path;
	}

	public function getControlPoints(Request $request)
	{
		$floorplanID = $request->input('floorplan_id');
		$orderID = $request->input('order_id');
		$version = $request->input('version');
		$data = [];

		$devices = Device::where('floorplan_id', $floorplanID)->where('version', $version)->get();

		foreach ($devices as $device) {
			$questions = [];
			$incidents = $device->incidents()->where('order_id', $orderID)->get();
			foreach ($incidents as $incident) {
				$questions[] = [
					'optionID' => $incident->question->option->id,
					'question' => $incident->question()->first()->question,
					'answer' => $incident->answer,
				];
			}
			$data[] = [
				'deviceID' => $device->id,
				'nplan' => $device->nplan,
				'name' => optional($device->controlPoint->product)->name,
				'zone' => $device->applicationArea()->first()->name,
				'questions' => !empty($questions) ? $questions : $device->questions()->get(),
			];
		}
		return response()->json($data);
	}

	public function checkReport($id)
	{
		$answers = json_decode(file_get_contents(public_path($this->file_answers_path)), true);
		$order = Order::find($id);
		$recommendations = Recommendations::all();
		$application_methods = ApplicationMethod::all();
		$lots = Lot::all();
		$products = ProductCatalog::all();
		$services = $order->services()->get();
		$metrics = Metric::all();
		$pests = $services->flatMap(function ($service) {
			return $service->pests()->get();
		})->unique('id')->values();

		return view('report.create', compact(
			'order',
			'answers',
			'recommendations',
			'application_methods',
			'pests',
			'services',
			'products',
			'lots',
			'metrics',
		));
	}
}
