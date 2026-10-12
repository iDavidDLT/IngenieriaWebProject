<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendedor;

class VendedoresTableSeeder extends Seeder
{
    public function run()
    {
        Vendedor::insert([
            ['nombre' => 'Jossue Ayala',   'email' => 'jossue.ayala@udla.edu.ec'],
            ['nombre' => 'Melanie Jacome', 'email' => 'melanie.jacome@udla.edu.ec'],
            ['nombre' => 'Carlos Pérez',   'email' => 'carlos.perez@udla.edu.ec'],
            ['nombre' => 'María Ortiz',    'email' => 'maria.ortiz@udla.edu.ec'],
        ]);
    }
}
