<?php

declare(strict_types=1);

use App\Core\Tenancy\TenantContext;
use App\Modules\Catalog\Models\Product;
use App\Modules\Purchasing\Models\GoodsReceipt;
use App\Modules\Purchasing\Models\PurchaseOrderItem;
use App\Modules\Purchasing\Services\PurchaseOrderService;
use App\Modules\Stock\Models\StockBatch;
use App\Modules\Stock\Models\StockLedger;
use App\Modules\Suppliers\Models\Supplier;
use Illuminate\Support\Carbon;

use function Tests\Helpers\createTenantWithBranch;

it('creates batch and increases stock when PO is received via GRN', function (): void {
    [$tenant, $branch] = createTenantWithBranch();
    app(TenantContext::class)->set($tenant);

    $supplier = Supplier::query()->create([
        'tenant_id' => $tenant->id,
        'name' => 'MedSupply Co',
        'slug' => 'medsupply-co',
    ]);

    $product = Product::query()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Amoxicillin 250mg',
        'slug' => 'amoxicillin-250mg',
    ]);

    /** @var PurchaseOrderService $service */
    $service = app(PurchaseOrderService::class);

    $po = $service->createDraft(
        tenantId: $tenant->id,
        supplierId: $supplier->id,
        branchId: $branch->id,
        lines: [
            ['product_id' => $product->id, 'quantity' => 50, 'unit_cost_minor' => 1200],
        ],
    );

    $service->send($po);

    /** @var PurchaseOrderItem $poItem */
    $poItem = $po->items()->first();

    $grn = $service->receiveViaGrn($po->fresh(), [
        [
            'purchase_order_item_id' => $poItem->id,
            'batch_number' => 'AMX-BATCH-1',
            'expires_at' => Carbon::today()->addYear()->toDateString(),
            'quantity' => 50,
            'purchase_price_minor' => 1200,
            'mrp_minor' => 2000,
        ],
    ]);

    expect($grn->items)->toHaveCount(1);
    expect($po->fresh()->status)->toBe('received');

    $batch = StockBatch::query()
        ->where('branch_id', $branch->id)
        ->where('product_id', $product->id)
        ->where('batch_number', 'AMX-BATCH-1')
        ->first();

    expect($batch)->not->toBeNull();
    expect($batch->quantity)->toBe(50);

    $ledger = StockLedger::query()
        ->where('reference_type', GoodsReceipt::class)
        ->where('reference_id', $grn->id)
        ->where('type', StockLedger::TYPE_PURCHASE)
        ->first();

    expect($ledger)->not->toBeNull();
    expect($ledger->quantity_delta)->toBe(50);
});
