<?php

namespace App\Http\Controllers;

use App\Models\ApplicationArea;
use App\Models\Customer;
use App\Models\FloorPlans;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\OrderStatus;
use App\Models\Service;
use App\Models\User;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use PhpParser\ErrorHandler\Collecting;
use View;

class QualityController extends Controller
{
    private $size = 50;

    public function index()
    {
        $size = $this->size;
        $user = auth()->user();
        $customers = Customer::where('general_sedes', '!=', 0);

        if ($user->work_department_id == 7) {
            $customers->where('administrative_id', $user->id);
        }

        $customers = $customers->paginate($this->size);

        return View(
            'dashboard.quality.index',
            compact('customers', 'size')
        );
    }

    public function control()
    {
        $users = User::where('work_department_id', 7)->get();
        $customers = Customer::where('general_sedes', 0)->paginate($this->size);
        $control_customers = Customer::whereIn('administrative_id', $users->pluck('id'))->where('general_sedes', 0)->paginate($this->size);
        $size = $this->size;

        return View(
            'dashboard.quality.control',
            compact('users', 'customers', 'control_customers', 'size')
        );
    }

    public function storeControl(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $administrative_id = $request->input('user_id');
        $customer = Customer::find($customer_id);

        if ($customer) {
            $customer->administrative_id = $administrative_id;
            $customer->save();

            $sedes = Customer::where('general_sedes', $customer_id)->get();
            foreach ($sedes as $sede) {
                $sede->administrative_id = $administrative_id;
                $sede->save();
            }
        }

        return back();
    }

    public function destroyControl(string $id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->administrative_id = null;
            $customer->save();

            $sedes = Customer::where('general_sedes', $customer->id)->get();
            foreach ($sedes as $sede) {
                $sede->administrative_id = null;
                $sede->save();
            }
        }

