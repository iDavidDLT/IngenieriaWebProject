<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

Artisan::command('db:archivo {--solo-ruta : Mostrar únicamente la ruta de SQLite}', function () {
    $connection = DB::connection();
    if ($connection->getDriverName() !== 'sqlite') {
        $this->error('La conexión activa no utiliza un archivo SQLite.');

        return 1;
    }

    $path = realpath($connection->getDatabaseName());
    if ($path === false) {
        $this->error('No se encuentra el archivo de la base de datos activa.');

        return 1;
    }

    if ($this->option('solo-ruta')) {
        $this->line($path);

        return 0;
    }

    $this->info('Base de datos SQLite activa');
    $this->line($path);
    $this->line('Productos almacenados: '.$connection->table('products')->count());

    return 0;
})->purpose('Identificar el archivo SQLite utilizado por esta instalación');

Artisan::command('demo:password', function () {
    if (! config('security.demo_enabled') || app()->isProduction()) {
        $this->error('Este comando solo está disponible con la demostración académica habilitada.');

        return 1;
    }

    $user = User::where('username', 'admin')->first();
    if (! $user) {
        $this->error('No existe la cuenta demo. Ejecuta php artisan db:seed.');

        return 1;
    }

    $hash = $user->getRawOriginal('password');
    $this->info('Cuenta académica: admin');
    $this->line('Hash almacenado: '.$hash);
    $this->line('Algoritmo: '.Hash::info($hash)['algoName']);
    $this->line('La contraseña original no se guarda en la base de datos.');
})->purpose('Mostrar el hash de la cuenta demo para el video académico');
