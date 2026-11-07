<?php

declare(strict_types=1);

use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\TenantContext;
use App\Models\Tenant;

if (! function_exists('module_enabled')) {
    function module_enabled(string $key, ?int $tenantId = null): bool
    {
        return app(ModuleManager::class)->isEnabled($key, $tenantId);
    }
}

if (! function_exists('current_tenant')) {
    function current_tenant(): ?Tenant
    {
        return app(TenantContext::class)->get();
    }
}

if (! function_exists('tenant_id')) {
    function tenant_id(): ?int
    {
        return current_tenant()?->id;
    }
}

if (! function_exists('money_format')) {
    function money_format(int $minor, ?string $currency = null): string
    {
        $currency = $currency ?? current_tenant()?->currency ?? 'BDT';
        $major = $minor / 100;
        $decimals = in_array($currency, ['BDT', 'JPY', 'KRW'], true) ? 2 : 2;
        $formatted = number_format($major, $decimals, '.', ',');

        return match ($currency) {
            'BDT' => "৳{$formatted}",
            'USD' => "\${$formatted}",
            'EUR' => "€{$formatted}",
            default => "{$currency} {$formatted}",
        };
    }
}
