<?php

declare(strict_types=1);

namespace App\Modules\Transfers;

use App\Modules\Transfers\Services\TransferService;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TransferService::class);
    }

    public function boot(): void
    {
        //
    }
}
