<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! config('security.demo_enabled')) {
            return;
        }

        $user = User::firstOrCreate(['username' => 'admin'], [
            'name' => 'Administrador',
            'email' => 'admin@ingenieriaweb.test',
            'password' => Hash::make('IngenieriaWeb2026!'),
        ]);

        if ($user->email === 'admin@ingenieriaweb.test' && config('hashing.driver') === 'md5' && password_verify('IngenieriaWeb2026!', $user->getRawOriginal('password'))) {
            $user->update(['password' => Hash::make('IngenieriaWeb2026!')]);
        }

        foreach ([
            ['name' => 'Teclado inalámbrico', 'sku' => 'TEC-001', 'description' => 'Teclado compacto para estaciones de trabajo.', 'price' => 29.90, 'stock' => 12],
            ['name' => 'Mouse ergonómico', 'sku' => 'MOU-002', 'description' => 'Mouse óptico con conexión USB.', 'price' => 15.50, 'stock' => 4],
            ['name' => 'Monitor de 24 pulgadas', 'sku' => 'MON-003', 'description' => 'Monitor Full HD para el laboratorio.', 'price' => 159.00, 'stock' => 8],
            ['name' => 'Memoria USB de 64 GB', 'sku' => 'USB-004', 'description' => 'Unidad de almacenamiento portátil.', 'price' => 9.75, 'stock' => 25],
        ] as $data) {
            if (! Product::where('sku', $data['sku'])->exists()) {
                $user->products()->create($data);
            }
        }
    }
}
