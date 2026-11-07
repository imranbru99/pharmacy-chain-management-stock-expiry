<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

trait ChecksModuleAccess
{
    protected static function moduleKey(): string
    {
        return 'core';
    }

    public static function canViewAny(): bool
    {
        return module_enabled(static::moduleKey()) && parent::canViewAny();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return module_enabled(static::moduleKey()) && parent::shouldRegisterNavigation();
    }
}
