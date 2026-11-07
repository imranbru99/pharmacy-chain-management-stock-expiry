<?php

declare(strict_types=1);

namespace App\Modules\Pos;

use App\Modules\Pos\Services\PosService;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PosService::class);
    }

    public function boot(): void
    {
        //
    }
}
