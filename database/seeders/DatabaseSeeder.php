<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PlanSeeder::class,
            RolePermissionSeeder::class,
            DemoTenantSeeder::class,
            DemoUserSeeder::class,
            DemoCatalogSeeder::class,
            DemoSupplierSeeder::class,
            DemoStockSeeder::class,
            DemoCustomersSeeder::class,
            DemoSalesSeeder::class,
            SecondTenantSeeder::class,
            CmsSeeder::class,
        ]);
    }
}
