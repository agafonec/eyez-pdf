<?php

namespace App\Providers;

use App\Models\Opretail;
use App\Models\Store;
use App\Observers\OpretailObserver;
use App\Observers\StoreObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Opretail::observe(OpretailObserver::class);
        Store::observe(StoreObserver::class);
    }
}