        return back();
    }

    public function search(Request $request)
    {
        $data = [];
        $size = $this->size;
        $search = $request->search;
        $searchTerm = '%' . $search . '%';
        $customers = Customer::where('name', 'LIKE', $searchTerm)
            ->where('general_sedes', '!=', 0)
            ->paginate($size)
            ->appends(compact('search'));
        return View(
            'dashboard.quality.index',
            compact('customers', 'size')
        );
    }

    public function customer(string $id)
    {
        $pendings = [];
        $count_devices = 0;
        $customer = Customer::find($id);
        $orders = $customer->orders()->where('status_id', 1)->get();
        
        $floorplans = $customer->floorplans;
        
        foreach ($floorplans as $floorplan) {
            $last_version = $floorplan->versions()->latest('version')->value('version');
            $count_devices += $floorplan->devices($last_version)->count();
        }

        foreach ($customer->contracts as $contract) {
            $endDate = Carbon::parse($contract->enddate);
            if ($endDate->isBetween(Carbon::now(), Carbon::now()->addDays(31))) {
                $pendings[] = [
                    'id' => $contract->id,
                    'content' => 'El contrato con id "' . $contract->id . '" esta apunto de expirar.',
                    'date' => $contract->enddate,
                    'type' => 'contract'
                ];
            }
        }

        foreach ($orders as $order) {
            $programmed_date = Carbon::parse($order->programmed_date);
            if ($programmed_date->isBetween(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek())) {
                $pendings[] = [
                    'id' => $order->id,
                    'content' => 'La orden de servicio con id "' . $order->id . '" con los servicios "' . implode(', ', $order->services->pluck('name')->toArray()) . '", esta programada para esta semana.',
                    'date' => $order->programmed_date,
                    'type' => 'order'
                ];
            }
        }

        foreach ($customer->files as $file) {
            $expirated_date = Carbon::parse($file->expirated_at);
            if ($expirated_date->isBetween(Carbon::now(), Carbon::now()->addDays(31))) {
                $pendings[] = [
                    'id' => $customer->id,
                    'content' => 'El Documento "' . $file->filename->name . '" esta apunto de expirar.',
                    'date' => $file->expirated_at,
                    'type' => 'file'
                ];
            }

        }

        $size = count($pendings);
        $start = count($pendings) - 20;
        $pendings = array_slice($pendings, $start, 20);

        return view(
            'dashboard.quality.show.customer',
            compact('customer', 'count_devices', 'pendings')
        );
    }

    public function orders(string $id)
    {
        $size = $this->size;
        $customer = Customer::find($id);
        $order_status = OrderStatus::all();
        $orders = Order::where('customer_id', $customer->id)->paginate($size);

        return view(
            'dashboard.quality.order.index',
            compact('orders', 'customer', 'order_status')
        );
    }

    public function contracts(string $id)
    {
        $customer = Customer::find($id);
        $contracts = Contract::where('customer_id', $customer->id)->orderBy('enddate', 'desc')->get();

        return view(
            'dashboard.quality.show.contracts',
            compact('contracts')
        );
    }

    public function floorplans(string $id)
    {
        $customer = Customer::find($id);
        $floorplans = FloorPlans::where('customer_id', $customer->id)->get();

        return view(
            'dashboard.quality.show.floorplans',
            compact('floorplans')
        );
    }

    public function zones(string $id)
    {
        $customer = Customer::find($id);
        $zones = ApplicationArea::where('customer_id', $customer->id)->paginate($this->size);

        // $deviceByArea = 0;
        // foreach($customer->floorplans as $floorplan)
        // {
        //     foreach ($floorplan->devices($floorplan->versions->pluck('version')->first())->get() as $device) {
        //         if($device->application_area_id == $zone->id)
        //         {
        //             $deviceByArea++;
        //         }
        //     }  
        // }

        return view(
            'dashboard.quality.zone.index',
            compact('zones', 'customer')
        );
    }

    public function devices(string $id)
    {
        $customer = Customer::find($id);
        $floorplans = FloorPlans::where('customer_id', $customer->id)->get();
        $deviceSummary = [];
        foreach ($floorplans as $floorplan) {
            $last_version = $floorplan->versions()->latest('version')->value('version');
            $devices = $floorplan->devices($last_version)->get();
            foreach ($devices as $device) {
                $deviceId = $device->controlPoint->id;
                if (!isset($deviceSummary[$deviceId])) {
                    $deviceSummary[$deviceId] = [
                        'id' => $deviceId,
                        'name' => $device->controlPoint->name,
                        'count' => 0,
                        'code' => $device->controlPoint->code,
                        'floorplans' => [],
                        'zones' => [],
                    ];
                }
                $deviceSummary[$deviceId]['count']++;
                
                // Agrega los dispositivos que no se han agregado
                if (!in_array($device->applicationArea->name, $deviceSummary[$deviceId]['zones'])) {
                    $deviceSummary[$deviceId]['zones'][] = $device->applicationArea->name;
                }
                // Agrega los planos que no se han agregado
                if (!in_array($floorplan->filename, $deviceSummary[$deviceId]['floorplans'])) {
                    $deviceSummary[$deviceId]['floorplans'][] = $floorplan->filename;
                }
            }
        }

        
        return view(
            'dashboard.quality.device.index',
            compact('deviceSummary', 'customer')
        );
    }

    public function getOrdersByCustomer(Request $request)
    {
        $orders = [];

        if (!empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $customerIDs = Customer::where('name', 'LIKE', $searchTerm)->pluck('id');
            $orders = Order::whereIn('customer_id', $customerIDs)->pluck('id');
            // orWhere()
        }

        return response()->json(['orders' => 1]);
    }
    
    public function searchOrders(Request $request, string $id) {

        $customer = Customer::find($id);

		$date = $request->input('date');
        $time = $request->input('time');
        $service = $request->input('service');
        $status = $request->input('status');

        $orders = Order::where('customer_id', $id)->where('status_id', $status);

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
		$orders = $orders->paginate($size)->appends([
			'date' => $date,
			'time' => $time,
			'service' => $service,
			'status' => $status,
		]);
		$order_status = OrderStatus::all();

		return view(
			'dashboard.quality.order.index',
			compact(
				'orders',
				'order_status',
                'customer',
				'size'
			)
		);
    }

    public function getOrdersByTime(Request $request)
    {
        $orders = [];
        if (!empty($request->start_time)) {
            $time_request = Carbon::parse($request->start_time)->format('H:i');
            $orders = Order::whereTime('start_time', '=', $time_request)->pluck('id')->toArray();
        }

        return response()->json(['orders' => $orders]);
    }

    public function getOrdersByDate(Request $request)
    {
        $orders = [];

        if (!empty($request->programmed_date)) {
            $date_request = Carbon::parse($request->programmed_date)->format('Y-m-d');
            $orders = Order::whereDate('programmed_date', $date_request)->pluck('id')->toArray();
        }

        return response()->json(['orders' => $orders]);
    }

    public function getOrdersByService(Request $request)
    {
        $orders = [];

        if (!empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            // $order->services->pluck('name')->toArray()
            $servicesId = Service::where('name', 'LIKE', $searchTerm)->pluck('id');
            // $orders = Order::services->whereIn('customer_id', $servicesId)->pluck('id');
            $orders = Order::whereHas('services', function ($query) use ($servicesId) {
                $query->whereIn('service_id', $servicesId); // Filtrar servicios por ID
            })->pluck('id'); // Obtener solo los IDs de las órdenes
        }

        return response()->json(['orders' => $orders]);
    }

    public function getOrdersByStatus(Request $request)
    {
        $orders = [];

        if (!empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $statusId = OrderStatus::where('name', 'LIKE', $searchTerm)->pluck('id');
            $orders = Order::whereIn('status_id', $statusId)->pluck('id');
        }

        return response()->json(['orders' => $orders]);
    }
}