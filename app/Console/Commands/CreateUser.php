<?php

namespace App\Console\Commands;

use App\Models\User;
use Closure;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateUser extends Command
{
    protected $signature = 'users:create {username : Nombre de usuario único}';

    protected $description = 'Crear una cuenta privada desde la terminal con contraseña oculta';

    public function handle(): int
    {
        $data = [
            'username' => strtolower((string) $this->argument('username')),
            'name' => $this->ask('Nombre'),
            'email' => $this->ask('Correo'),
            'password' => $this->secret('Contraseña (12 caracteres mínimo, 72 bytes máximo)'),
            'password_confirmation' => $this->secret('Repite la contraseña'),
        ];

        $validator = Validator::make($data, [
            'username' => ['required', 'string', 'regex:/\A[a-z0-9][a-z0-9_.-]{2,49}\z/', 'unique:users,username'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(12)->letters()->mixedCase()->numbers()->symbols(), function (string $attribute, mixed $value, Closure $fail): void {
                if (is_string($value) && strlen($value) > 72) {
                    $fail('La contraseña no puede superar 72 bytes.');
                }
            }],
        ], [
            'confirmed' => 'Las contraseñas no coinciden.',
            'unique' => 'El valor de :attribute ya está registrado.',
            'regex' => 'El usuario debe tener 3–50 caracteres: letras minúsculas, números, puntos, guiones o guiones bajos.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        $this->info('Cuenta creada. El usuario tendrá su propio inventario.');

        return self::SUCCESS;
    }
}
