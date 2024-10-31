<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder {

	public function run(): void {
		$permissions = [
			[
				'name' => 'read_contract', // 1
				'type' => 'r',
			],
			[
				'name' => 'read_customer', // 2
				'type' => 'r',
			],
			[
				'name' => 'read_order', // 3
				'type' => 'r',
			],
			[
				'name' => 'read_pest', // 4
				'type' => 'r',
			],
			[
				'name' => 'read_product', // 5
				'type' => 'r',
			],
			[
				'name' => 'read_service', // 6
				'type' => 'r',
			],
			[
				'name' => 'read_user', // 7
				'type' => 'r',
			],
			[
				'name' => 'read_controlPoint', // 8
				'type' => 'r',
			],
			[
				'name' => 'write_contract', // 9
				'type' => 'w',
			],
			[
				'name' => 'write_customer', // 10
				'type' => 'w',
			],
			[
				'name' => 'write_order', // 11
				'type' => 'w',
			],
			[
				'name' => 'write_pest', // 12
				'type' => 'w',
			],
			[
				'name' => 'write_product', // 13
				'type' => 'w',
			],
			[
				'name' => 'write_service', // 14
				'type' => 'w',
			],
			[
				'name' => 'write_user', // 15
				'type' => 'w',
			],
			[
				'name' => 'write_controlPoint', // 16
				'type' => 'w',
			],
			[
				'name' => 'write_report', //17
				'type' => 'w',
			],
			[
				'name' => 'write_schedule', //18
				'type' => 'w',
			],
			[
				'name' => 'read_client', //19
				'type' => 'r'
			],
			[
				'name' => 'write_client', //20
				'type' => 'w'
			]
		];

		$data = array_map(function ($permission) {
			return [
				'name' => $permission['name'],
				'type' => $permission['type'],
				'guard_name' => 'web',
				'created_at' => now(),
				'updated_at' => null,
			];
		}, $permissions);

		Permission::insert($data);

	}

}
