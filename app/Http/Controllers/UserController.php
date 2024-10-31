<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\SimpleExcel\SimpleExcelWriter;

use App\Models\Administrative;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\ContractType;
use App\Models\SimpleRole;
use App\Models\Status;
use App\Models\Technician;
use App\Models\User;
use App\Models\UserContract;
use App\Models\UserFile;
use App\Models\WorkDepartment;
use App\Models\Filenames;
use App\Models\UserCustomer;
use App\Models\DirectoryPermission;

class UserController extends Controller
{
	private static $files_path = 'files/';
	private static $states_route = 'datas/json/Mexico_states.json';
	private static $cities_route = 'datas/json/Mexico_cities.json';
	private $path = 'client_system/';
	private $user_path = 'users/';

	private $size = 30;

	public static function verifyData($user, $hasContract)
	{
		$user_data = $user->role_id == 3 ? Technician::where('user_id', $user->id)->first() : Administrative::where('user_id', $user->id)->first();

		if (
			$user_data->curp != null && $user_data->rfc != null && $user_data->nss != null &&
			$user_data->address != null && $user_data->colony != null && $user_data->city != null &&
			$user_data->state != null && $user_data->country != null && $user_data->zip_code != null &&
			$user_data->birthdate != null && $user_data->hiredate != null && $user_data->salary != null &&
			$hasContract != false
		) {
			$user->status_id = 2;
			$user->save();
			return true;
		} else {
			return false;
		}
	}

	private function listDirectoriesRecursively($path)
	{
		$directories = [];

		if (Storage::disk('public')->exists($path)) {
			$subdirectories = Storage::disk('public')->directories($path);
			foreach ($subdirectories as $directory) {
				$directories[] = [
					'name' => basename($directory),
					'path' => $directory . '/',
					'directories' => $this->listDirectoriesRecursively($directory)
				];
			}
		}

		return $directories;
	}


	public function index(int $page): View
	{
		$type = 1;
		$users = User::orderBy('type_id', 'asc')->orderBy('id', 'desc')->get();
		$total = ceil($users->count() / $this->size);
		$min = ($page * $this->size) - $this->size;

		$users = collect(array_slice($users->toArray(), $min, $this->size))->map(function ($user) {
			return new User($user);
		});

		return view(
			'user.index',
			compact(
				'users',
				'type',
				'page',
				'total'
			)
		);
	}

	public function create(int $type): View
	{
		$disk = Storage::disk('public');
		$local_dirs = $disk->directories($this->path);
		$directories = $this->listDirectoriesRecursively($this->path);
		$statuses = Status::all();
		$work_departments = WorkDepartment::where('id', '!=', 1)->get();
		$roles = SimpleRole::where('id', '!=', 4)->get();
		$branches = Branch::all();
		$companies = Company::all();

		return view(
			'user.create',
			compact(
				'directories',
				'statuses',
				'work_departments',
				'roles',
				'branches',
				'companies',
				'type'
			)
		);
	}

