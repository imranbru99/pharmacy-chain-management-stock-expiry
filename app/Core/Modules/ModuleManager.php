<?php

declare(strict_types=1);

namespace App\Core\Modules;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\TenantModule;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class ModuleManager
{
    public function __construct(
        private readonly ModuleRegistry $registry,
    ) {}

    public function isEnabled(string $key, ?int $tenantId = null): bool
    {
        $manifest = $this->registry->get($key);

        if (($manifest['always_on'] ?? false) === true) {
            return true;
        }

        $tenantId ??= tenant_id();

        if ($tenantId === null) {
            return (bool) ($manifest['default_enabled'] ?? false);
        }

        $record = TenantModule::query()
            ->where('tenant_id', $tenantId)
            ->where('module_key', $key)
            ->first();

        if ($record !== null) {
            return $record->enabled;
        }

        return (bool) ($manifest['default_enabled'] ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSettings(string $key, ?int $tenantId = null): array
    {
        $tenantId ??= tenant_id();
        $defaults = $this->registry->get($key)['settings_schema'] ?? [];

        if ($tenantId === null) {
            return is_array($defaults) ? $defaults : [];
        }

        $record = TenantModule::query()
            ->where('tenant_id', $tenantId)
            ->where('module_key', $key)
            ->first();

        $settings = is_array($record?->settings) ? $record->settings : [];

        return array_merge(is_array($defaults) ? $defaults : [], $settings);
    }

    public function enable(int $tenantId, string $key): void
    {
        $manifest = $this->registry->get($key);

        foreach ($this->registry->dependencies($key) as $dependency) {
            $this->enable($tenantId, $dependency);
        }

        TenantModule::query()->updateOrCreate(
            ['tenant_id' => $tenantId, 'module_key' => $key],
            [
                'enabled' => true,
                'settings' => $this->getSettings($key, $tenantId),
            ],
        );

        if (($manifest['always_on'] ?? false) === true) {
            TenantModule::query()
                ->where('tenant_id', $tenantId)
                ->where('module_key', $key)
                ->update(['enabled' => true]);
        }
    }

    public function disable(int $tenantId, string $key): void
    {
        $manifest = $this->registry->get($key);

        if (($manifest['always_on'] ?? false) === true) {
            throw new RuntimeException("Module [{$key}] is always on and cannot be disabled.");
        }

        foreach ($this->registry->dependents($key) as $dependent) {
            if ($this->isEnabled($dependent, $tenantId)) {
                throw new RuntimeException(
                    "Cannot disable [{$key}]: dependent module [{$dependent}] is still enabled.",
                );
            }
        }

        TenantModule::query()->updateOrCreate(
            ['tenant_id' => $tenantId, 'module_key' => $key],
            ['enabled' => false],
        );
    }

    public function syncFromPlan(Tenant $tenant, ?Plan $plan = null): void
    {
        $plan ??= $tenant->plan;

        $allowed = $plan?->modules_json ?? [];

        if (! is_array($allowed)) {
            $allowed = [];
        }

        DB::transaction(function () use ($tenant, $allowed): void {
            foreach ($this->registry->all() as $key => $manifest) {
                if (($manifest['always_on'] ?? false) === true) {
                    $this->enable($tenant->id, $key);

                    continue;
                }

                if ($allowed === [] || in_array($key, $allowed, true)) {
                    $this->enable($tenant->id, $key);
                } else {
                    try {
                        $this->disable($tenant->id, $key);
                    } catch (RuntimeException) {
                        // Skip if dependents block disable during plan sync.
                    }
                }
            }
        });
    }
}
