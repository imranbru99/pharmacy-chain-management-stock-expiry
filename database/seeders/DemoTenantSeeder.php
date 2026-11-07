<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Modules\ModuleManager;
use App\Models\Branch;
use App\Models\Plan;
use App\Models\Tenant;
use Database\Seeders\Support\DemoState;
use Illuminate\Database\Seeder;

final class DemoTenantSeeder extends Seeder
{
    public function run(): void
    {
        $plan = Plan::query()->where('slug', 'enterprise')->firstOrFail();

        $tenant = Tenant::query()->updateOrCreate(
            ['slug' => 'demo-pharma-chain'],
            [
                'name' => 'Demo Pharma Chain',
                'email' => 'contact@demo-pharma.test',
                'phone' => '+8801700000000',
                'primary_color' => '#0B6E4F',
                'plan_id' => $plan->id,
                'is_active' => true,
                'settings' => ['allow_negative_stock' => false, 'demo' => true],
            ],
        );

        app(ModuleManager::class)->syncFromPlan($tenant, $plan);

        $warehouse = Branch::query()->updateOrCreate(
            ['tenant_id' => $tenant->id, 'code' => 'WH-DHKA'],
            [
                'name' => 'Central Warehouse Dhaka',
                'slug' => 'central-warehouse-dhaka',
                'type' => Branch::TYPE_WAREHOUSE,
                'address_line_1' => 'Tejgaon Industrial Area',
                'city' => 'Dhaka',
                'country' => 'BD',
                'is_active' => true,
            ],
        );

        $branches = [
            ['Dhanmondi Flagship', 'DHN-01', 'dhanmondi-flagship', 'Dhanmondi Rd 27', 'Dhaka'],
            ['Gulshan Express', 'GLS-02', 'gulshan-express', 'Gulshan 2 Circle', 'Dhaka'],
            ['Mirpur Community', 'MIR-03', 'mirpur-community', 'Mirpur 10', 'Dhaka'],
            ['Chattogram Portside', 'CTG-04', 'chattogram-portside', 'Agrabad', 'Chattogram'],
            ['Sylhet Green', 'SYL-05', 'sylhet-green', 'Zindabazar', 'Sylhet'],
            ['Rajshahi Care', 'RAJ-06', 'rajshahi-care', 'Padma Residential', 'Rajshahi'],
        ];

        $branchMap = [];

        foreach ($branches as [$name, $code, $slug, $addr, $city]) {
            $branch = Branch::query()->updateOrCreate(
                ['tenant_id' => $tenant->id, 'code' => $code],
                [
                    'name' => $name,
                    'slug' => $slug,
                    'type' => Branch::TYPE_BRANCH,
                    'address_line_1' => $addr,
                    'city' => $city,
                    'country' => 'BD',
                    'is_active' => true,
                    'is_24h' => str_contains($slug, 'gulshan'),
                ],
            );
            $branchMap[$slug] = $branch->id;
        }

        DemoState::$tenantId = $tenant->id;
        DemoState::$warehouseId = $warehouse->id;
        DemoState::$branchIds = array_values($branchMap);
        DemoState::$branches = array_merge(['warehouse' => $warehouse->id], $branchMap);
    }
}
