<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venta;

class VentasTableSeeder extends Seeder
{
    public function run()
    {
        Venta::insert([
            ['vendedor_id' => 1, 'fecha_venta' => '2025-06-02', 'monto' => 650],
            ['vendedor_id' => 1, 'fecha_venta' => '2025-06-15', 'monto' => 850],

            ['vendedor_id' => 2, 'fecha_venta' => '2025-06-05', 'monto' => 500],
            ['vendedor_id' => 2, 'fecha_venta' => '2025-06-20', 'monto' => 1200],

            ['vendedor_id' => 3, 'fecha_venta' => '2025-06-10', 'monto' => 300],

            ['vendedor_id' => 4, 'fecha_venta' => '2025-06-12', 'monto' => 900],
            ['vendedor_id' => 4, 'fecha_venta' => '2025-05-28', 'monto' => 700],  // fuera de rango ejemplo
        ]);
    }
}
