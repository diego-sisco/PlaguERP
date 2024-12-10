<?php

namespace App\Http\Controllers;

use App\Models\ApplicationArea;
use App\Models\Customer;
use App\Models\FloorPlans;
use App\Models\Order;
use App\Models\User;
use App\Models\Contract;

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

        $floorplans = FloorPlans::where('customer_id', $customer->id)->get();
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

    public function orders(string $id, string $statusId)
    {
        $size = $this->size;
        $customer = Customer::find($id);
        $orders = Order::where('customer_id', $customer->id)->where('status_id', $statusId)->paginate($size);

        return view(
            'dashboard.quality.show.orders',
            compact('orders', 'customer')
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
        $zones = ApplicationArea::where('customer_id', $customer->id)->get();

        return view(
            'dashboard.quality.show.zones',
            compact('zones')
        );
    }

    public function devices(string $id)
    {
    }

    public function showCustomerDetails(string $id): View
    {
        $client = $floortype = $prope = $actibableprop = $actibableprop = $defaultinac = $defaultprop = $activeprop = $sedes = $reference_types = $refs = $customer_file = $zones = $floorplans = null;

        $companies = Company::all();
        $companyCategories = CompanyCategory::all();
        $services = ServiceType::all();
        $branches = Branch::all();
        $tax_regimes = TaxRegime::all();
        $referenceTypes = Reference_type::all();
        $floorTypes = FloorType::all();

        $customer = Customer::find($id);

        $products = 0;
        $pendingCount = 0;
        $customerPending = [];

        foreach ($customer->floorplans as $floorplan) {
            foreach ($floorplan->devices($floorplan->versions->pluck('version')->first())->get() as $device) {
                $products++;
            }
        }







        $customerData = [
            'servicePendiente' => $customer->countOrdersbyStatus(1),
            'serviceAccepted' => $customer->countOrdersbyStatus(2),
            'serviceFinished' => $customer->countOrdersbyStatus(3),
            'serviceVerified' => $customer->countOrdersByStatus(4),
            'serviceApproved' => $customer->countOrdersByStatus(5),
            'serviceCanceled' => $customer->countOrdersByStatus(6),
            'floorplansCount' => $customer->floorplans->count(),
            'applicationAreaCount' => $customer->applicationAreas()->count(),
            'devices' => $products,
            'customerFile' => $customer->files->where('path', '!=', NULL)->count(),
            'pendings' => $customerPending,
        ];

        $states = file_get_contents(public_path($this->states_route));
        $cities = file_get_contents(public_path($this->cities_route));
        $states = json_decode($states, true);
        $cities = json_decode($cities, true);

        return view('customer.show.details', compact('customer', 'customerData', 'companies', 'companyCategories', 'services', 'branches', 'tax_regimes', 'referenceTypes', 'floorTypes'));
    }

    public function getOrdersByCustomer(Request $request)
    {
        $orders = [];

        if (!empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $customerIDs = Customer::where('name', 'LIKE', $searchTerm)->pluck('id');
            $orders = Order::whereIn('customer_id', $customerIDs)->pluck('id');
        }

        return response()->json(['orders' => 1]);
    }


    public function getOrdersByHour(Request $request)
    {
        $orders = [];

        $date_request = Carbon::parse($request->programmed_date)->format('d-m-Y');

        if (!empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            // $customerIDs = Customer::where('name', 'LIKE', $searchTerm)->pluck('id');

            $orders = Order::whereDate('programmed_date', $date_request)->get()->pluck('id');

            // $orders = Order::whereIn('customer_id', $customerIDs)->pluck('id');
        }
        // Carbon::parse($order->start_time)->format('H:i'),
        // Carbon::parse($order->programmed_date)->format('d-m-Y'),

        return response()->json(['orders' => 1]);
    }

    public function getOrdersByDate(Request $request)
    {
        $orders = [];


        $date_request = Carbon::parse($request->programmed_date)->format('d-m-Y');

        if (!empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            // $customerIDs = Customer::where('name', 'LIKE', $searchTerm)->pluck('id');

            $orders = Order::whereDate('programmed_date', $date_request)->get()->pluck('id');

            // $orders = Order::whereIn('customer_id', $customerIDs)->pluck('id');
        }
        // Carbon::parse($order->start_time)->format('H:i'),
        // Carbon::parse($order->programmed_date)->format('d-m-Y'),

        return response()->json(['orders' => $orders]);
    }

    public function getOrdersByService(Request $request)
    {
        $orders = [];

        if (!empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $customerIDs = Customer::where('name', 'LIKE', $searchTerm)->pluck('id');
            $orders = Order::whereIn('customer_id', $customerIDs)->pluck('id');
        }

        return response()->json(['orders' => $orders]);
    }

    public function getOrdersByStatus(Request $request)
    {
        $orders = [];

        if (!empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $customerIDs = Customer::where('name', 'LIKE', $searchTerm)->pluck('id');
            $orders = Order::whereIn('customer_id', $customerIDs)->pluck('id');
        }

        return response()->json(['orders' => $orders]);
    }
}
