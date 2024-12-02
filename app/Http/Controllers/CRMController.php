<?php

namespace App\Http\Controllers;

use App\Models\ServiceTracking;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    private $size = 100;

    public function index(string $type) {
        $customers = $type ? Customer::where('general_sedes', 0)->paginate($this->size) : Lead::paginate($this->size);
        $services = Service::all();
        $customer_services = [];
        $order_status = OrderStatus::all();
        foreach($customers as $customer) {
            $orders = Order::where('customer_id', $customer->id)->whereIn('status_id', [3, 4, 5])->get();
            foreach($orders as $order) {
                $customer_services[] = [
                    'customer_id' => $customer->id,
                    'services'  => $order->services()/*->select('service.id', 'service.name')*/->get()->toArray(),
                ];
            }
        }        

        return view('dashboard.crm.schedule.index', compact('customers', 'customer_services', 'order_status', 'services', 'type'));

    }

    public function tracking(Request $request, string $type) {
        $tracking = new ServiceTracking();
        $tracking->fill($request->all());
        $tracking->model_type = $type ? Customer::class : Lead::class;
        $tracking->save();

        return back();
    }

    public function orders(string $status)
    {
        $order_status = OrderStatus::all();
        $customerIds = Customer::where('service_type_id', '!=', 3)->where('general_sedes', '!=',  0)->get()->pluck('id');
        $orders = Order::where('status_id', $status)->whereIn('customer_id', $customerIds);
        $user = auth()->user();
        if ($user->work_department_id == 7) {
            $orders = $orders->where('administrative_id', $user->id);
        }
        $orders = $orders->paginate($this->size);
        return view(
            'dashboard.crm.schedule.tables.orders',
            compact('orders', 'status', 'order_status')
        );
    }
}
