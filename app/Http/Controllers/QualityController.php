<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;
use App\Models\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class QualityController extends Controller
{
    private $size = 50;
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

    public function searchOrders(Request $request)
    {
        $orders = [];
        $query = Order::query();
        // Filtro por cliente
        if (!empty($request->searchCustomer)) {
            $searchCustomer = '%' . $request->searchCustomer . '%';
            $customerIDs = Customer::where('name', 'LIKE', $searchCustomer)->pluck('id');
            $query->whereIn('customer_id', $customerIDs);
        }

        // Filtro por hora
        if (!empty($request->start_time)) {
            $start_time = Carbon::parse($request->start_time)->format('H:i');
            $query->whereTime('start_time', '=', $start_time);
        }

        // Filtro por fecha
        if (!empty($request->programmed_date)) {
            $programmed_date = Carbon::parse($request->programmed_date)->format('Y-m-d');
            $query->whereDate('programmed_date', $programmed_date);
        }
        
        // Filtro por servicio
        if (!empty($request->searchService)) {
            $searchService = '%' . $request->searchService . '%';
            $servicesId = Service::where('name', 'LIKE', $searchService)->pluck('id');
            $query->whereHas('services', function ($query) use ($servicesId) {
                $query->whereIn('service_id', $servicesId); // Filtrar servicios por ID
            });
        }
        
        // Filtro por estado
        if (!empty($request->searchStatus)) {
            // dd($request->start_time);
            $searchStatus = '%' . $request->searchStatus . '%';
            $statusId = OrderStatus::where('name', 'LIKE', $searchStatus)->pluck('id');
            $query->whereIn('status_id', $statusId);
        }

        
        $orders = $query->orderBy('id', 'desc')->paginate($this->size);
        
        
        if($orders->isEmpty())
        {
            $orders = Order::orderBy('id', 'desc')->paginate($this->size);
            return view('order.index', compact('orders'))/*->with('message', 'No se encontraron ordenes para los filtros aplicados.')*/;
        }

        $orders->appends([
            'searchCustomer' => $request->searchCustomer,
            'start_time' => $request->start_time,
            'programmed_date' => $request->programmed_date,
            'searchService' => $request->searchService,
            'searchStatus' => $request->searchStatus,
        ]);

		return view(
			'order.index',
			compact(
				'orders',
			)
		);
    }
}
