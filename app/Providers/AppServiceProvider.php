<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\CartItem;
use App\Policies\CartItemPolicy;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(CartItem::class, CartItemPolicy::class);

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}
