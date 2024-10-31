<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Directory;
use App\Models\ClientFile;
use App\Models\Customer;
use App\Models\DirectoryUser;
use App\Models\LineBusiness;
use App\Models\MIPFile;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\Service;
use App\Models\UserCustomer;
use App\Models\User;

class ClientController extends Controller
{
    private $path = 'client_system/';
    private $mip_path = 'mip_directory/';
    private $reports_path = 'backups/reports/';
    private $dir_names = [];

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

    public function localClientSystemFormat($local_data)
    {
        $data = [];
        foreach ($local_data as $d) {
            $data[] = [
                'name' => basename($d),
                'path' => $d . '/',
            ];
        }
        return $data;
    }

    private function getPermissions($dirs)
    {
        $permissions = [];
        foreach ($dirs as $dir) {
            $permissions[] = [
                'dirId' => $dir->id,
                'users' => DirectoryUser::where('directory_id', $dir->id)->get()->pluck('user_id'),
            ];
        }
        return $permissions;
    }

    private function getBreadcrumb($path)
    {
        $breadcrump = [];
        $aux = '';
        $parts = explode('/', $path);
        foreach ($parts as $part) {
            $breadcrump[] = $aux . $part;
            $aux .= ($part . '/');
        }
        return $breadcrump;
    }

    private function flattenArray(array $array): array {
        return array_merge(...$array);
    }

    private function uniqueArray($items) {
        $uniqueItems = array_unique(
            array_map(
                function($item) {
                    return serialize($item);
                },
                $items
            )
        );

        $uniqueItems = array_map(
            function($item) {
                return unserialize($item);
            },
            $uniqueItems
        );

        return $uniqueItems;
    }

    private function filterFiles($id, $date, $filesArray)
    {
        $results = [];
        $date = str_replace("-", "", $date);
        foreach ($filesArray as $file) {
                $fileParts = explode('_', $file['name']);
                if (count($fileParts) == 3) {
                    $fileDate = $fileParts[0];
                    $fileId = explode('.', $fileParts[2])[0];
                    $dateMatches = ($date == null || $fileDate == $date);
                    $idMatches = ($id == null || $fileId == $id);

                    if ($dateMatches && $idMatches) {
                        $results[] = $file;
                    }
                }
            }
        
        return $results;
    }
    public function createMip(string $path)
    {
        foreach ($this->mip_directories as $name) {
            $folder_name = $path . '/' . $name;
            if (!Storage::disk('public')->exists($folder_name)) {
                Storage::disk('public')->makeDirectory($folder_name);
            }
        }
        return back();
    }

    public function index()
    {
        $path = $this->path;
        $mip_path = $this->mip_path;
        return view('client.index', compact('path', 'mip_path'));
    }

    public function directories(string $path)
    {
        $mip_dirs = $mip_files = [];
        $disk = Storage::disk('public');
        $dir_name = $this->mip_path . basename($path);
        $local_dirs = $disk->directories($path);
        $local_files = $disk->files($path);

        $links = $this->getBreadcrumb($path);
        $user = User::find(auth()->user()->id);

        if ($disk->exists($dir_name)) {
            $mip_dirs = $disk->directories($dir_name);
            $mip_files = $disk->files($dir_name);
        }

        $data = [
            'root_path' => $path,
            'directories' => $this->localClientSystemFormat($local_dirs),
            'files' => $this->localClientSystemFormat($local_files),
            'mip_directories' => $this->localClientSystemFormat($mip_dirs),
            'mip_files' => $this->localClientSystemFormat($mip_files)
        ];

        return view('client.directory.index', compact('data', 'links', 'user'));
    }

    public function reports(int $section)
    {
        $user = User::find(auth()->user()->id);
        $business_lines = LineBusiness::all();

        return view('client.report.index', compact('user', 'business_lines', 'section'));
    }

    public function mip(string $path)
    {
        $directories = $files = [];
        $local_dirs = Storage::disk('public')->directories($path);
        $local_files = Storage::disk('public')->files($path);

        $links = $this->getBreadcrumb($path);

        $data = [
            'root_path' => $path,
            'directories' => $this->localClientSystemFormat($local_dirs),
            'files' => $this->localClientSystemFormat($local_files),
        ];

        return view('client.mip.index', compact('data', 'links'));
    }


    public function storeDirectory(Request $request)
    {
        Storage::disk('public')->makeDirectory($request->input('path'). '/' . $request->input('name'));
        return back();
    }

