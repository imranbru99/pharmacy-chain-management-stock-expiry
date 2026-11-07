<?php

declare(strict_types=1);

namespace App\Modules\Purchasing;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Services\PurchaseOrderService::class);
        $this->app->singleton(Services\SupplierPriceComparisonService::class);
    }

    public function boot(): void
    {
        //
    }
}
