<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\CartItem;
use App\Policies\CartItemPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(CartItem::class, CartItemPolicy::class);
    }
}
