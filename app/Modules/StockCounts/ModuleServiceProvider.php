<?php

declare(strict_types=1);

namespace App\Modules\StockCounts;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Services\StockCountService::class);
    }

    public function boot(): void
    {
        //
    }
}
