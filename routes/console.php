<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

Artisan::command('demo:password', function () {
    $user = User::where('username', 'admin')->first();
    if (! $user) {
        $this->error('No existe la cuenta demo. Ejecuta php artisan db:seed.');

        return 1;
    }
    $this->info('Cuenta académica: admin');
    $this->line('Hash almacenado: '.$user->getRawOriginal('password'));
    $this->line('Algoritmo: '.password_get_info($user->getRawOriginal('password'))['algoName']);
    $this->line('La contraseña original no se guarda en la base de datos.');
})->purpose('Mostrar el hash de la cuenta demo para el video académico');
