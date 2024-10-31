<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuestionOption;

class QuestionOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            'Si,No',
            'Bien,Mal,Regular',
            'Numero Entero',
            'Numero Decimal',
            'Texto Corto',
            'Texto Largo',
            'Nulo,Bajo,Poco',
            'Porcentaje',
            'Evaluacion',/*1 - 10 */
            'Muy mal,Mal,Regular,Bien,Perfecto',
            'Nulo,Bajo,Medio,Alto',
            'Tipo de plaga',
            'Correcta,Desaparecida,Inaccesible,Reducida,Rota,Apagada',
            'Correcto,Estropeado,Completamente comido,Parcialmente,Comido,Sin cebo',
            'Huella,Deposicion,Restos comida,Pelos,Rata,Raton,No hay deteccion,Cebo roido',
            'Area interior,Area exterior,Perimetro'
        ];
        $descriptions = [
            'El aplicador podra contestar entre un',
            'El aplicador podra contestar entre valores',
            'El aplicador podra contestar con un',
            'El aplicador podra contestar con un',
            'El aplicador podra contestar con un',
            'El aplicador podra contestar con un',
            'El aplicador podra contestar entre valores',
            'El aplicador podra contestar con un',
            'El aplicador podra contestar con un valor',
            'El aplicador podra contestar con un valor a elegir entre',
            'El aplicador podra contestar con un valor a elegir entre',
            'El aplicador podra contestar con una plaga a elegir de entre las que gestiona el punto de control',
            'El aplicador podra contestar entre valores',
            'El aplicador podra contestar entre valores',
            'El aplicador podra contestar entre valores',
            'El aplicador podra contestar con'
        ];
        for($i = 0; $i<count($values); $i++)
        {
            QuestionOption::create([
                'value' =>  $values[$i],
                'description' => $descriptions[$i]
            ]); 
        }
    }
}
