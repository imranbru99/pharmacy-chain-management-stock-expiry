<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Modules\ModuleManager;
use App\Models\Branch;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\TenantModule;
use Database\Seeders\Support\DemoState;
use Illuminate\Database\Seeder;

final class SecondTenantSeeder extends Seeder
{
    public function run(): void
    {
        $plan = Plan::query()->where('slug', 'starter')->firstOrFail();

        $tenant = Tenant::query()->updateOrCreate(
            ['slug' => 'minimal-pharmacy'],
            [
                'name' => 'Minimal Pharmacy Co',
                'email' => 'hello@minimal-pharmacy.test',
                'plan_id' => $plan->id,
                'is_active' => true,
            ],
        );

        $manager = app(ModuleManager::class);
        $manager->syncFromPlan($tenant, $plan);

        TenantModule::query()->where('tenant_id', $tenant->id)->update(['enabled' => false]);

        foreach (['core', 'users', 'catalog', 'stock', 'pos', 'sales', 'customers', 'expiry', 'notifications'] as $key) {
            $manager->enable($tenant->id, $key);
        }

        Branch::query()->updateOrCreate(
            ['tenant_id' => $tenant->id, 'code' => 'MIN-01'],
            [
                'name' => 'Minimal Main',
                'slug' => 'minimal-main',
                'type' => Branch::TYPE_BRANCH,
                'city' => 'Dhaka',
                'country' => 'BD',
                'is_active' => true,
            ],
        );

        DemoState::$secondTenantId = $tenant->id;
    }
}
