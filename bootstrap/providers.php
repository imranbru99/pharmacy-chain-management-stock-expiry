<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\StaffPanelProvider;
use App\Providers\Filament\SuperAdminPanelProvider;
use App\Providers\ModuleServiceProvider;

return [
    AppServiceProvider::class,
    ModuleServiceProvider::class,
    AdminPanelProvider::class,
    SuperAdminPanelProvider::class,
    StaffPanelProvider::class,
];
