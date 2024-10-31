<?php

namespace Database\Seeders;

use App\Models\SimpleRole;
use App\Models\WorkDepartment;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{

	public function run(): void
	{

		$permissions = Permission::all();

		SimpleRole::insert([
			['name' => 'Administrativo'],	// 1
			['name' => 'Supervisor'], // 2
			['name' => 'Tecnico'], // 3
			['name' => 'Administrador'], // 4
			['name' => 'Cliente'], // 5
		]);

		// Crea el rol administrador para la definición de permisos
		$role = SimpleRole::find(4);
		$workDept = WorkDepartment::find(1);
		$admin = Role::create([
			'simple_role_id' => $role->id,
			'work_id' => $workDept->id,
			'name' => $role->name . $workDept->name,
			'guard_name' => 'web',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		// Define TODOS los permisos para el admin
		foreach ($permissions as $permission) {
			$admin->givePermissionTo($permission->name);
		}

		// Crea los roles para administrativo y supervisor para la definición de permisos
		$roles = SimpleRole::whereIn('id', [1, 2])->get();
		$workDepts = WorkDepartment::where('id', '!=', 1)->get();

		$toWritePermissions = [
			['word_id' => 2, 'permissions' => [10, 13, 15]],
			['word_id' => 3, 'permissions' => [10, 13, 15, 16, 17]],
			['word_id' => 4, 'permissions' => [15]],
			['word_id' => 5, 'permissions' => [12, 13]],
			['word_id' => 6, 'permissions' => [9, 10, 11, 13, 14, 17]],
			['word_id' => 7, 'permissions' => [9, 10, 11, 12, 13, 14, 16, 17, 18, 19, 20]],
			['word_id' => 8, 'permissions' => [11, 18]],
		];

		$read_permissions = Permission::where('type', 'r')->get();

		foreach ($roles as $role) {
			foreach ($workDepts as $wd) {
				$newRole = Role::create([
					'simple_role_id' => $role->id,
					'work_id' => $wd->id,
					'name' => $role->name . $wd->name,
					'guard_name' => 'web',
					'created_at' => now(),
					'updated_at' => now(),
				]);

				$newRole->givePermissionTo($read_permissions->pluck('name')->toArray());

				$writePermissions = collect($toWritePermissions)->firstWhere('word_id', $wd->id);
				if ($writePermissions) {
					$newRole->givePermissionTo(Permission::whereIn('id', $writePermissions['permissions'])->pluck('name')->toArray());
				}
			}
		}

		// Crea el rol administrador para la definición de permisos
		$role = SimpleRole::find(5);
		$permission = Permission::find(19);
		$client = Role::create([
			'simple_role_id' => $role->id,
			'name' => $role->name,
			'guard_name' => 'web',
			'created_at' => now(),
			'updated_at' => now(),
		]);

		// Define los permisos para el cliente-usuario
		$client->givePermissionTo($permission->name);
	}
}
