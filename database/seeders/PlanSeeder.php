<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Modules\ModuleRegistry;
use App\Models\Plan;
use Illuminate\Database\Seeder;

final class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $allModules = array_keys(app(ModuleRegistry::class)->all());

        $bundles = [
            'starter' => ['core', 'users', 'catalog', 'stock', 'expiry', 'pos', 'sales', 'customers', 'suppliers', 'purchasing', 'reports', 'notifications'],
            'growth' => array_values(array_diff($allModules, ['ai', 'billing'])),
            'enterprise' => $allModules,
        ];

        $rows = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price_monthly_minor' => 499900,
                'price_yearly_minor' => 4999900,
                'max_branches' => 3,
                'max_users' => 15,
                'max_skus' => 2000,
                'modules_json' => $bundles['starter'],
            ],
            [
                'name' => 'Growth',
                'slug' => 'growth',
                'price_monthly_minor' => 1299900,
                'price_yearly_minor' => 12999900,
                'max_branches' => 15,
                'max_users' => 75,
                'max_skus' => 10000,
                'modules_json' => $bundles['growth'],
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price_monthly_minor' => 2999900,
                'price_yearly_minor' => 29999900,
                'max_branches' => 999,
                'max_users' => 999,
                'max_skus' => 999999,
                'modules_json' => $bundles['enterprise'],
            ],
        ];

        foreach ($rows as $row) {
            Plan::query()->updateOrCreate(
                ['slug' => $row['slug']],
                array_merge($row, [
                    'is_active' => true,
                    'trial_days' => 14,
                ]),
            );
        }
    }
}
