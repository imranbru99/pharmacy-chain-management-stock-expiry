<?php

declare(strict_types=1);

namespace App\Modules\Forecasting;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Services\DemandForecastService::class);
    }

    public function boot(): void
    {
        //
    }
}
