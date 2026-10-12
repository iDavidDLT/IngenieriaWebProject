<?php
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Venta;
use App\Models\Vendedor;
use App\Models\Regla;
use Database\Seeders\DatabaseSeeder;
Artisan::command('inspire', function () { $this->comment(Inspiring::quote()); });
Artisan::command('demo:preparar', function () {
    if (Vendedor::exists() || Regla::exists() || Venta::exists()) {
        $this->info('Los datos de ejemplo ya existen.');
        return;
    }
    $this->call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);
})->purpose('Carga los datos originales solo si las tres tablas están vacías');
