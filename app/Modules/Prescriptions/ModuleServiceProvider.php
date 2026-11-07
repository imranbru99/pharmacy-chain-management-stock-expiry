<?php

declare(strict_types=1);

namespace App\Modules\Prescriptions;

use App\Modules\Prescriptions\Services\PrescriptionService;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PrescriptionService::class);
    }

    public function boot(): void
    {
        //
    }
}
