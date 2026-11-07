<?php

declare(strict_types=1);

use App\Core\Tenancy\TenantContext;
use App\Modules\Catalog\Models\Product;
use App\Modules\Forecasting\Services\DemandForecastService;
use App\Modules\Sales\Models\Sale;
use App\Modules\Sales\Models\SaleItem;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Support\Carbon;

use function Tests\Helpers\createTenantWithBranch;

it('suggests reorder quantity from recent sales velocity', function (): void {
    [$tenant, $branch] = createTenantWithBranch();
    app(TenantContext::class)->set($tenant);

    $product = Product::query()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Vitamin C',
        'slug' => 'vitamin-c',
        'reorder_level' => 10,
    ]);

    StockBatch::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'product_id' => $product->id,
        'batch_number' => 'VIT-1',
        'expires_at' => Carbon::today()->addYear(),
        'quantity' => 5,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    $sale = Sale::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'invoice_number' => 'INV-TEST-1',
        'type' => 'retail',
        'status' => Sale::STATUS_COMPLETED,
        'subtotal_minor' => 9000,
        'discount_minor' => 0,
        'tax_minor' => 0,
        'total_minor' => 9000,
        'paid_minor' => 9000,
        'completed_at' => Carbon::now()->subDays(10),
    ]);

    SaleItem::query()->create([
        'sale_id' => $sale->id,
        'product_id' => $product->id,
        'quantity' => 90,
        'unit_price_minor' => 100,
        'discount_minor' => 0,
        'tax_minor' => 0,
        'line_total_minor' => 9000,
    ]);

    $suggestions = app(DemandForecastService::class)->suggestReorder($tenant->id, $branch->id);

    expect($suggestions)->not->toBeEmpty();

    $forProduct = collect($suggestions)->firstWhere('product_id', $product->id);
    expect($forProduct)->not->toBeNull();
    expect($forProduct['suggested_qty'])->toBeGreaterThan(0);
    expect($forProduct['reason'])->toContain('90-day sales');
});
