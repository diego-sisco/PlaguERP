<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\CompanyCategory;
use App\Models\CustomerReference;
use App\Models\ServiceType;
use App\Models\Reference_type;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Service;
use App\Models\Floortype;
use App\Models\CustomerFile;
use App\Models\ApplicationArea;
use App\Models\TaxRegime;
use App\Models\CustomerProperties;
use App\Models\Filenames;
use App\Models\Properties;
use App\Models\ZoneType;
use App\Models\Directory;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use PhpParser\Builder\Property;

use function PHPUnit\Framework\directoryExists;

class CustomerController extends Controller
{

    private $files_path = 'customers/files/';
    private $cities_route = 'datas/json/Mexico_cities.json';
    private $states_route = 'datas/json/Mexico_states.json';
    private $size = 50;

    /*private function createDirectory($customer, $directoryId)
    {
        $directory = Directory::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $customer->id,
            'root_id' => $directoryId,
            'father_id' => $directoryId,
            'name' => $customer->name,
            'created_at' => now(),
        ]);

        return $directory;
    }*/

    public function index(string $type): View
    {
        $customers = $type == 0 ? Lead::where('status', '!=', 0)->orderBy('id', 'desc')->paginate($this->size)
            : ($type == 1
                ? Customer::where('general_sedes', 0)->where('status', '!=', 0)->orderBy('id', 'desc')->paginate($this->size)
                : Customer::where('general_sedes', '!=', 0)->where('status', '!=', 0)->orderBy('id', 'desc')->paginate($this->size));

        return view('customer.index', compact(
            'customers',
            'type',
        ));
    }