	public function search(Request $request, int $type, int $page)
	{
		if (empty($request->search)) {
			return redirect()->back()->with('error', trans('messages.no_results_found'));
		}

		$searchTerm = '%' . $request->search . '%';

		$users = User::where('name', 'LIKE', $searchTerm)
			->orWhere('email', 'LIKE', $searchTerm)
			->orderBy('id', 'desc')
			->get();

		if ($users->isEmpty()) {
			$error = 'No se encontraron resultados de la búsqueda';
		}

		$total = ceil($users->count() / $this->size);
		$min = ($page * $this->size) - $this->size;

		$users = collect(array_slice($users->toArray(), $min, $this->size))->map(function ($user) {
			return new User($user);
		});

		return view(
			'user.index',
			compact(
				'users',
				'type',
				'page',
				'total'
			)
		);
	}
	public function store(Request $request): RedirectResponse
	{
		$directories = json_decode($request->input('directories'));
		$customerIds = json_decode($request->input('customers'));
		$files = Filenames::where('type', 'user')->get();
		$type = $request->input('type');

		// Crear nuevo usuario
		$user = new User($request->all());
		$user->password = bcrypt($request->password);
		$user->nickname = $request->password;
		$user->status_id = $type;
		$user->type_id = $type;
		$user->work_department_id = $type == 1 ? $request->work_department_id : null;
		$user->role_id = $type == 1 ? $request->role_id : 5;
		$user->save();

		$changes = 'Usuario creado';

		// Manejo según el tipo de usuario
		if ($type == 1) {
			// Definir el permiso para el usuario
			$role = Role::where('simple_role_id', $user->role_id)->where('work_id', $user->work_department_id)->first();
			if ($role) {
				$user->assignRole($role->name);
			}

			// Archivos del usuario
			foreach ($files as $file) {
				UserFile::insert([
					'user_id' => $user->id,
					'filename_id' => $file->id,
				]);
			}

			// Asignar a la tabla correspondiente
			if ($user->role_id == 3) {
				$technician = new Technician($request->all());
				$technician->user_id = $user->id;
				$technician->contract_type_id = 1;
				$technician->save();
				$changes .= 'Nuevo Technician creado ';
			} else {
				$admin = new Administrative($request->all());
				$admin->user_id = $user->id;
				$admin->contract_type_id = 1;
				$admin->save();
				$changes .= 'Nuevo Administrative ';
			}

			// Crear contrato del usuario
			$user_contract = new UserContract();
			$user_contract->user_id = $user->id;
			$user_contract->contract_type_id = 1;
			$user_contract->save();
		} else {
			// Definir el rol para el usuario sin departamento de trabajo
			$role = Role::where('simple_role_id', $user->role_id)->where('work_id', null)->first();
			if ($role) {
				$user->assignRole($role->name);
			}

			if (!empty($directories)) {
				foreach ($directories as $dir) {
					DirectoryPermission::insert([
						'path' => $dir,
						'user_id' => $user->id,
					]);
				}
			}

			if (!empty($customerIds)) {
				foreach ($customerIds as $customerId) {
					UserCustomer::insert([
						'user_id' => $user->id,
						'customer_id' => $customerId,
						'created_at' => now(),
						'updated_at' => now(),
					]);
				}
			}
		}
		$sql = 'INSERT_USER_' . $user->id;
		PagesController::log('store', $changes, $sql);

		return redirect()->route('user.index', ['type' => $type, 'page' => 1]);
	}

	public function show(string $id, string $type): View
	{
		$user = User::find($id);
		$status = Status::find($user->status_id);
		$work_departments = WorkDepartment::all();
		$roles = SimpleRole::all();
		$branches = Branch::all();
		$companies = Company::all();
		$contracts = ContractType::all();
		$user_contracts = UserContract::where('user_id', $id)->get();

		$states = json_decode(file_get_contents(public_path(UserController::$states_route)), true);
		$cities = json_decode(file_get_contents(public_path(UserController::$cities_route)), true);

		return view(
			'user.show',
			compact(
				'user',
				'status',
				'work_departments',
				'roles',
				'branches',
				'companies',
				'contracts',
				'user_contracts',
				'states',
				'type',
				'cities',
			)
		);
	}

	public function edit(string $id, int $section)
	{
		$dates = [];
		$user = User::find($id);
		$filenames = Filenames::where('type', 'user')->get();
		$statuses = $user->status_id != 1 ? Status::where('id', '!=', 1)->get() : Status::where('id', 1)->get();
		$work_departments = WorkDepartment::where('id', '!=', 1)->get();
		$roles = SimpleRole::whereNotIn('id', [4, 5])->get();
		$branches = Branch::all();
		$companies = Company::all();
		$contracts = ContractType::all();
		$contract = $user->contracts()->latest()->first();
		$clients = Customer::whereIn('general_sedes', Customer::whereIn('id', $user->customers()->get()->pluck('id'))->get()->pluck('general_sedes'))->get();

		$states = json_decode(file_get_contents(public_path(UserController::$states_route)), true);
		$cities = json_decode(file_get_contents(public_path(UserController::$cities_route)), true);

		if ($user->type_id == 1) {
			$dates = array(
				'startdate' => $contract->contract_startdate,
				'enddate' => $contract->contract_enddate,
			);
		}

		$disk = Storage::disk('public');
		$local_dirs = $disk->directories($this->path);
		$directories = $this->listDirectoriesRecursively($this->path);

		return view(
			'user.edit',
			compact(
				'user',
				'statuses',
				'work_departments',
				'roles',
				'branches',
				'companies',
				'contracts',
				'dates',
				'states',
				'cities',
				'filenames',
				'directories',
				'clients',
				'section'
			)
		);
	}

