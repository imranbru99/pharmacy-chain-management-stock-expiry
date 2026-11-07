<?php

declare(strict_types=1);

use App\Core\Tenancy\TenantContext;
use App\Models\Branch;
use App\Models\Plan;
use App\Models\Tenant;
use App\Modules\Catalog\Models\Product;
use App\Modules\Pos\Services\PosService;
use App\Modules\Sales\Models\Sale;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Support\Facades\DB;

beforeEach(function (): void {
    $plan = Plan::query()->create([
        'name' => 'Starter',
        'slug' => 'starter',
    ]);

    $tenant = Tenant::query()->create([
        'name' => 'Test Pharmacy',
        'slug' => 'test-pharmacy',
        'plan_id' => $plan->id,
    ]);

    $branch = Branch::query()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Main',
        'slug' => 'main',
        'code' => 'MAIN',
    ]);

    app(TenantContext::class)->set($tenant);

    $this->tenant = $tenant;
    $this->branch = $branch;
});

it('completes a sale and decrements stock using FEFO', function (): void {
    $product = Product::query()->create([
        'tenant_id' => $this->tenant->id,
        'name' => 'Paracetamol 500mg',
        'slug' => 'paracetamol-500mg',
        'barcode' => '8901001',
        'selling_price_minor' => 500,
    ]);

    $earlierBatch = StockBatch::query()->create([
        'tenant_id' => $this->tenant->id,
        'branch_id' => $this->branch->id,
        'product_id' => $product->id,
        'batch_number' => 'B-EARLY',
        'expires_at' => now()->addMonths(3),
        'quantity' => 5,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    $laterBatch = StockBatch::query()->create([
        'tenant_id' => $this->tenant->id,
        'branch_id' => $this->branch->id,
        'product_id' => $product->id,
        'batch_number' => 'B-LATE',
        'expires_at' => now()->addMonths(12),
        'quantity' => 10,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    /** @var PosService $pos */
    $pos = app(PosService::class);
    $sale = $pos->startSale((int) $this->branch->id);
    $pos->addItem($sale, (int) $product->id, 7);
    $completed = $pos->complete($sale->fresh());

    expect($completed->status)->toBe('completed');

    expect($earlierBatch->fresh()->quantity)->toBe(0)
        ->and($laterBatch->fresh()->quantity)->toBe(8);

    $ledgerRows = DB::table('stock_ledgers')
        ->where('reference_type', Sale::class)
        ->where('reference_id', $completed->id)
        ->orderBy('id')
        ->get();

    expect($ledgerRows)->toHaveCount(2)
        ->and((int) $ledgerRows[0]->batch_id)->toBe($earlierBatch->id)
        ->and((int) $ledgerRows[0]->quantity_delta)->toBe(-5)
        ->and((int) $ledgerRows[1]->batch_id)->toBe($laterBatch->id)
        ->and((int) $ledgerRows[1]->quantity_delta)->toBe(-2);
});
