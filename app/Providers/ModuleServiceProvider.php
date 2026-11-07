<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Middleware\EnsureModuleEnabled;
use App\Core\Modules\ModuleManager;
use App\Core\Modules\ModuleRegistry;
use App\Core\Tenancy\TenantContext;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantContext::class);
        $this->app->singleton(ModuleRegistry::class);
        $this->app->singleton(ModuleManager::class);

        $this->registerModuleProviders();
    }

    public function boot(ModuleRegistry $registry): void
    {
        Route::aliasMiddleware('module', EnsureModuleEnabled::class);

        $registry->all();
    }

    private function registerModuleProviders(): void
    {
        $modulesPath = app_path('Modules');

        if (! File::isDirectory($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $directory) {
            $folder = basename($directory);
            $providerClass = "App\\Modules\\{$folder}\\ModuleServiceProvider";

            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }
}
