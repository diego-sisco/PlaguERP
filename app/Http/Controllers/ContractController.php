<?php

namespace App\Http\Controllers;

use App\Models\OrderService;
use App\Models\OrderStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\ExecFrequency;
use App\Models\ApplicationMethod;
use App\Models\PestCategory;
use App\Models\Day;
use App\Models\Technician;
use App\Models\ContractTechnician;
use App\Models\Order;
use App\Models\OrderTechnician;
use App\Models\ContractService;
use App\Models\Administrative;
use App\Models\Contract_File;
use App\Models\Service;

use Carbon\Carbon;

class ContractController extends Controller
{
    private static $files_path = 'files/';

    private $mip_directories = [
        'MIP',
        'Contrato de servicio',
        'Justificación',
        'Datos de la empresa',
        'Certificación MIP',
        'Plano de ubicación de dispositivos',
        'Responsabilidades',
        'Plago objeto',
        'Calendarización de actividades',
        'Descripción de actividades POEs',
        'Métodos preventivos',
        'Métodos correctivos',
        'Información de plaguicidas',
        'Reportes',
        'Gráficas de tendencias',
        'Señaléticas',
        'Pago seguro'
    ];

    private $intervals = [
        'Por día',
        'Primera semana',
        'Segunda semana',
        'Tercera semana',
        'Ultima semana',
        'Quincenal'
    ];

    private $size = 50;

    public function index(): View
    {
        $contracts = Contract::orderBy('id', 'desc')->paginate($this->size);
        $technicians = Technician::all();

        return view(
            'contract.index',
            compact(
                'contracts',
                'technicians',
            )
        );
    }

    public function create(): View
    {
        $technicians = Technician::all();
        $exec_frecuencies = ExecFrequency::all();
        $contracts = Contract::all();
        $intervals = $this->intervals;

        return view(
            'contract.create',
            compact(
                'exec_frecuencies',
                //'application_methods',
                //'pest_categories',
                'technicians',
                //'days',
                'contracts',
                'intervals'
            )
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $contract_data = json_decode($request->input('contract_data'));
        $selected_technicians = json_decode($request->input('technicians'));
        //$contract_id = $request->input('contract_id');
        //dd($contract_data);

        //if ($contract_id == 0) {
        $contract = Contract::create([
            'customer_id' => $request->input('customer_id'),
            'user_id' => auth()->user()->id,
            'startdate' => $request->input('startdate'),
            'enddate' => $request->input('enddate'),
            'status' => 1,
            'file' => null,
        ]);
        /*} else {
            $contract = Contract::find($contract_id);
        }*/

        if ($selected_technicians[count($selected_technicians) - 1] == 0) {
            $technicians = Technician::all();
            foreach ($technicians as $tech) {
                ContractTechnician::insert([
                    'technician_id' => $tech->id,
                    'contract_id' => $contract->id,
                ]);
            }
        } else {
            foreach ($selected_technicians as $id) {
                ContractTechnician::insert([
                    'technician_id' => $id,
                    'contract_id' => $contract->id,
                ]);
            }
        }

        foreach ($contract_data as $data) {
            foreach ($data->settings as $setting) {
                $contract_service = ContractService::create([
                    'contract_id' => $contract->id,
                    'service_id' => $data->id,
                    'execution_frequency_id' => $setting->frequency,
                    'interval' => $setting->interval,
                    'days' => json_encode($setting->days),
                    'total' => count($setting->dates),
                    'created_at' => now(),
                ]);

                foreach ($setting->dates as $date) {
                    if (isset($date)) {
                        $order = Order::create([
                            'administrative_id' => Administrative::where('user_id', auth()->user()->id)->first()->id,
                            'customer_id' => $contract->customer_id,
                            'contract_id' => $contract->id,
                            'setting_id' => $contract_service->id,
                            'status_id' => '1',
                            'start_time' => '00:00',
                            'programmed_date' => $date,
                        ]);
                        OrderService::insert([
                            'order_id' => $order->id,
                            'service_id' => $data->id,
                        ]);
                        if ($selected_technicians[count($selected_technicians) - 1] == 0) {
                            $technicians = Technician::all();
                            foreach ($technicians as $tech) {
                                OrderTechnician::insert([
                                    'technician_id' => $tech->id,
                                    'order_id' => $order->id,
                                ]);
                            }
                        } else {
                            foreach ($selected_technicians as $id) {
                                OrderTechnician::insert([
                                    'technician_id' => $id,
                                    'order_id' => $order->id,
                                ]);
                            }
                        }
                    }
                }

            }
        }
        return redirect()->route('contract.index');
    }

    public function search(Request $request)
    {

        if (empty($request->search)) {
            return redirect()->back()->with('error', trans('messages.no_results_found'));
        }

        $searchTerm = '%' . $request->search . '%';

        $customerIds = Customer::where('name', 'LIKE', $searchTerm)->get()->pluck('id');

        $contracts = Contract::whereIn('customer_id', $customerIds)
            ->orWhereDate('startdate', 'LIKE', $searchTerm)
            ->orWhereDate('enddate', 'LIKE', $searchTerm)
            ->orderBy('id', 'desc')
            ->paginate($this->size);

        if ($contracts->isEmpty()) {
            session()->flash('error', trans('messages.no_results_found'));
            $contracts = Contract::orderBy('id', 'desc')->paginate($this->size);
        }

        $technicians = Technician::all();

        return view(
            'contract.index',
            compact(
                'contracts',
                'technicians'
            )
        );
    }

    public function searchOrders(Request $request, string $id, string $customerId)
    {

        $contract = Contract::find($id);
        $customer = Customer::find($customerId);
        $date = $request->input('date');
        $time = $request->input('time');
        $service = $request->input('service');
        $status = $request->input('status');

        $orders = Order::where('customer_id', $customer->id)->where('status_id', $status);

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
            'contract.show',
            compact(
                'orders',
                'order_status',
                'customer',
                'contract',
                'size'
            )
        );
    }

