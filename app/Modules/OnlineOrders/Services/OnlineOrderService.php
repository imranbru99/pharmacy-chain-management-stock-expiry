<?php

declare(strict_types=1);

namespace App\Modules\OnlineOrders\Services;

use App\Models\Branch;
use App\Modules\OnlineOrders\Models\OnlineOrder;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Support\Facades\DB;

final class OnlineOrderService
{
    public function assignNearestBranch(OnlineOrder $order): OnlineOrder
    {
        $order->loadMissing('items');

        if ($order->items->isEmpty()) {
            return $order;
        }

        return DB::transaction(function () use ($order): OnlineOrder {
            $branches = Branch::query()
                ->where('tenant_id', $order->tenant_id)
                ->where('is_active', true)
                ->get();

            $bestBranchId = null;
            $bestScore = -1;

            foreach ($branches as $branch) {
                $canFulfill = true;
                $totalStock = 0;

                foreach ($order->items as $item) {
                    $available = (int) StockBatch::query()
                        ->where('tenant_id', $order->tenant_id)
                        ->where('branch_id', $branch->id)
                        ->where('product_id', $item->product_id)
                        ->where('status', StockBatch::STATUS_AVAILABLE)
                        ->sum('quantity');

                    if ($available < $item->quantity) {
                        $canFulfill = false;
                        break;
                    }

                    $totalStock += $available;
                }

                if ($canFulfill && $totalStock > $bestScore) {
                    $bestScore = $totalStock;
                    $bestBranchId = $branch->id;
                }
            }

            if ($bestBranchId !== null) {
                $order->update(['branch_id' => $bestBranchId]);
            }

            return $order->fresh();
        });
    }
}
