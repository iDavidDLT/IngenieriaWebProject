<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

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
