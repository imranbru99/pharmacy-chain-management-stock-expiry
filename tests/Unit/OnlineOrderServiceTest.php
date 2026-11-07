<?php

declare(strict_types=1);

use App\Models\Branch;
use App\Modules\Customers\Models\Customer;
use App\Modules\OnlineOrders\Models\OnlineOrder;
use App\Modules\OnlineOrders\Models\OnlineOrderItem;
use App\Modules\OnlineOrders\Services\OnlineOrderService;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Support\Carbon;

use function Tests\Helpers\createProduct;
use function Tests\Helpers\createTenantWithBranch;
use function Tests\Helpers\setTenant;

it('assigns branch with sufficient stock', function (): void {
    [$tenant, $branchA] = createTenantWithBranch();
    setTenant($tenant);

    $branchB = Branch::query()->create([
        'tenant_id' => $tenant->id,
        'name' => 'North Branch',
        'slug' => 'north-branch',
        'code' => 'NB-'.uniqid(),
    ]);

    $product = createProduct($tenant);

    StockBatch::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branchA->id,
        'product_id' => $product->id,
        'batch_number' => 'A1',
        'expires_at' => Carbon::today()->addYear(),
        'quantity' => 1,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    StockBatch::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branchB->id,
        'product_id' => $product->id,
        'batch_number' => 'B1',
        'expires_at' => Carbon::today()->addYear(),
        'quantity' => 10,
        'status' => StockBatch::STATUS_AVAILABLE,
    ]);

    $customer = Customer::query()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Online Buyer',
    ]);

    $order = OnlineOrder::query()->create([
        'tenant_id' => $tenant->id,
        'customer_id' => $customer->id,
        'order_number' => 'ORD-'.uniqid(),
        'status' => OnlineOrder::STATUS_PLACED,
        'subtotal_minor' => 1000,
        'total_minor' => 1000,
    ]);

    OnlineOrderItem::query()->create([
        'online_order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 5,
        'unit_price_minor' => 200,
    ]);

    $assigned = app(OnlineOrderService::class)->assignNearestBranch($order->fresh());

    expect($assigned->branch_id)->toBe($branchB->id);
});
