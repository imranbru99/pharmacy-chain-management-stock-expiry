<?php

declare(strict_types=1);

namespace App\Modules\Expiry;

use App\Modules\Expiry\Services\ExpiryDashboardService;
use App\Modules\Expiry\Services\FefoPicker;
use App\Modules\Expiry\Services\RecallService;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FefoPicker::class);
        $this->app->singleton(ExpiryDashboardService::class);
        $this->app->singleton(RecallService::class);
    }

    public function boot(): void
    {
        //
    }
}
