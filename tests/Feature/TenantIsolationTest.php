<?php

declare(strict_types=1);

use App\Core\Tenancy\TenantContext;
use App\Models\Tenant;
use App\Modules\Catalog\Models\Product;

use function Tests\Helpers\createProduct;
use function Tests\Helpers\createTenantWithBranch;
use function Tests\Helpers\setTenant;

it('scopes products to the active tenant context', function (): void {
    [$tenantA] = createTenantWithBranch('Pharmacy A');
    $productA = createProduct($tenantA, ['name' => 'Product A', 'slug' => 'product-a']);

    $tenantB = Tenant::query()->create([
        'name' => 'Pharmacy B',
        'slug' => 'pharmacy-b',
    ]);
    createProduct($tenantB, ['name' => 'Product B', 'slug' => 'product-b']);

    setTenant($tenantA);
    $visible = Product::query()->pluck('id')->all();

    expect($visible)->toBe([$productA->id]);

    app(TenantContext::class)->clear();
    expect(Product::query()->count())->toBe(2);
});
