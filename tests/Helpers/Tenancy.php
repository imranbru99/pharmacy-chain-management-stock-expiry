<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Core\Tenancy\TenantContext;
use App\Models\Branch;
use App\Models\Tenant;
use App\Modules\Catalog\Models\Product;

function setTenant(Tenant $tenant): void
{
    app(TenantContext::class)->set($tenant);
}

function createTenantWithBranch(string $name = 'Test Pharmacy'): array
{
    $tenant = Tenant::query()->create([
        'name' => $name,
        'slug' => str($name)->slug()->toString(),
    ]);

    $branch = Branch::query()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Main Branch',
        'slug' => 'main-branch',
        'code' => 'MB-'.uniqid(),
    ]);

    setTenant($tenant);

    return [$tenant, $branch];
}

function createProduct(Tenant $tenant, array $overrides = []): Product
{
    setTenant($tenant);

    return Product::query()->create(array_merge([
        'tenant_id' => $tenant->id,
        'name' => 'Paracetamol 500mg',
        'slug' => 'paracetamol-500mg-'.uniqid(),
    ], $overrides));
}
