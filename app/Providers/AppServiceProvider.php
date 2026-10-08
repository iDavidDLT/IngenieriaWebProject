<?php

namespace App\Providers;

use App\Models\Product;
use App\Policies\ProductPolicy;
use App\Support\Md5Hasher;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use LogicException;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Hash::extend('md5', fn () => new Md5Hasher);
        Gate::policy(Product::class, ProductPolicy::class);

        if ($this->app->isProduction()) {
            if (config('hashing.driver') === 'md5' || config('security.demo_enabled')) {
                throw new LogicException('Producción requiere HASH_DRIVER=bcrypt y DEMO_MODE=false.');
            }

            config(['session.secure' => true, 'session.http_only' => true]);
        }
    }
}