    public function create(string $id, string $type): View
    {
        $customer = $id != 0 ? $customer = Customer::find($id) : null;
        $companies = Company::all();
        $categs = CompanyCategory::all();
        $branch = Branch::all();
        $services = ServiceType::all();
        $branch = Branch::all();
        $states = json_decode(file_get_contents(public_path($this->states_route)), true);
        $cities = json_decode(file_get_contents(public_path($this->cities_route)), true);


        return view('customer.create')->with(compact('customer', 'companies', 'branch', 'type', 'categs', 'services', 'states', 'cities'));
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
                if($device->product_id)
                    $products++;
            }
        }

        foreach($customer->contracts as $contract) {
            $endDate = Carbon::parse($contract->enddate);
            
            if ($endDate->isBetween(Carbon::now(), Carbon::now()->addDays(31))) {
                $pendingCount++;

                $customerPending[$pendingCount] = [
                    'id' => $contract->id,
                    'content' => 'El contrato con id "'. $contract->id .  '" esta apunto de expirar.',
                    'date' => $contract->enddate,
                    'type' => 'contract'
                ];
            }
        }

        foreach($customer->ordersPending as $order) {
            $programmed_date = Carbon::parse($order->programmed_date);
            if ($programmed_date->isBetween(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek(), null, '[]')) {
                $pendingCount++;
                $servicesNames = [];
                foreach ($order->services as $service) {
                    $servicesNames[] = $service->name;
                }

                $customerPending[$pendingCount] = [
                    'id' => $order->id,
                    'content' => 'La orden de servicio con id "' . $order->id . '" con los servicios "' . implode(', ', $servicesNames) . '", esta programada para esta semana.',
                    'date' => $order->programmed_date,
                    'type' => 'order'
                ];
            }
        }

        foreach($customer->files as $file) {
            $expirated_date = Carbon::parse($file->expirated_at);
            if ($expirated_date->isBetween(Carbon::now(), Carbon::now()->addDays(31), null, '[]')) {
                $pendingCount++;

                $customerPending[$pendingCount] = [
                    'id' => $file->id,
                    'content' => 'El Documento "'. $file->filename->name . '" esta apunto de expirar.',
                    'date' => $file->expirated_at,
                    'type' => 'file'
                ];
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
            'products' => $products,
            'customerFile' => $customer->files->where('path','!=', NULL)->count(),
            'pendings' => $customerPending,
        ];

        $states = file_get_contents(public_path($this->states_route));
        $cities = file_get_contents(public_path($this->cities_route));
        $states = json_decode($states, true);
        $cities = json_decode($cities, true);

        return view('customer.show.details', compact('customer', 'customerData', 'companies', 'companyCategories', 'services', 'branches', 'tax_regimes', 'referenceTypes', 'floorTypes'));
    }

    public function createReference(string $id, string $type): View
    {
        $states = file_get_contents(public_path($this->states_route));
        $cities = file_get_contents(public_path($this->cities_route));
        $states = json_decode($states, true);
        $cities = json_decode($cities, true);
        $reference_types = Reference_type::all();
        return view('customer.create.reference', compact('reference_types', 'id', 'type', 'states', 'cities'));
    }

    public function editReference(string $id, string $type): View
    {
        $states = file_get_contents(public_path($this->states_route));
        $cities = file_get_contents(public_path($this->cities_route));
        $states = json_decode($states, true);
        $cities = json_decode($cities, true);
        $reference_types = Reference_type::all();
        $reference = CustomerReference::find($id);

        return view('customer.edit.reference.references', compact('reference', 'reference_types', 'id', 'type', 'states', 'cities'));
    }

    public function storeReference(Request $request, string $customerId)
    {
        $reference = new CustomerReference();
        $reference->fill($request->all());
        $reference->customer_id = $customerId;
        $reference->save();

        return back();
    }

    public function updateReference(Request $request, string $id)
    {
        $reference = CustomerReference::find($id); // Obtener la referencia por su ID
        $reference->fill($request->all());
        $reference->save();
        return back();
    }

    public function destroyReference(string $id)
    {
        try {
            $reference = CustomerReference::findOrFail($id);
            $reference->delete();
            return back();
        } catch (\Exception $e) {
            return back();
        }
    }

    public function store(Request $request, string $id, string $type): RedirectResponse
    {
        // Inicializar una variable para registrar los cambios
        $changes = '';

        // Recuperar los archivos relacionados con el tipo 'customer'
        $files = Filenames::where('type', 'customer')->get();

        $propsDefault = [2, 3, 4, 5];

        // Verificar y actualizar el estado del cliente potencial
        if (!empty($request->input('lead_to_customer_id'))) {
            $leadCustomerId = $request->input('lead_to_customer_id');
            Lead::where('id', $leadCustomerId)->update(['status' => 0]);
            $changes .= 'Actualizado estado de Cliente potencial,  ';
        }

        // Crear nuevo cliente o Lead_Customer según el tipo
        if ($type != 0) {
            $customer = new Customer();
            $customer->general_sedes = $type == 2 ? $id : 0;
            $customer->blueprints = $request->input('service_type_id') == 3 ? 1 : 0;
            $customer->print_doc = $request->input('service_type_id') == 3 ? 1 : 0;
            $customer->validate_certificate = $request->input('service_type_id') == 3 ? 1 : 0;
            $changes .= 'Creado ';
        } else {
            $customer = new Lead();
            $changes .= 'Creado ';
        }

        // Rellenar el cliente con los datos de la solicitud
        $customer->fill($request->all());
        $customer->status = 1;
        $customer->save();

        // Manejo de archivos y propiedades
        if ($type == 2) {
            foreach ($files as $file) {
                CustomerFile::insert([
                    'customer_id' => $customer->id,
                    'filename_id' => $file->id,
                ]);
            }
        }

        if ($type == 1 && $customer->service_type_id != 1) {
            CustomerProperties::insert([
                'customer_id' => $customer->id,
                'property_id' => 1,
            ]);
            $new_customer = new Customer();
            $new_customer->fill($request->all());
            $new_customer->name = $request->name . ' ' . $request->city;
            $new_customer->general_sedes = $customer->id;
            $new_customer->status = 1;
            $new_customer->save();

            foreach ($files as $file) {
                CustomerFile::insert([
                    'customer_id' => $new_customer->id,
                    'filename_id' => $file->id,
                ]);
            }

            foreach ($propsDefault as $prop) {
                CustomerProperties::insert([
                    'customer_id' => $new_customer->id,
                    'property_id' => $prop,
                ]);
            }

            $changes .= 'Creado nuevo Customer con ID: ' . $new_customer->id . '; ';
        }

        if ($type == 2) {
            foreach ($files as $file) {
                CustomerFile::insert([
                    'customer_id' => $customer->id,
                    'filename_id' => $file->id,
                ]);
            }

            foreach ($propsDefault as $prop) {
                CustomerProperties::insert([
                    'customer_id' => $customer->id,
                    'property_id' => $prop,
                ]);
            }
        }

        $sql = 'INSERT_CUSTOMER_' . $customer->id;
        PagesController::log('store', $changes, $sql);

        if ($id != 0) {
            return redirect()->route('customer.edit', ['id' => $id, 'type' => $type, 'section' => 4]);
        } else {
            return redirect()->route('customer.index', ['type' => $type, 'page' => 1]);
        }
    }

    public function storeArea(Request $request, string $customerId)
    {

        $area = new ApplicationArea();
        $area->fill($request->all());
        $area->customer_id = $customerId;
        $area->save();

        return back();
    }

    public function show(string $id, int $type, int $section): View
    {
        $client = $floortype = $prope = $actibableprop = $actibableprop = $defaultinac = $defaultprop = $activeprop = $sedes = $reference_types = $refs = $customer_file = $zones = $floorplans = null;

        $companies = Company::all();
        $companyCategories = CompanyCategory::all();
        $services = ServiceType::all();
        $branches = Branch::all();
        $tax_regimes = TaxRegime::all();
        $referenceTypes = Reference_type::all();
        $floorTypes = FloorType::all();

        $customer = $type != 0 ? Customer::find($id) : Lead::find($id);

        $states = file_get_contents(public_path($this->states_route));
        $cities = file_get_contents(public_path($this->cities_route));
        $states = json_decode($states, true);
        $cities = json_decode($cities, true);

        return view('customer.show', compact('customer', 'companies', 'companyCategories', 'services', 'branches', 'tax_regimes', 'referenceTypes', 'floorTypes', 'type', 'section'));
    }

    public function edit(string $id, string $type, string $section)
    {
        $tax_regimes = TaxRegime::all();
        $categs = CompanyCategory::all();
        $serviceTypes = ServiceType::all();
        $services = Service::all();
        $branches = Branch::all();
        $floortype = Floortype::all();
        $reference_types = Reference_type::all();
        $zone_types = ZoneType::all();
        $states = json_decode(file_get_contents(public_path($this->states_route)), true);
        $cities = json_decode(file_get_contents(public_path($this->cities_route)), true);

        $customer = $type != 0 ? Customer::find($id) : Lead::find($id);

        if ($customer) {
            if ($customer->general_sedes == null) {
                $properties = [Properties::find(1)];
            } else {
                $properties = Properties::whereNot('id', 1)->get();
            }
        } else {
            // Manejar el caso en que no se encuentra el cliente o lead
            // Puedes redirigir o mostrar un mensaje de error
            return redirect()->route('customer.list')->with('error', 'Cliente o Lead no encontrado.');
        }

        return view(
            'customer.edit',
            compact(
                'zone_types',
                'tax_regimes',
                'customer',
                'branches',
                'categs',
                'reference_types',
                'services',
                'serviceTypes',
                'states',
                'cities',
                'properties',
                'type',
                'section',
            )
        );
    }

    public function update(Request $request, string $id, string $type): RedirectResponse
    {
        $properties = json_decode($request->input('selected_properties'), true) ?: [];
        $customer = $type != 0 ? Customer::find($id) : Lead::find($id);
        // Inicializar una variable para registrar los cambios
        $changes = '';

        // Recuperar el cliente o lead según el tipo
        $customer = $type != 0 ? Customer::find($id) : Lead::find($id);

        // Verificar si el cliente o lead existe
        if (!$customer) {
            return back();
        }

        // Rellenar el cliente o lead con los datos de la solicitud y guardar
        $customer->fill($request->all());
        $customer->save();
        $changes .= 'Datos actualizados ';

        // Solo manejar propiedades si es un Customer
        if ($type != 0) {
            $properties = json_decode($request->input('selected_properties'), true) ?? [];
            $customerPropertiesIds = $customer->properties->pluck('id')->toArray();

            $selected = array_diff($properties, $customerPropertiesIds);
            $unselected = array_diff($customerPropertiesIds, $properties);

            // Eliminar propiedades no seleccionadas
            foreach ($unselected as $propID) {
                CustomerProperties::where('customer_id', $id)->where('property_id', $propID)->delete();
            }

            // Añadir propiedades nuevas seleccionadas
            foreach ($selected as $propID) {
                CustomerProperties::updateOrCreate(
                    ['customer_id' => $id, 'property_id' => $propID],
                    ['customer_id' => $id, 'property_id' => $propID]
                );
            }
        }

        $sql = 'UPDATE_CUSTOMER_' . $customer->id;
        PagesController::log('update', $changes, $sql);

        return back();
    }

    public function updateArea(Request $request, string $id)
    {
        $area = ApplicationArea::find($id);
        $area->fill($request->all());
        $area->save();

        return back();
    }

    public function search(Request $request, int $type)
    {
        if (empty($request->search)) {
            return redirect()->back()->with('error', trans('messages.no_results_found'));
        }

        $searchTerm = '%' . $request->search . '%';
        if ($type == 0) {
            // Cliente Potencial
            $customers = Lead::where('status', '!=', 0)
                ->where('name', 'LIKE', $searchTerm)
                ->orWhere('email', 'LIKE', $searchTerm)
                ->orWhere('phone', 'LIKE', $searchTerm)
                ->orderBy('id', 'asc')
                ->paginate($this->size);
        } else if ($type == 1) {
            // Cliente
            $customers = Customer::where('general_sedes', 0)
                ->where('name', 'LIKE', $searchTerm)
                ->orWhere('email', 'LIKE', $searchTerm)
                ->orWhere('phone', 'LIKE', $searchTerm)
                ->where('status', '!=', 0)
                ->orderBy('id', 'asc')
                ->paginate($this->size);
        } else if ($type == 2) {
            // Sedes
            $customers = Customer::where('general_sedes', '!=', 0)
                ->where('service_type_id', '=', 3)
                ->where('name', 'LIKE', $searchTerm)
                ->orWhere('email', 'LIKE', $searchTerm)
                ->orWhere('phone', 'LIKE', $searchTerm)
                ->where('status', '!=', 0)
                ->orderBy('id', 'asc')
                ->paginate($this->size);
        }

        return view('customer.index', compact('customers','type'));
    }

    public function store_file(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,pdf|max:5120'
        ]);

        $disk = Storage::disk('public');
        $customer_file = CustomerFile::find($request->file_id);
        $file = $request->file('file');

        // Verifica si hay un archivo existente y lo elimina si es así
        if (!is_null($customer_file->path)) {
            Storage::disk('public')->delete($customer_file->path);
        }

        $newFilename = $customer_file->filename()->pluck('name')[0] . '_cliente' . $customer_file->customer_id . '.' . $request->file('file')->getClientOriginalExtension();

        $url = $this->files_path . $newFilename;

        $disk->put($url, file_get_contents($file));

        $customer_file->path = $url;
        $customer_file->expirated_at = $request->expirated_date;
        $customer_file->save();

        return back();
    }

    public function download_file($id)
    {

        try {
            $customer_file = CustomerFile::find($id);

            if (Storage::disk('public')->exists($customer_file->path)) {
                return Storage::disk('public')->download($customer_file->path);
            }
            return response()->json(['error' => 'File not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while downloading the file.'], 500);
        }
    }

    public function destroy(string $id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        return redirect()->route('customer.index');
    }

    public function destroyArea(string $id)
    {
        ApplicationArea::find($id)->delete();
        return back();
    }

    public function convert(int $id)
    {
        $lead = Lead::find($id);
        if ($lead) {
            $data = $lead->toArray();
            unset($data['id']);
            unset($data['reason']);
            unset($data['tracking_at']);
            $customer = new Customer($data);
            $customer->general_sedes = 0;
            $customer->save();

            $lead->delete();
        }
        return redirect()->route('customer.index', ['type' => 1, 'page' => 1]);
    }

    public function tracking(int $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->tracking_at = Carbon::now()->toDateString();
        $lead->save();
        return back();
    }
}
