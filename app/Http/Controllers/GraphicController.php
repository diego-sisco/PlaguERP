<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;
use App\Charts\SampleChart;
use App\Models\Order;

use Carbon\Carbon;

class GraphicController extends Controller
{
    private $colors = ['#039BE5', '#1A237E'];

    // Graficas de nuevo clientes
    public function newCustomers()
    {
        $labels = ['Domesticos', 'Comerciales'];
        $api = url(route('crm.chart.customers'));

        $chart = new SampleChart;
        $chart->labels($labels)->load($api);

        return $chart;
    }

    public function newCustomersDataset()
    {
        $month = Carbon::now()->month;

        $counts = [];
        $domestics = Customer::whereMonth('created_at', $month)->where('service_type_id', 1)->where('general_sedes', 0)->count();
        $comercials = Customer::whereMonth('created_at', $month)->where('service_type_id', 2)->where('general_sedes', 0)->count();

        $counts = [$domestics, $comercials];

        $chart = new SampleChart;
        $chart->dataset('New Customers', 'doughnut', $counts)->backgroundColor($this->colors)->color($this->colors);

        return $chart->api();
    }

    public function refreshNewCustomers(Request $request)
    {
        $month = $request->input('month');
        $counts = [];
        $domestics = Customer::whereMonth('created_at', $month)->where('service_type_id', 1)->where('general_sedes', 0)->count();
        $comercials = Customer::whereMonth('created_at', $month)->where('service_type_id', 2)->where('general_sedes', 0)->count();

        $counts = [$domestics, $comercials];
        $chart = new SampleChart;
        $chart->dataset('New Customers', 'doughnut', $counts)->backgroundColor($this->colors)->color($this->colors);

        return $chart->api();
    }

    // Graficas de ordenes o clientes agendados
    public function orders() {
        $labels = ['Domesticos', 'Comerciales'];
        $api = url(route('crm.chart.orders'));

        $chart = new SampleChart;
        $chart->labels($labels)->load($api);

        return $chart;
    }

    public function ordersDataset() {
        $month = Carbon::now()->month;
        $counts = [0, 0];        
        $orders = Order::whereMonth('programmed_date', $month)->get();
        foreach($orders as $order) {
            if($order->customer->service_type_id == 1) {
                $counts[0]++;
            }
            if($order->customer->service_type_id == 2) {
                $counts[1]++;
            }
        }

        $chart = new SampleChart;
        $chart->dataset('Scheduled Orders', 'doughnut', $counts)->backgroundColor($this->colors)->color($this->colors);

        return $chart->api();
    }

    public function refreshOrders(Request $request) {
        $month = $request->input('month');
        $counts = [];        
        $orders = Order::whereMonth('programmed_date', $month)->get();
        foreach($orders as $order) {
            if($order->customer->service_type_id == 1) {
                $counts[0]++;
            }
            if($order->customer->service_type_id == 2) {
                $counts[1]++;
            }
        }

        $chart = new SampleChart;
        $chart->dataset('Scheduled Orders', 'doughnut', $counts)->backgroundColor($this->colors)->color($this->colors);

        return $chart->api();
    }



    // Obtiene la diferencia de servicios agendados respecto de los clientes agregdaos
    // Si recibe 1 entonces es domestico, si recibe 2 es comercial
    public function orderTypes($service_type) {
        $labels = ['Agendados', 'Totales'];
        $api = url(route('crm.chart.ordertypes', ['service_type' => $service_type]));
        $chart = new SampleChart;
        $chart->labels($labels)->load($api);

        return $chart;
    }

    public function orderTypesDataset($service_type) {
        $month = Carbon::now()->month;
        $counts = [0, 0];
        $customerIds = [];

        
        $orders = Order::whereMonth('programmed_date', $month)->get();
        $customers = Customer::whereMonth('created_at', $month)->where('service_type_id', $service_type)->where('general_sedes', 0)->count();

        foreach($orders as $order) {
            if($order->customer->service_type_id == $service_type) {
                $customerIds[] = $order->customer_id;
            }
        }

        $counts[0] = count(array_unique($customerIds));
        $counts[1] = $customers;

        $chart = new SampleChart;
        $chart->dataset('Clientes', 'bar', $counts)->backgroundColor($this->colors)->color($this->colors);

        return $chart->api();
    }

    public function refreshOrderTypes(Request $request, $service_type) {
        $month = $request->input('month');

        $counts = [0, 0];
        $customerIds = [];

        
        $orders = Order::whereMonth('programmed_date', $month)->get();
        $customers = Customer::whereMonth('created_at', $month)->where('service_type_id', $service_type)->where('general_sedes', 0)->count();

        foreach($orders as $order) {
            if($order->customer->service_type_id == $service_type) {
                $customerIds[] = $order->customer_id;
            }
        }

        $counts[0] = count(array_unique($customerIds));
        $counts[1] = $customers;

        $chart = new SampleChart;
        $chart->dataset('Clientes', 'bar', $counts)->backgroundColor($this->colors)->color($this->colors);

        return $chart->api();
    }
}
