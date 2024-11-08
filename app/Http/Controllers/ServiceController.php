<?php

namespace App\Http\Controllers;

use App\Models\PestCatalog;
use App\Models\PestCategory;
use App\Models\PestService;
use App\Models\ApplicationMethod;
use App\Models\ApplicationMethodService;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\ServiceStatus;
use App\Models\LineBusiness;
use App\Models\ServicePrefix;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class ServiceController extends Controller
{

    private $size = 50;

    public function index(): View
    {
        $services = Service::orderBy('id', 'desc')->paginate($this->size);

        return view(
            'service.index',
            compact(
                'services',
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $service_types = ServiceType::all();
        $pest_categories = PestCategory::orderBy('category', 'asc')->get();
        $application_methods = ApplicationMethod::orderBy('name', 'asc')->get();
        $business_lines = LineBusiness::all();
        $prefixes = ServicePrefix::all();

        return view(
            'service.create',
            compact(
                'pest_categories',
                'service_types',
                'application_methods',
                'business_lines',
                'prefixes',
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //: RedirectResponse
    {
        $pestSelected = json_decode($request->input('pestSelected'), true);
        $appMethodSelected = json_decode($request->input('appMethodSelected'), true);

        $service = new Service();
        $service->fill($request->all());
        $service->status_id = 1;
        $service->has_pests = !empty($pestSelected);
        $service->has_application_methods = !empty($appMethodSelected);
        $service->save();

        $changes = 'Creación de nuevo servicio; ';

        if (!empty($pestSelected)) {
            foreach ($pestSelected as $pestID) {
                PestService::insert([
                    'service_id' => $service->id,
                    'pest_id' => $pestID,
                ]);
            }
        }

        if (!empty($appMethodSelected)) {
            foreach ($appMethodSelected as $appID) {
                ApplicationMethodService::insert([
                    'service_id' => $service->id,
                    'application_method_id' => $appID,
                ]);
            }
        }

        $sql = 'INSERT_SERVICE_' . $service->id;
        PagesController::log('store', $changes, $sql);

        return redirect()->route('service.index', ['page' => 1]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, string $section)
    {
        $service = Service::find($id);

        return view(
            'service.show',
            compact(
                'service',
                'section',
            )
        );
    }

    public function search(Request $request)
    {
        if (empty($request->search)) {
            session()->flash('error', trans('messages.no_results_found'));
            return back();
        }

        $searchTerm = '%' . $request->search . '%';

        $services = Service::where('name', 'LIKE', $searchTerm)
            ->orderBy('id', 'desc')
            ->paginate($this->size);

        if ($services->isEmpty()) {
            $services = Service::orderBy('id', 'desc')
                ->paginate($this->size);
        }

        return view(
            'service.index',
            compact(
                'services',
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $service = Service::find($id);
        $service_types = ServiceType::all();
        $service_status = ServiceStatus::all();
        $business_lines = LineBusiness::all();
        $pest_categories = PestCategory::orderBy('category', 'asc')->get();
        $application_methods = ApplicationMethod::orderBy('name', 'asc')->get();
        $pest_services = PestService::where('service_id', $id)->get();
        $app_methods_service = ApplicationMethodService::where('service_id', $id)->get();
        $prefixes = ServicePrefix::all();
        $error = null;
        $warning = null;
        $success = null;

        return view(
            'service.edit',
            compact(
                'service',
                'pest_categories',
                'service_types',
                'service_status',
                'business_lines',
                'application_methods',
                'pest_services',
                'app_methods_service',
                'prefixes',
                'error',
                'warning',
                'success'
            )
        );
    }
    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id): RedirectResponse
    {
        $pestSelected = json_decode($request->input('pestSelected'), true);
        $appMethodSelected = json_decode($request->input('appMethodSelected'), true);

        $service = Service::find($id);
        $service->fill($request->all());

        $changes = '';

        // Comparar campos específicos antes de guardar
        $originalService = $service->getOriginal();
        if ($originalService['name'] != $request->input('name')) {
            $changes .= 'Cambio de nombre; ';
        }
        if ($originalService['description'] != $request->input('description')) {
            $changes .= 'Cambio de descripción; ';
        }
        if ($originalService['status_id'] != $request->input('status_id')) {
            $changes .= 'Cambio de estado; ';
        }

        if (!empty($pestSelected)) {
            $service->has_pests = !empty($pestSelected);
            $pestServices = PestService::where('service_id', $id)->pluck('pest_id')->toArray();
            $pestsToDelete = array_diff($pestServices, $pestSelected);
            if (!empty($pestsToDelete)) {
                PestService::where('service_id', $id)->whereIn('pest_id', $pestsToDelete)->delete();
                $changes .= 'Plagas eliminadas: ' . implode(', ', $pestsToDelete) . '; ';
            }
            foreach ($pestSelected as $pestId) {
                PestService::updateOrCreate(
                    ['service_id' => $id, 'pest_id' => $pestId],
                    ['service_id' => $id, 'pest_id' => $pestId]
                );
                $changes .= "Plaga ID $pestId añadida o actualizada; ";
            }
        }

        if (!empty($appMethodSelected)) {
            $service->has_application_methods = !empty($appMethodSelected);
            $appMethodServices = ApplicationMethodService::where('service_id', $id)->pluck('application_method_id')->toArray();
            $appMethodsToDelete = array_diff($appMethodServices, $appMethodSelected);
            if (!empty($appMethodsToDelete)) {
                ApplicationMethodService::where('service_id', $id)->whereIn('application_method_id', $appMethodsToDelete)->delete();
                $changes .= 'Métodos de aplicación eliminados: ' . implode(', ', $appMethodsToDelete) . '; ';
            }
            foreach ($appMethodSelected as $appMethodId) {
                ApplicationMethodService::updateOrCreate(
                    ['service_id' => $id, 'application_method_id' => $appMethodId],
                    ['service_id' => $id, 'application_method_id' => $appMethodId]
                );
                $changes .= "Método de aplicación ID $appMethodId añadido o actualizado; ";
            }
        }

        $service->save();

        $sql = 'UPDATE_SERVICE_' . $service->id;
        PagesController::log('update', $changes, $sql);

        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ApplicationMethodService::where('service_id', $id)->delete();
        PestService::where('service_id', $id)->delete();
        Service::destroy($id);

        return back();
    }
}
