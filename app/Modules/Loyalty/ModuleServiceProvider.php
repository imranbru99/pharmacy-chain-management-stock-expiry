<?php

declare(strict_types=1);

namespace App\Modules\Loyalty;

use App\Modules\Loyalty\Services\LoyaltyService;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LoyaltyService::class);
    }

    public function boot(): void
    {
        //
    }
}
