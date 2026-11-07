<?php

declare(strict_types=1);

namespace App\Modules\Sales;

use App\Modules\Sales\Services\InvoicePdfService;
use App\Modules\Sales\Services\SaleReturnService;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SaleReturnService::class);
        $this->app->singleton(InvoicePdfService::class);
    }

    public function boot(): void
    {
        //
    }
}
