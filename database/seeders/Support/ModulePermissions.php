<?php

declare(strict_types=1);

namespace Database\Seeders\Support;

use App\Core\Modules\ModuleRegistry;

final class ModulePermissions
{
    /**
     * @return list<string>
     */
    public static function all(): array
    {
        $permissions = [];

        foreach (app(ModuleRegistry::class)->all() as $manifest) {
            foreach ($manifest['permissions'] ?? [] as $permission) {
                if (is_string($permission)) {
                    $permissions[] = $permission;
                }
            }
        }

        $permissions[] = 'platform.super';
        $permissions[] = 'tenant.manage';

        return array_values(array_unique($permissions));
    }

    /**
     * @return array<string, list<string>>
     */
    public static function roleMap(): array
    {
        $all = self::all();

        return [
            'super_admin' => $all,
            'owner' => array_values(array_filter($all, fn (string $p): bool => $p !== 'platform.super')),
            'regional' => self::prefixes(['reports.', 'transfers.', 'stock.', 'catalog.', 'core.view']),
            'branch_manager' => self::prefixes(['pos.', 'sales.', 'stock.', 'catalog.', 'customers.', 'expiry.']),
            'pharmacist' => self::prefixes(['prescriptions.', 'catalog.', 'customers.', 'sales.view']),
            'cashier' => self::prefixes(['pos.', 'sales.', 'customers.view']),
            'storekeeper' => self::prefixes(['stock.', 'transfers.', 'purchasing.', 'suppliers.']),
            'purchase' => self::prefixes(['purchasing.', 'suppliers.', 'catalog.view']),
            'accountant' => self::prefixes(['finance.', 'reports.', 'sales.view']),
            'hr' => self::prefixes(['hr.', 'users.view']),
            'warehouse' => self::prefixes(['stock.', 'transfers.', 'purchasing.']),
            'rider' => self::prefixes(['delivery.', 'online_orders.view']),
            'supplier' => ['suppliers.view', 'purchasing.view'],
            'customer' => ['customers.view'],
            'doctor' => self::prefixes(['prescriptions.']),
            'auditor' => self::prefixes(['compliance.', 'reports.', 'stock_counts.', 'stock.view']),
        ];
    }

    /**
     * @param  list<string>  $prefixes
     * @return list<string>
     */
    private static function prefixes(array $prefixes): array
    {
        $out = [];

        foreach (self::all() as $permission) {
            foreach ($prefixes as $prefix) {
                if (str_starts_with($permission, $prefix)) {
                    $out[] = $permission;
                }
            }
        }

        return array_values(array_unique($out));
    }
}
