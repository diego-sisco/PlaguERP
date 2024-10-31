<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Filenames;

class FilenamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerFileNames = [
            'Certificado RFC',
            'Comprobante domicilio fiscal',
            'Credencial INE',
            'Estatutos de incorporación',
            'Comprobante situación fiscal',
            'Manual del portal',
        ];
    
        $userFileNames = [
            'INE',
            'CURP',
            'Constancia de situación fiscal (RFC)',
            'NSS',
            'Acta de nacimiento',
            'Comprobante de domicilio',
            'Licencia para conducir',
            'Foto',
            'Firma'
        ];
    
        $data = [];
    
        // Generar datos para los archivos del cliente
        foreach ($customerFileNames as $fileName) {
            $data[] = [
                'name' => $fileName,
                'type' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        // Generar datos para los archivos de usuario
        foreach ($userFileNames as $fileName) {
            $data[] = [
                'name' => $fileName,
                'type' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        Filenames::insert($data);
    }
    
}
