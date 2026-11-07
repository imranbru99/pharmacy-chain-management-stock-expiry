<?php

declare(strict_types=1);

use App\Modules\Expiry\Services\FefoPicker;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Support\Carbon;

use function Tests\Helpers\createProduct;
use function Tests\Helpers\createTenantWithBranch;

it('allocates batches in FEFO order by expiry date', function (): void {
    [$tenant, $branch] = createTenantWithBranch();
    $product = createProduct($tenant);

    $later = StockBatch::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'product_id' => $product->id,
        'batch_number' => 'B-LATE',
        'expires_at' => Carbon::today()->addMonths(6),
        'quantity' => 10,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    $sooner = StockBatch::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'product_id' => $product->id,
        'batch_number' => 'B-SOON',
        'expires_at' => Carbon::today()->addMonth(),
        'quantity' => 5,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    $picked = app(FefoPicker::class)->pick($branch->id, $product->id, 7);

    expect($picked)->toBe([
        $sooner->id => 5,
        $later->id => 2,
    ]);
});

it('skips recalled batches', function (): void {
    [$tenant, $branch] = createTenantWithBranch();
    $product = createProduct($tenant);

    StockBatch::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'product_id' => $product->id,
        'batch_number' => 'B-OK',
        'expires_at' => Carbon::today()->addMonths(2),
        'quantity' => 5,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    StockBatch::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'product_id' => $product->id,
        'batch_number' => 'B-BAD',
        'expires_at' => Carbon::today()->addMonth(),
        'quantity' => 20,
        'status' => StockBatch::STATUS_RECALLED,
    ]);

    $picked = app(FefoPicker::class)->pick($branch->id, $product->id, 3);

    expect($picked)->toHaveCount(1);
    expect(array_sum($picked))->toBe(3);
});
