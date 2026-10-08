<?php

namespace App\Http\Controllers;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:72', function (string $attribute, mixed $value, Closure $fail): void {
                if (is_string($value) && strlen($value) > 72) {
                    $fail('La contraseña no puede superar 72 bytes.');
                }
            }],
        ], [
            'username.required' => 'Ingresa tu nombre de usuario.',
            'password.required' => 'Ingresa tu contraseña.',
            'string' => 'El campo :attribute debe ser texto.',
            'max' => 'El campo :attribute supera el límite permitido.',
        ]);

        $key = 'login:user:'.hash('sha256', Str::lower($credentials['username']).'|'.$request->ip());
        $ipKey = 'login:ip:'.hash('sha256', $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5) || RateLimiter::tooManyAttempts($ipKey, 20)) {
            $seconds = max(RateLimiter::availableIn($key), RateLimiter::availableIn($ipKey));
            throw ValidationException::withMessages([
                'username' => 'Demasiados intentos. Espera '.$seconds.' segundos.',
            ]);
        }

        $user = User::where('username', $credentials['username'])->first();
        $validHash = $user && Hash::isHashed($user->getAuthPassword());

        if (! $validHash || ! Auth::attempt($credentials)) {
            RateLimiter::hit($key, 60);
            RateLimiter::hit($ipKey, 60);
            throw ValidationException::withMessages([
                'username' => 'El usuario o la contraseña son incorrectos.',
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        return redirect()->intended(route('productos.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Cerraste tu sesión correctamente.');
    }
}