	public function update(Request $request, string $id)
	{
		$user_data = [];
		$hasContract = false;
		$changes = '';
		$directories = json_decode($request->input('directories'));
		$customerIds = json_decode($request->input('customers'));
		$user = User::findOrFail($id);
		$user->fill($request->all());
		$user->nickname = $request->input('password');
		$user->password = Hash::make($request->input('password'));
		$user->save();

		if ($user->role_id != 5) {
			if ($user->role_id == 3) {
				$user_data = Technician::where('user_id', $id)->first();
			} else {
				$user_data = Administrative::where('user_id', $id)->first();
			}

			if ($user_data) {
				$user_data->update($request->all());

				if ($request->type == 1) {
					$updated = false;            // Datos para tabla usuarios
					if ($user->name != $request->name) {
						$changes .= 'Nombre actualizado, ';
						$updated = true;
					}
					if ($user->email != $request->email) {
						$changes .= 'Correo actualizado, ';
						$updated = true;
					}
					if ($user->password != bcrypt($request->password)) {
						$changes .= 'Contraseña actualizada, ';
						$updated = true;
					}
					if ($user->nickname != $request->password) {
						$changes .= 'Nickname cambiado, ';
						$updated = true;
					}
					if ($user_data->phone != $request->phone) {
						$changes .= 'Teléfono actualizado, ';
						$updated = true;
					}
					if ($user_data->company_phone != $request->company_phone) {
						$changes .= 'Teléfono de la empresa actualizado, ';
						$updated = true;
					}
					if ($user_data->birthdate != $request->birthdate) {
						$changes .= 'Fecha de nacimiento actualizada, ';
						$updated = true;
					}
					if ($user_data->address != $request->address) {
						$changes .= 'Dirección actualizada, ';
						$updated = true;
					}
					if ($user_data->zip_code != $request->zip_code) {
						$changes .= 'Código postal, ';
						$updated = true;
					}
					if ($user_data->colony != $request->colony) {
						$changes .= 'Colonia actualizada, ';
						$updated = true;
					}
					if ($user_data->city != $request->city) {
						$changes .= 'Ciudad cambiada, ';
						$updated = true;
					}
					if ($user_data->state != $request->state) {
						$changes .= 'Estado cambiado, ';
						$updated = true;
					}
					if ($user_data->country != $request->country) {
						$changes .= 'País cambiado, ';
						$updated = true;
					}
					if ($user_data->curp != $request->curp) {
						$changes .= 'CURP actualizado, ';
						$updated = true;
					}
					if ($user_data->nss != $request->nss) {
						$changes .= 'NSS actualizado, ';
						$updated = true;
					}
					if ($user_data->rfc != $request->rfc) {
						$changes .= 'RFC actualizado, ';
						$updated = true;
					}

					if ($updated) {
						$success = 'Datos personales actualizados correctamente';
					} else {
						$success = 'No se realizaron cambios en los datos personales';
					}
				} elseif ($request->type == 2) {
					if ($request->contract != $user_data->contract_type_id && !isset($request->contract_enddate)) {
						$warning = 'Debes ingresar la fecha de término del contrato actual antes de cambiarlo.';
						return redirect()->back()->with(compact('warning'));
					}
					if ($user->role_id != $request->role) {
						$user->role_id = $request->role;
						$changes .= 'Rol cambiado, ';
					}
					if ($user->status_id != $request->status) {
						$user->status_id = $request->status;
						$changes .= 'Estado cambiado, ';
					}
					if ($user->work_department_id != $request->wk_department) {
						$user->work_department_id = $request->wk_department;
						$changes .= 'Departamento de trabajo cambiado, ';
					}
					if ($user_data->branch_id != $request->branch) {
						$user_data->branch_id = $request->branch;
						$changes .= 'Sucursal cambiada, ';
					}
					if ($user_data->hiredate != $request->hiredate) {
						$user_data->hiredate = $request->hiredate;
						$changes .= 'Fecha de contratación, ';
					}
					if ($user_data->salary != $request->salary) {
						$user_data->salary = $request->salary;
						$changes .= 'Salario cambiado, ';
					}
					if ($user_data->clabe != $request->clabe) {
						$user_data->clabe = $request->clabe;
						$changes .= 'CLABE actualizada, ';
					}
					if ($user_data->company_id != $request->company) {
						$user_data->company_id = $request->company;
						$changes .= 'Empresa actualizada, ';
					}
					if ($user_data->contract_type_id != $request->contract) {
						$user_data->contract_type_id = $request->contract;
						$changes .= 'Tipo de contrato actualizado, ';
					}
				}
			}


			if ($request->contract) {
				UserContract::updateOrCreate(
					[
						'user_id' => $id,
						'contract_type_id' => $request->contract,
					],
					[
						'contract_startdate' => $request->contract_startdate,
						'contract_enddate' => $request->contract_enddate,
					]
				);

				if (isset($request->contract_startdate)) {
					$hasContract = true;
				}
			}

			$role = Role::where('simple_role_id', $user->role_id)->where('work_id', $user->work_department_id)->first();
			if ($role) {
				$user->syncRoles([$role->name]);
			}

			if ($user->status_id != 3 && $user->role_id != 5) {
				UserController::verifyData($user, $hasContract);
			}
		} else {
			if (!empty($directories)) {

				$dir_perms = DirectoryPermission::where('user_id', $user->id)->get();
				$new_paths = array_diff($directories, $dir_perms->pluck('path')->toArray());
				$delete_paths = array_diff($dir_perms->pluck('path')->toArray(), $directories);

				foreach ($delete_paths as $path) {
					DirectoryPermission::where('path', $path)->delete();
				}

				foreach ($new_paths as $path) {
					DirectoryPermission::insert([
						'path' => $path,
						'user_id' => $user->id,
						'created_at' => now(),
						'updated_at' => now()
					]);
				}
			}

			if (!empty($customerIds)) {
				$customers = $user->customers()->get();
				$new_customers = array_diff($customerIds, $customers->pluck('id')->toArray());
				$delete_customers = array_diff($customers->pluck('id')->toArray(), $customerIds);

				foreach ($delete_customers as $customerId) {
					UserCustomer::where('user_id', $user->id)->where('customer_id', $customerId)->delete();
				}

				foreach ($new_customers as $customer_id) {
					UserCustomer::insert([
						'user_id' => $user->id,
						'customer_id' => $customer_id,
						'created_at' => now(),
						'updated_at' => now()
					]);
				}
			}

			// Definir permisos para el usuario
			$role = Role::where('simple_role_id', $user->role_id)->where('work_id', $user->work_department_id)->first();
			if ($role) {
				$user->syncRoles([$role->name]);
			}
		}

		// Registrar los cambios
		$sql = 'UPDATE_USER_' . $user->id;
		PagesController::log('update', $changes, $sql);

		return back();
	}

