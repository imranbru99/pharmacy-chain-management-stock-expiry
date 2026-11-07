<?php

declare(strict_types=1);

use App\Modules\Stock\Models\StockBatch;
use App\Modules\Stock\Models\StockLedger;
use App\Modules\Stock\Services\StockLedgerService;
use Illuminate\Support\Carbon;

use function Tests\Helpers\createProduct;
use function Tests\Helpers\createTenantWithBranch;

it('writes ledger and updates batch quantity on move', function (): void {
    [$tenant, $branch] = createTenantWithBranch();
    $product = createProduct($tenant);

    $batch = StockBatch::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'product_id' => $product->id,
        'batch_number' => 'LEDGER-1',
        'expires_at' => Carbon::today()->addYear(),
        'quantity' => 20,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    $ledger = app(StockLedgerService::class)->move(
        tenantId: $tenant->id,
        branchId: $branch->id,
        productId: $product->id,
        batchId: $batch->id,
        delta: -5,
        type: StockLedger::TYPE_SALE,
    );

    expect($ledger->quantity_after)->toBe(15);
    expect($batch->fresh()->quantity)->toBe(15);
});

it('rejects negative stock when not allowed', function (): void {
    [$tenant, $branch] = createTenantWithBranch();
    $product = createProduct($tenant);

    $batch = StockBatch::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'product_id' => $product->id,
        'batch_number' => 'LEDGER-2',
        'expires_at' => Carbon::today()->addYear(),
        'quantity' => 2,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    app(StockLedgerService::class)->move(
        tenantId: $tenant->id,
        branchId: $branch->id,
        productId: $product->id,
        batchId: $batch->id,
        delta: -10,
        type: StockLedger::TYPE_SALE,
    );
})->throws(RuntimeException::class);

it('allows negative stock when tenant setting enabled', function (): void {
    [$tenant, $branch] = createTenantWithBranch();
    $tenant->update(['settings' => ['allow_negative_stock' => true]]);
    $product = createProduct($tenant);

    $batch = StockBatch::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'product_id' => $product->id,
        'batch_number' => 'LEDGER-3',
        'expires_at' => Carbon::today()->addYear(),
        'quantity' => 2,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    app(StockLedgerService::class)->move(
        tenantId: $tenant->id,
        branchId: $branch->id,
        productId: $product->id,
        batchId: $batch->id,
        delta: -5,
        type: StockLedger::TYPE_ADJUSTMENT,
    );

    expect($batch->fresh()->quantity)->toBe(-3);
});
