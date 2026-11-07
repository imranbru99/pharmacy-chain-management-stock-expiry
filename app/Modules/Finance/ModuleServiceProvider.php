<?php

declare(strict_types=1);

namespace App\Modules\Finance;

use App\Modules\Finance\Services\JournalService;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(JournalService::class);
    }

    public function boot(): void
    {
        //
    }
}
