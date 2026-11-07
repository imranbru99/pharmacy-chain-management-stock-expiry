<?php

declare(strict_types=1);

namespace App\Support;

final class DemoGuard
{
    public static function enabled(): bool
    {
        return (bool) config('demo.enabled', false);
    }

    public static function blocks(string $action): bool
    {
        if (! self::enabled()) {
            return false;
        }

        return in_array($action, config('demo.blocked_settings', []), true);
    }
}
