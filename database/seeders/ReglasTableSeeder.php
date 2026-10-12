<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Regla;

class ReglasTableSeeder extends Seeder
{
    public function run()
    {
        Regla::insert([
                ['porcentaje'   => 0.00, 'umbral_monto' => 0],  
            ['porcentaje' => 0.06, 'umbral_monto' => 600],
            ['porcentaje' => 0.08, 'umbral_monto' => 500],
            ['porcentaje' => 0.10, 'umbral_monto' => 800],
            ['porcentaje' => 1.15, 'umbral_monto' => 1000],
        ]);
    }
}