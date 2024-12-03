<?php

namespace App\Http\Controllers;

// Verificación de retorno

use App\Models\Administrative;
use App\Models\Branch;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// Modelos
use App\Models\User;
use App\Models\Customer;
use App\Models\DatabaseLog;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\OrderStatus;
use App\Models\OrderTechnician;
use App\Models\Service;
use App\Models\Technician;
use App\Models\Lead;
use App\Models\LineBusiness;
use App\Models\OrderFrequency;
use App\Models\ServiceType;
use App\Models\UserFile;
use App\Models\Warehouse;
use App\Models\Lot;

use Carbon\Carbon;

use function Laravel\Prompts\alert;

class PagesController extends Controller
{
    private $hrs_format = [
        "00:00:00",
        "01:00:00",
        "02:00:00",
        "03:00:00",
        "04:00:00",
        "05:00:00",
        "06:00:00",
        "07:00:00",
        "08:00:00",
        "09:00:00",
        "10:00:00",
        "11:00:00",
        "12:00:00",
        "13:00:00",
        "14:00:00",
        "15:00:00",
        "16:00:00",
        "17:00:00",
        "18:00:00",
        "19:00:00",
        "20:00:00",
        "21:00:00",
        "22:00:00",
        "23:00:00"
    ];

    private $months = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
    ];

    private $size = 20;

    private function convertToUTC($date, $time)
    {
        $timezone = 'America/Mexico_City';
        $dateTimeLocal = $date . ' ' . $time;
        $carbonLocal = Carbon::createFromFormat('Y-m-d H:i:s', $dateTimeLocal, $timezone);
        return $carbonLocal->toDateTimeString();
    }

    private function getOrdersByTimeLapse($time_lapse, $orders)
    {
        if ($time_lapse == 1) {
            $orders->where('programmed_date', now()->toDateString());
        } elseif ($time_lapse == 2) {
            $orders->whereBetween('programmed_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($time_lapse == 3) {
            $orders->whereBetween('programmed_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        }

        return $orders->get();
    }

    private function getPlanningData($orders)
    {
        $data = [];
        for ($i = 0; $i < count($this->hrs_format); $i++) {
            $activities = [];
            $hourCarbon = Carbon::createFromFormat('H:i:s', $this->hrs_format[$i])->format('H:00:00');
            foreach ($orders as $order) {
                $orderTime = Carbon::createFromFormat('H:i:s', $order->start_time)->format('H:00:00');
                if ($hourCarbon == $orderTime) {
                    $activities[] = [
                        'id' => $order->id,
                        'service_type' => $order->customer->service_type_id,
                        'services' => $order->services->pluck('name')->toArray(),
                        'start_time' => Carbon::parse($order->start_time)->format('H:i'),
                        'programmed_date' => Carbon::parse($order->programmed_date)->format('d-m-Y'),
                        'address' => $order->customer->address . ', ' . $order->customer->city . ', ' . $order->customer->state,
                        'customer' => $order->customer->name,
                        'status_id' => $order->status_id,
                        'status' => $order->status->name,
                        'technicianIds' => OrderTechnician::where('order_id', $order->id)->get()->pluck('technician_id')
                    ];
                }
            }
            $data[] = [
                'hour' => Carbon::parse($this->hrs_format[$i])->format('H:i'),
                'activities' => $activities
            ];
        }

        return $data;
    }

    public function dashboard()
    {
        return view('dashboard.index');
    }

    public function crm()
    {
        /*
        $charts = [
            'customers' => (new GraphicController)->newCustomers(),
            'orders' => (new GraphicController)->orders(),
            'domestic' => (new GraphicController)->orderTypes(1),
            'comercial' => (new GraphicController)->orderTypes(2),
        ];

        $chartNames = [
            'Nuevos clientes',
            'Clientes agendados',
            'Clientes domesticos agendados',
            'Clientes comerciales agendados',
        ];

        $frecuencies = OrderFrequency::all();
        $leads = Lead::all();
        $months = $this->months;
        */
        return view('dashboard.crm.index');
    }

    public function crmOrders(string $status)
    {
        $orders = Order::where('status_id', $status)->orderBy('id', 'desc');
        return view('dashboard.crm.', compact('customers', 'order_status', 'type'));
    }


    public function rrhh($section)
    {
        $users = User::where('status_id', 1)->get();
        $files = $section == 2 ? UserFile::where('path', null)->get() : UserFile::whereMonth('expirated_at', '<=', Carbon::now()->month)->get();

        return view('dashboard.rrhh.index', compact('users', 'files', 'section'));
    }

    public function qualityOrders(string $status)
    {        
        $user = auth()->user();
        $orders = Order::where('status_id', $status);

        if($user->role_id == 1 && $user->work_department_id == 7) {
            $customerIds = Customer::where('administrative_id', $user->id)->get()->pluck('id');
            $orders = $orders->whereIn('customer_id', $customerIds);
        } 

        $orders = $orders->paginate($this->size);

        return view(
            'dashboard.quality.orders',
            compact('orders', 'status')
        );
    }

    public function qualityCustomers()
    {
        $totalPages = 0;
        $customers = Customer::where('general_sedes','!=' , 0)->where('service_type_id', 3)->get();
        return view(
            'dashboard.quality.customers',
            compact('customers')
        );
    }

    public function qualityControl()
    {
        $totalPages = 0;

        $customers = Customer::where('general_sedes', 0)->get();
        $users = User::where('work_department_id', 7)->get();

        return view(
            'dashboard.quality.control',
            compact('customers', 'users')
        );
    }

    public function qualityControlStore(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $administrative_id = $request->input('user_id');
        $customer = Customer::find($customer_id);

        if($customer) {
            $customer->administrative_id = $administrative_id;
            $customer->save();

            $sedes = Customer::where('general_sedes', $customer_id)->get();
            foreach($sedes as $sede) {
                $sede->administrative_id = $administrative_id;
                $sede->save();
            }
        }
        
        return back();
    }

    public function qualityControlDestroy(string $customerId)
    {

        $customer = Customer::find($customerId);
        if ($customer) {
            $customer->administrative_id = null;
            $customer->save();
            $sedes = Customer::where('general_sedes', $customerId)->get();
            foreach ($sedes as $sede) {
                $sede->administrative_id = null;
                $sede->save();
            }
        }

        return back();
    }

    public function planning(Request $request): View
    {
        $urlName = Str::afterLast(Route::currentRouteName(), '.');
        $date = $request->input('date');
        $daily_program = [];
        $customers = Customer::all();
        $services = Service::all();
        $order_services = OrderService::all();
        $status = OrderStatus::all();
        $business_lines = LineBusiness::all();
        $service_types = ServiceType::all();
        $branches = Branch::all();
        $technicians = Technician::all();

        if ($date) {
            [$startDate, $endDate] = array_map(function ($d) {
                return Carbon::createFromFormat('d/m/Y', trim($d));
            }, explode(' - ', $date));
        } else {
            $startDate = now();
            $endDate = now();
        }

        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        $orders = Order::whereBetween('programmed_date', [$startDate, $endDate])->get();
        if ($orders) {
            $daily_program = $this->getPlanningData($orders);
        }
        $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->format('d/m/Y');
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->format('d/m/Y');
        $date = $startDate . ' - ' . $endDate;
        //dd($daily_program);
        $view = ($urlName == 'activities') ? 'dashboard.planning.activities' : 'dashboard.planning.schedule';
        return view($view, compact('daily_program', 'date', 'technicians', 'business_lines', 'branches', 'service_types', 'order_services', 'customers', 'status'));
    }

    public function filterPlanning(Request $request)
    {
        $daily_program = $technicians = $orders = [];

        try {
            $data = json_decode($request->input('data'), true);
            $date = json_decode($request->input('date'), true);
            if ($data) {
                $key = $data['key'];
                $values = $data['values'];

                [$startDate, $endDate] = array_map(function ($d) {
                    return Carbon::createFromFormat('d/m/Y', trim($d))->format('Y-m-d');
                }, explode(' - ', $date));

                switch ($key) {
                    case 'technician':
                        $orders = Order::whereIn(
                            'id',
                            OrderTechnician::whereIn('technician_id', $values)->get()->pluck('order_id')
                        )->whereBetween('programmed_date', [$startDate, $endDate])->get();
                        $technicians = Technician::whereIn('id', $values)->get();
                        break;

                    case 'business_line':
                        $orders = Order::whereIn(
                            'id',
                            OrderService::whereIn(
                                'service_id',
                                Service::whereIn('business_line_id', $values)->get()->pluck('id')
                            )->get()->pluck('order_id')
                        )->whereBetween('programmed_date', [$startDate, $endDate])->get();
                        $technicians = Technician::whereIn(
                            'id',
                            OrderTechnician::whereIn('order_id', $orders->pluck('id'))->get()->pluck('technician_id')
                        )->get();
                        break;

                    case 'branch':
                        $orders = Order::whereIn(
                            'customer_id',
                            Customer::whereIn('branch_id', $values)->get()->pluck('id')
                        )->whereBetween('programmed_date', [$startDate, $endDate])->get();
                        $technicians = Technician::whereIn('branch_id', $values)->get();
                        break;

                    case 'service_type':
                        $orders = Order::whereIn(
                            'id',
                            OrderService::whereIn(
                                'service_id',
                                Service::whereIn('service_type_id', $values)->get()->pluck('id')
                            )->get()->pluck('order_id')
                        )->whereBetween('programmed_date', [$startDate, $endDate])->get();
                        $technicians = Technician::whereIn(
                            'id',
                            OrderTechnician::whereIn('order_id', $orders->pluck('id'))->get()->pluck('technician_id')
                        )->get();
                        break;

                    default:
                        $orders = [];
                        $technicians = [];
                        break;
                }
            }
            if ($orders) {
                $daily_program = $this->getPlanningData($orders);
            }

            return response()->json([
                'daily_program' => $daily_program,
                'technicians' => $technicians->map(function ($technician) {
                    return [
                        'id' => $technician->id,
                        'name' => $technician->user->name,
                    ];
                })->toArray(),
            ]);
        } catch (\Exception $e) {
            // Capturar cualquier excepción y manejarla
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function clients()
    {
        return view('clients.index');
    }

    public function updateSchedule(Request $request)
    {
        try {
            $data = $request->all();

            /*if (isset($data['date']) && isset($data['orderId'])) {
            $dateTimeString = $data['date'];

            // Extraer la fecha y hora
            $dateString = substr($dateTimeString, 4, 11); // 'Jul 04 2024'
            $timeString = substr($dateTimeString, 16, 8); // '12:00:00'
            $date = Carbon::createFromFormat('M d Y H:i:s', $dateString . ' ' . $timeString);

            $programmed_date = $date->format('Y-m-d'); // Formato Y-m-d para almacenar en base de datos
            $start_time = $date->format('H:i:s');

            // Encontrar la orden por ID
            $order = Order::find($data['orderId']);

            if ($order) {
                // Actualizar los campos programados en la orden
                $order->programmed_date = $programmed_date;
                $order->start_time = $start_time;
                $order->save();

                return response()->json(['message' => 'Save'], 200);
            } else {
                return response()->json(['message' => 'Order not found'], 404);
            }
        } else {
            return response()->json(['message' => 'Invalid data provided'], 400);
        }*/

            if (isset($data['technicianId']) && isset($data['orderId'])) {
                $order = Order::find($data['orderId']);
                $order->start_time = Carbon::createFromTime($data['hour'], 0, 0);
                $order->save();

                OrderTechnician::updateOrCreate(
                    ['order_id' => $order->id],
                    ['technician_id' => $data['technicianId']]
                );
            }

            return response()->json(200);
        } catch (\Exception $e) {
            // Capturar cualquier excepción y manejarla
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


    public function getOrdersByCustomer(Request $request)
    {
        $orders = [];

        if (!empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $customerIDs = Customer::where('name', 'LIKE', $searchTerm)->pluck('id');
            $orders = Order::whereIn('customer_id', $customerIDs)->pluck('id');
        }

        return response()->json(['orders' => $orders]);
    }

    public function orders(string $va, $page)
    {
        $customers = Customer::all();
        $order_services = OrderService::all();
        $services = Service::all();
        $status = OrderStatus::all();
        if ($va == 1) {
            //ordenes de servicio terminadas
            $orders = Order::where('status_id', 3)->get();
        } elseif ($va == 2) {
            //ordenes de servicio canceladas
            $orders = Order::where('status_id', 6)->get();
        } elseif ($va == 3) {
            $orders = Order::whereNotIn('status_id', [3, 6])->get();
        }

        return view('dashboard.tables.order', compact('customers', 'services', 'orders', 'order_services', 'va'));
    }

    public function trackingIndex(string $va, $page)
    {
        $customers = null;
        $size = 20;
        //clientes registrados 6 u 1 año antes a la fecha actual
        $fechaActual = Carbon::now();
        $haceUnAnio = $fechaActual->copy()->subYear();
        $haceSeisMeses = $fechaActual->copy()->subMonths(6);
        if ($va == 1) {
            $primerDiaMesActual = now()->startOfMonth();
            $ultimoDiaMesActual = now()->endOfMonth();

            $cust_ids = Customer::whereBetween('created_at', [$primerDiaMesActual, $ultimoDiaMesActual])
                ->whereNotNull('general_sedes')
                ->where('general_sedes', '!=', 0)
                ->pluck('id')
                ->toArray();

            $customers_withcontract = Contract::pluck('customer_id')->toArray();

            $customers_ids = array_diff($cust_ids, $customers_withcontract);
            if ($customers_ids) {
                $customers = Customer::whereIn('id', $customers_ids)->get();
            }
        } elseif ($va == 2) {
            $customers = Customer::where(function ($query) {
                $campos = Schema::getColumnListing((new Customer())->getTable());
                foreach ($campos as $campo) {
                    $query->orWhereNull($campo);
                }
            })->where(function ($query) {
                $query->whereNotNull('general_sedes')
                    ->where('general_sedes', '!=', 0);
            })->get();
        } else {
            $primerDiaMesActual = now()->startOfMonth(); // Primer día del mes actual
            $ultimoDiaMesActual = now()->endOfMonth();
            $cust_ids = Lead::whereBetween('created_at', [$primerDiaMesActual, $ultimoDiaMesActual])
                ->pluck('id')
                ->toArray();

            $customers = Lead::whereIn('id', $cust_ids)->get();
        }
        return view('dashboard.tables.customer', compact('customers', 'va'));
    }


    public static function log($type, $change, $sql)
    {
        DatabaseLog::insert([
            'user_id' => auth()->user()->id,
            'changetype' => $type,
            'change' => $change,
            'sql_command' => $sql,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
