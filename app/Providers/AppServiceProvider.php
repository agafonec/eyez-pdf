<?php

namespace App\Providers;

use App\Contracts\CustomersFlowInterface;
use App\Models\Opretail;
use App\Models\Store;
use App\Observers\OpretailObserver;
use App\Observers\StoreObserver;
use App\Services\CustomersFlow\CustomersFlow;
use App\Services\Opretail\OpretailService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomersFlowInterface::class, CustomersFlow::class);
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
