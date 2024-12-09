<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class QualityController extends Controller
{
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
