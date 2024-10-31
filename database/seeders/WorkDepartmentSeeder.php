<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            [ 'name' => 'Dirección' ],
            [ 'name' => 'Contabilidad' ],
            [ 'name' => 'Logística' ],
            [ 'name' => 'Recursos Humanos' ],
            [ 'name' => 'Almacén' ],
            [ 'name' => 'Ventas' ],
            [ 'name' => 'Calidad' ],
            [ 'name' => 'Operaciones' ],
        ]);
    }
}