	public function storeFile(Request $request)
	{
		$fileID = $request->file_id;
		$userfile = UserFile::find($fileID);

		$request->validate([
			'file' => 'required|mimes:jpeg,png,jpg,pdf|max:5120'
		]);

		$file = $request->file('file');
		$fileName = 'firma_' . $userfile->user_id . '_' . time() . '_' . $file->getClientOriginalExtension();
		$url = $this->user_path . 'signatures/' . $fileName;

		Storage::disk('public')->put($url, file_get_contents($file));

		// Verifica si hay un archivo existente y lo elimina si es así
		if (!is_null($userfile->path)) {
			Storage::disk('local')->delete('userfiles/' . $userfile->user_id . '/' . $userfile->path);
		}

		$userfile->path = $url;
		$userfile->expirated_at = $request->input('expirated_at');
		$userfile->save();

		return back();
	}

	public function downloadFile(string $id)
	{
		try {
			$userfile = UserFile::find($id);

			if (!$userfile) {
				abort(404);
			}

			if (Storage::disk('public')->exists($userfile->path)) {
				return Storage::disk('public')->download($userfile->path);
			}
			return response()->json(['error' => 'File not found.'], 404);
		} catch (\Exception $e) {
			return response()->json(['error' => 'An error occurred while downloading the file.'], 500);
		}
	}

	public function export(Request $request)
	{
		$exportData = [];
		switch ($request->input('option_export')) {
			case 1:
				$users = User::all();
				foreach ($users as $user) {
					$data = [
						'id' => $user->id,
						'name' => $user->name,
						'email' => $user->email,
						'role' => optional($user->role)->name,
						'status' => optional($user->status)->name,
						'company' => optional($user->roleData->company)->name,
						'branch' => optional($user->roleData->branch)->name,
						'contract' => optional($user->roleData->contractType)->name,
						'curp' => $user->roleData->curp,
						'rfc' => $user->roleData->rfc,
						'nss' => $user->roleData->nss,
						'phone' => $user->roleData->phone,
						'company_phone' => $user->roleData->company_phone,
						'address' => $user->roleData->address,
						'colony' => $user->roleData->colony,
						'city' => $user->roleData->city,
						'state' => $user->roleData->state,
						'country' => $user->roleData->country,
						'zip_code' => $user->roleData->zip_code,
						'birthdate' => $user->roleData->birthdate,
						'hiredate' => $user->roleData->hiredate,
						'salary' => $user->roleData->salary,
						'clabe' => $user->roleData->clabe,
						'signature' => $user->roleData->signature,
						'created_at' => $user->roleData->created_at,
					];
					$exportData[] = $data;
				}
				$columnNames = array_keys($data);
				break;

			case 2:
				$columnNames = Schema::getColumnListing('user_file');
				$exportData = UserFile::all()->toArray();
				break;
		}

		SimpleExcelWriter::streamDownload('your-export.xlsx')
			->addHeader($columnNames)
			->addRows($exportData)
			->toBrowser();

		return redirect()->back();
	}
}