    public function storeFile(Request $request)
    {
        $file_path = $request->input('path');
        $files = $request->files->get('files');

        if (!$files) {
            return redirect()->back()->withErrors(['files' => 'No files uploaded.']);
        }

        foreach ($files as $file) {
            if (!$file->isValid()) {
                return redirect()->back()->withErrors(['file' => 'Invalid file.']);
            }
            $url = $file_path . '/' . $file->getClientOriginalName();
            Storage::disk('public')->put($url, file_get_contents($file));
        }

        return redirect()->back();
    }

    public function searchReport(Request $request)
    {
        $orders = $services = $files = [];
        $section = 1;
        $business_lines = LineBusiness::all();
        $user = User::find(auth()->user()->id);
        $sede = $request->input('sede');

        if ($request->service) {
            $searchTerm = '%' . $request->service . '%';
            $services = Service::where('business_line_id', $request->input('business_line'))->orWhere('name', 'LIKE', $searchTerm)->get();
            $query = Order::whereIn('id', OrderService::whereIn('service_id', $services->pluck('id'))->get()->pluck('order_id'));
        } else {
            $query = Order::where('id', $request->input('report_id'));
        }

        if ($sede != null) {
            $query = $query->orWhere('customer_id', $sede);
        }

        $orders = $query->orWhereDate('programmed_date', $request->input('date'))
            ->orWhereTime('start_time', $request->input('time'))
            ->where('status_id', 5)->get();

        if ($orders->isEmpty()) {
            $work_dept_id = auth()->user()->work_department_id;
            if ($work_dept_id == 1 || $work_dept_id == 7) {
                $orders = Order::all();
            }
        }
        
        return view('client.report.index', compact('user', 'orders', 'business_lines', 'section'));
    }

    public function searchBackupReport(Request $request) {
        $business_lines = LineBusiness::all();
        $user = User::find(auth()->user()->id);
        $disk = Storage::disk('public');
        $section = 2;

        $customer_id = $request->input('sede');
        $report_id = $request->input('report_id');
        $date = $request->input('date');

        $folder_name = Customer::find($customer_id)->name;
        $directories = $disk->allDirectories($this->reports_path);
        $matchingDirectories = array_filter($directories, function ($dir) use ($folder_name) {
            return str_contains(strtolower($dir), strtolower($folder_name));
        });

        foreach ($matchingDirectories as $directory) {
            $files[] = $this->localClientSystemFormat($disk->allFiles($directory));
        }

        $files = $this->filterFiles($report_id, $date, $this->uniqueArray($this->flattenArray($files)));

        return view('client.report.index', compact('user', 'files', 'folder_name', 'business_lines', 'section'));
    }

    public function downloadFile($path)
    {
        try {
            $disk = Storage::disk('public');
            $decodedPath = urldecode($path);

            if ($disk->exists($decodedPath)) {
                return $disk->download($decodedPath);
            }
            return response()->json(['error' => 'File not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while downloading the file.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDirectory(Request $request)
    {
        $disk = Storage::disk('public');
        $root_path = $request->input('root_path');
        $path = $request->input('path');
        $new_path = $root_path . '/' . $request->input('name');
        if ($disk->exists($path)) {
            $disk->move($path, $new_path);
        }

        return back();
    }

    public function destroyDirectory(string $path)
    {
        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->deleteDirectory($path);
                return back();
            }

            return response()->json(['error' => 'Directory not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the directory.'], 500);
        }
    }


    public function destroyFile(string $path)
    {
        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                return back();
            }

            return response()->json(['error' => 'File not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the file.'], 500);
        }
    }


    public function permissions(Request $request)
    {
        $directoryId = $request->input('directory_id');
        $userIds = json_decode($request->input('selected_users'));
        $users = DirectoryUser::where('directory_id', $directoryId)->pluck('user_id')->toArray();

        //Elimina permisos
        $userIdstoDelete = array_diff($users, $userIds);
        foreach ($userIdstoDelete as $userId) {
            DirectoryUser::where('user_id', $userId)->where('directory_id', $directoryId)->delete();
        }

        // Agregar permiso
        $userIdstoAdd = array_diff($userIds, $users);
        foreach ($userIdstoAdd as $userId) {
            DirectoryUser::insert([
                'directory_idconsole.log(path);' => $directoryId,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back();
    }
}
