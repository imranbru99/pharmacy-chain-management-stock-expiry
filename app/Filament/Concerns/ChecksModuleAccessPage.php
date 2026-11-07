<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

trait ChecksModuleAccessPage
{
    protected static function moduleKey(): string
    {
        return 'core';
    }

    public static function canAccess(): bool
    {
        return module_enabled(static::moduleKey()) && parent::canAccess();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return module_enabled(static::moduleKey()) && parent::shouldRegisterNavigation();
    }
}