    public function show(string $id, int $section)
    {
        $contract = Contract::find($id);
        $order_status = OrderStatus::all();
        $orders = Order::where('contract_id', $contract->id)->paginate($this->size);
        $customer = $contract->customer();
        return view('contract.show', compact('contract', 'orders', 'order_status', 'customer', 'section'));
    }

    public function getSelectedTechnicians(Request $request)
    {
        $technicians = Technician::all();
        $technicianIds = ContractTechnician::where('contract_id', $request->contractId)->get();
        $technicianSelected = empty(array_diff($technicians->pluck('id')->toArray(), $technicianIds->pluck('technician_id')->toArray())) ? [0] : $technicianIds->pluck('technician_id')->toArray();

        return response()->json([
            'technicians' => $technicians->pluck('id')->toArray(),
            'technicianSelected' => $technicianSelected,
        ]);
    }
    public function updateTechnicians(Request $request, int $id)
    {
        $ot_array = json_decode($request->input('technicians'));
        $contract = Contract::find($id);

        if (empty($ot_array)) {
            return redirect()->back();
        }

        $technicians = ContractTechnician::where('contract_id', $id)->pluck('technician_id')->toArray();

        if ($ot_array[count($ot_array) - 1] == 0) {
            $existingTechnicians = Technician::pluck('id')->toArray();
            $techniciansInsert = array_diff($existingTechnicians, $technicians);
        } else {
            $techniciansInsert = array_diff($ot_array, $technicians);
            $techniciansDelete = array_diff($technicians, $ot_array);

            foreach ($techniciansDelete as $technicianId) {
                ContractTechnician::where('contract_id', $id)
                    ->where('technician_i  d', $technicianId)
                    ->delete();
            }
        }

        foreach ($techniciansInsert as $technicianId) {
            ContractTechnician::updateOrCreate(
                ['contract_id' => $id, 'technician_id' => $technicianId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $orders = $contract->orders;
        $orderIds = $orders->pluck('id')->toArray();
        $orderTechnicians = OrderTechnician::whereIn('order_id', $orderIds)->get();

        foreach ($orders as $order) {
            $newTechnicians = $order->contract->technicians()->pluck('technician.id')->toArray();
            $orderTechniciansToDelete = $orderTechnicians->where('order_id', $order->id)
                ->whereNotIn('technician_id', $newTechnicians);

            foreach ($orderTechniciansToDelete as $orderTechnicianToDelete) {
                $orderTechnicianToDelete->delete();
            }

            $techniciansToAdd = array_diff($newTechnicians, $orderTechnicians->pluck('technician_id')->toArray());

            foreach ($techniciansToAdd as $technicianToAdd) {
                OrderTechnician::create([
                    'order_id' => $order->id,
                    'technician_id' => $technicianToAdd
                ]);
            }
        }

        return redirect()->back();
    }

    public function edit(string $id)
    {
        $orders = [];
        $contract = Contract::find($id);
        $contract_services = $contract->services()->get();
        $services = Service::whereIn('id', $contract_services->pluck('service_id'))->get();
        $contracts = Contract::where('customer_id', $contract->customer_id)->where('id', '!=', $contract->id)->get();
        $intervals = $this->intervals;
        dd($contract_services);
        foreach($contract_services as $contract_service) {
            $orders[] = [
                'service_id' => $contract_service->service_id,
                'frequency' => $contract_service->execution_frequency_id,
                'interval' => $contract_service->interval,
                'dates' => Order::where('setting_id', $contract_service->id)->where('contract_id', $id)->get()->pluck('programmed_date')
            ];
        }

        $technicians = Technician::all();
        $exec_frecuencies = ExecFrequency::all();

        return view(
            'contract.edit',
            compact(
                'contract',
                'services',
                'orders',
                'technicians',
                'exec_frecuencies',
                'contracts',
                'intervals'
            )
        );
    }

    public function update(Request $request, string $id)
    {
        $contract_data = json_decode($request->input('contract_data'));
        $selected_technicians = json_decode($request->input('technicians'));

        $contract = Contract::find($id);
        $contract->startdate = $request->input('startdate');
        $contract->enddate = $request->input('enddate');
        $contract->updated_at = now();
        $contract->save();

        //Eliminar los servicios ligados a la orden
        $serviceIds = array_map(function ($item) {
            return $item->id;
        }, $contract_data);

        $contract_services = ContractService::where('contract_id', $contract->id)->get();
        $contract_services_delete = array_diff($contract_services->pluck('service_id')->toArray(), $serviceIds);

        if ($contract_services_delete) {
            $order_services = OrderService::whereIn('service_id', $contract_services_delete)->get();
            ContractService::where('contract_id', $contract->id)->whereIn('service_id', $contract_services_delete)->delete();
            Order::where('contract_id', $contract->id)->whereIn('id', $order_services->pluck('order_id'))->delete();
        }

        // Control de técnicos
        $contract_technicians = ContractTechnician::where('contract_id', $contract->id)->get();
        if ($contract_technicians->isNotEmpty()) {
            $contract_technicians_delete = array_diff($contract_technicians->pluck('technician_id')->toArray(), $selected_technicians);
            $selected_technicians = array_diff($selected_technicians, $contract_technicians->pluck('technician_id')->toArray());
            ContractTechnician::where('contract_id', $contract->id)->whereIn('technician_id', $contract_technicians_delete)->delete();
            foreach ($selected_technicians as $id) {
                ContractTechnician::insert([
                    'technician_id' => $id,
                    'contract_id' => $contract->id,
                ]);
            }
        } else {
            foreach ($selected_technicians as $id) {
                ContractTechnician::insert([
                    'technician_id' => $id,
                    'contract_id' => $contract->id,
                ]);
            }
        }

        // Control de las ordenes de servicio
        foreach ($contract_data as $data) {
            $countOrders = 0;
            $orders = Order::where('contract_id', $contract->id)->get();
            $order_services = OrderService::whereIn('order_id', $orders->pluck('id'))->where('service_id', $data->id)->get();
            $orders = Order::whereIn('id', $order_services->pluck('order_id'))->get();
            $topeId = $orders->count();
            $topeDate = count($data->dates);

            if ($topeId > $topeDate && $topeDate != 0) {
                while ($topeId > $topeDate) {
                    Order::whereIn('id', $order_services->pluck('order_id'))->get()->last()->delete();
                    $orders = Order::whereIn('id', $order_services->pluck('order_id'))->get();
                    $topeId = $orders->count();
                }
            }

            foreach ($data->dates as $date) {
                if (isset($date)) {
                    if ($countOrders < $topeId) {
                        $order = Order::find($orders[$countOrders]->id);
                        $order->programmed_date = $date;
                        $order->save();
                    } else {
                        $order = Order::create([
                            'administrative_id' => Administrative::where('user_id', auth()->user()->id)->first()->id,
                            'customer_id' => $contract->customer_id,
                            'contract_id' => $contract->id,
                            'status_id' => '1',
                            'start_time' => '00:00',
                            'programmed_date' => $date,
                        ]);

                        OrderService::insert([
                            'order_id' => $order->id,
                            'service_id' => $data->id,
                        ]);
                    }
                    $countOrders++;
                }
            }

            $orders = Order::where('contract_id', $contract->id)->get();
            $contract_technicians = ContractTechnician::where('contract_id', $contract->id)->get();
            $order_technicians = OrderTechnician::whereIn('order_id', $orders->pluck('id'))->get();

            if ($order_technicians->isEmpty()) {
                foreach ($orders as $order) {
                    foreach ($contract_technicians->pluck('technician_id')->toArray() as $technician_id) {
                        OrderTechnician::insert([
                            'order_id' => $order->id,
                            'technician_id' => $technician_id
                        ]);
                    }
                }
            } else {
                $order_technicians_delete = array_unique(array_diff($order_technicians->pluck('technician_id')->toArray(), $contract_technicians->pluck('technician_id')->toArray()));
                $order_technicians_insert = array_unique(array_diff($contract_technicians->pluck('technician_id')->toArray(), $order_technicians->pluck('technician_id')->toArray()));
                OrderTechnician::whereIn('technician_id', $order_technicians_delete)->whereIn('order_id', $orders->pluck('id'))->delete();

                foreach ($orders as $order) {
                    foreach ($order_technicians_insert as $technician_id) {
                        OrderTechnician::insert([
                            'order_id' => $order->id,
                            'technician_id' => $technician_id
                        ]);
                    }
                }
            }

            $contract_service = ContractService::where('contract_id', $contract->id)->where('service_id', $data->id)->first();
            $contract_service->execution_frequency_id = $data->frequency;
            if ($countOrders > 0) {
                $contract_service->total = $countOrders;
            }
            $contract_service->updated_at = now();
            $contract_service->save();
        }

        return back();
    }

    public function destroy(Request $request, int $id)
    {
        $contract = Contract::find($id);
        if ($contract) {
            $orders = Order::where('contract_id', $id)->pluck('id');
            OrderService::whereIn('order_id', $orders)->delete();
            Order::where('contract_id', $id)->delete();
            ContractService::where('contract_id', $id)->delete();
            $contract->delete();
        }

        return redirect()->back();
    }

    private static function setFile($file, $id)
    {
        $file_name = $file->getClientOriginalExtension();
        $path = ContractController::$files_path . $id;
        $file->storeAs($path, $file_name);
        return $path . '/' . $file_name;
    }

    public function store_file(Request $request, string $customerID, int $type)
    {

        if ($request->file('file')) {
            $files = $request->file('file');
        } else {
            $error = 'No se encontraron los archivos';
        }
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $path = ContractController::setFile($files, $request->customer_id);
        $contractfile = new Contract_File();
        $contractfile->contract_id = $request->contract_id;
        $contractfile->path = $path;
        $contractfile->save();
        return redirect()->action([ContractController::class, 'index'], ['page' => 1]);
    }

    public function contract_downolad(string $id)
    {

        $file = Contract_File::find($id);
        if ($file) {
            return response()->download(storage_path('app/' . $file->path));
        }
    }
}
