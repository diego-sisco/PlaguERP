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
    private $size = 30;

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

    public function index(string $type, int $page): View
    {
        $customers = $type == 0 ? Lead::where('status', '!=', 0)->orderBy('id', 'desc')->get()
            : ($type == 1
                ? Customer::where('general_sedes', 0)->where('status', '!=', 0)->orderBy('id', 'desc')->get()
                : Customer::where('general_sedes', '!=', 0)->where('status', '!=', 0)->orderBy('id', 'desc')->get());

        $total = ceil($customers->count() / $this->size);
        $min = ($page * $this->size) - $this->size;

        $customers = collect(array_slice($customers->toArray(), $min, $this->size))->map(function ($customer) {
            return new Customer($customer);
        });

        return view('customer.index', compact(
            'customers',
            'type',
            'page',
            'total'
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

    public function storeReference(Request $request, string $id, string $type)
    {

        $reference = new CustomerReference();
        $reference->fill($request->all());
        $reference->customer_id = $id;
        $reference->save();
        return redirect()->route('customer.edit', ['id' => $id, 'type' => $type, 'section' => 5]);
    }

    public function showReference(string $id, string $type): View
    {
        $states = file_get_contents(public_path($this->states_route));
        $cities = file_get_contents(public_path($this->cities_route));
        $states = json_decode($states, true);
        $cities = json_decode($cities, true);
        $reference_types = Reference_type::all();
        $reference = CustomerReference::find($id);
        return view('customer.show.reference', compact('reference', 'reference_types', 'id', 'type', 'states', 'cities'));
    }

    public function updateReference(Request $request, string $id, string $type)
    {
        $reference = CustomerReference::find($id); // Obtener la referencia por su ID
        $reference->reference_type_id = $request->input('type');
        $reference->name = $request->input('name');
        $reference->phone = $request->input('phone');
        $reference->email = $request->input('email');
        $reference->department = $request->input('department');
        $reference->address = $request->input('address');
        $reference->zip_code = $request->input('zip_code');
        $reference->state = $request->input('state');
        $reference->city = $request->input('city');

        $reference->save();
        //success = "Se guardo correctamente la referencia";
        return redirect()->route('customer.edit', ['id' => $reference->customer_id, 'type' => 2, 'section' => 5]);
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

        return redirect()->back();
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
        $serviceTypes  = ServiceType::all();
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

        return redirect()->back();
    }

    public function updateArea(Request $request)
    {

        $area = ApplicationArea::find($request->input('area_id'));
        $area->fill($request->all());
        $area->save();

        return back();
    }

    public function search(Request $request, int $type, int $page)
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
                ->get();
        } else if ($type == 1) {
            // Cliente
            $customers = Customer::where('general_sedes', 0)
                ->where('name', 'LIKE', $searchTerm)
                ->orWhere('email', 'LIKE', $searchTerm)
                ->orWhere('phone', 'LIKE', $searchTerm)
                ->where('status', '!=', 0)
                ->orderBy('id', 'asc')
                ->get();
        } else if ($type == 2) {
            // Sedes
            $customers = Customer::where('general_sedes', '!=', 0)
                ->where('service_type_id', '=', 3)
                ->where('name', 'LIKE', $searchTerm)
                ->orWhere('email', 'LIKE', $searchTerm)
                ->orWhere('phone', 'LIKE', $searchTerm)
                ->where('status', '!=', 0)
                ->orderBy('id', 'asc')
                ->get();
        }

        $total = ceil($customers->count() / $this->size);
        $min = ($page * $this->size) - $this->size;

        $customers = collect(array_slice($customers->toArray(), $min, $this->size))->map(function ($customer) {
            return new Customer($customer);
        });

        return view('customer.index', compact('customers', 'page', 'total', 'type'));
    }

    public function store_file(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,pdf|max:5120'
        ]);

        $disk =  Storage::disk('public');
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
