<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\WorkDepartment;

class WorkDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkDepartment::insert([
            [ 'name' => 'Dirección' ], // 1
            [ 'name' => 'Contabilidad' ], // 2
            [ 'name' => 'Logística' ], // 3
            [ 'name' => 'Recursos Humanos' ], // 4
            [ 'name' => 'Almacén' ], // 5
            [ 'name' => 'Ventas' ], // 6
            [ 'name' => 'Calidad' ], // 7
            [ 'name' => 'Operaciones' ], // 8
        ]);
    }
}
