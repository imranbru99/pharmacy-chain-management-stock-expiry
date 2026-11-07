<?php

declare(strict_types=1);

namespace App\Modules\Stock;

use App\Modules\Stock\Services\StockLedgerService;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(StockLedgerService::class);
    }

    public function boot(): void
    {
        //
    }
}
