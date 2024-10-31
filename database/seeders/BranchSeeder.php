<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'status_id' => 2,
                'name' => 'San Luis Potosí (Sede Central)',
                'code' =>null,
                'fiscal_name' => 'SISPLAGAS DE MEXICO S.A. DE C.V.',
                'phone' => '4448251390',
                'alt_phone' => '4811228936',
                'email' => 'siscoplagas_slp@yahoo.com',
                'alt_email' => null,
                'address' => 'BOULEVARD MANUEL GÓMEZ MORIN NO. 855-1',
                'colony' => 'LOMAS DE MEXQUITIC',
                'zip_code' => '78130',
                'city' => 'San Luis Potosí',
                'state' => 'San Luis Potosí',
                'country' => 'México',
                'license_number' => '22 AP 24 028 0002 y 12-24A-0092',
                'rfc' => 'SME191121PU4',
                'fiscal_regime' => 'General de Ley Personas Morales',
                'url' => 'www.siscoplagas.com.mx',
                'description' => null,
            ],

            [
                'status_id' => 2,
                'name' => 'CD. Valles (Sede Valles)',
                'code' => null,
                'phone' => '4448250403',
                'alt_phone' => null,
                'email' => 'siscoplagas_valles@yahoo.com',
                'alt_email' => null,
                'address' => 'Derecho de Via #409',
                'colony' => '20 de Noviembre',
                'zip_code' => '79020',
                'city' => 'Ciudad Valles',
                'state' => 'San Luis Potosí',
                'country' => 'México',
                'license_number' => '18 AP 24 028 0001 y 12-24A-0092',
                'rfc' => 'SME191121PU4',
                'fiscal_regime' => 'General de Ley Personas Morales',
                'url' => 'www.siscoplagas.com.mx',
                'description' => 'puta madre',
            ],

            [
                'status_id' => 2,
                'name' => 'Jalisco (Sede Jalisco)',
                'code' => null,
                'phone' => '3338031219',
                'alt_phone' => '3338031226',
                'email' => 'siscoplagas@yahoo.com',
                'alt_email' => null,
                'address' => 'AV. 18 DE MARZO #1974',
                'colony' => 'Las Aguilas',
                'zip_code' => '45080',
                'city' => 'Zapopan',
                'state' => 'Jalisco',
                'country' => 'México',
                'license_number' => '18 AP 24 028 0001 y 12-24A-0092',
                'rfc' => 'SME191121PU4',
                'fiscal_regime' => 'General de Ley Personas Morales',
                'url' => 'www.siscoplagas.com.mx',
                'description' => null,
            ],
        ];

        foreach($branches as $branch) {
            Branch::insert($branch);
        }
    }
}