<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MovementType;

class MovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movement_type = [
            'Devolucion',
            'Recepcion',
            'Transpaso entrada',
            'Regularizacion entrada',
            'Deterioro',
            'Robo',
            'Transpaso salida',
            'Consumo',
            'Regularizacion salida',
            'Devolucion a proveedor'
        ];

        foreach($movement_type as $item){
            MovementType::create([
                'name' => $item,
            ]);
        }
    }
}
