<?php

declare(strict_types=1);

namespace App\Modules\StockCounts\Services;

use App\Modules\Stock\Models\StockBatch;
use App\Modules\Stock\Models\StockLedger;
use App\Modules\Stock\Services\StockLedgerService;
use App\Modules\StockCounts\Models\StockCount;
use App\Modules\StockCounts\Models\StockCountItem;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class StockCountService
{
    public function __construct(
        private readonly StockLedgerService $ledger,
    ) {}

    public function start(
        int $tenantId,
        int $branchId,
        string $type = StockCount::TYPE_CYCLE,
        ?int $startedBy = null,
        ?string $notes = null,
    ): StockCount {
        return DB::transaction(function () use ($tenantId, $branchId, $type, $startedBy, $notes): StockCount {
            $count = StockCount::query()->create([
                'tenant_id' => $tenantId,
                'branch_id' => $branchId,
                'type' => $type,
                'status' => StockCount::STATUS_IN_PROGRESS,
                'started_by' => $startedBy,
                'notes' => $notes,
            ]);

            $batches = StockBatch::query()
                ->where('branch_id', $branchId)
                ->where('quantity', '>', 0)
                ->get();

            foreach ($batches as $batch) {
                StockCountItem::query()->create([
                    'stock_count_id' => $count->id,
                    'product_id' => $batch->product_id,
                    'batch_id' => $batch->id,
                    'system_quantity' => $batch->quantity,
                    'counted_quantity' => null,
                    'variance' => 0,
                ]);
            }

            return $count->fresh('items');
        });
    }

    /**
     * @param  array<int, int>  $countedQuantities  stock_count_item_id => counted_quantity
     */
    public function submit(StockCount $count, array $countedQuantities): StockCount
    {
        if ($count->status !== StockCount::STATUS_IN_PROGRESS) {
            throw new RuntimeException('Only in-progress stock counts can be submitted.');
        }

        return DB::transaction(function () use ($count, $countedQuantities): StockCount {
            foreach ($count->items as $item) {
                if (! array_key_exists($item->id, $countedQuantities)) {
                    continue;
                }

                $counted = $countedQuantities[$item->id];
                $item->counted_quantity = $counted;
                $item->variance = $counted - $item->system_quantity;
                $item->save();
            }

            $count->status = StockCount::STATUS_SUBMITTED;
            $count->save();

            return $count->fresh('items');
        });
    }

    public function approve(StockCount $count, ?int $approvedBy = null): StockCount
    {
        if ($count->status !== StockCount::STATUS_SUBMITTED) {
            throw new RuntimeException('Only submitted stock counts can be approved.');
        }

        return DB::transaction(function () use ($count, $approvedBy): StockCount {
            foreach ($count->items as $item) {
                if ($item->variance === 0 || $item->batch_id === null) {
                    continue;
                }

                $this->ledger->move(
                    tenantId: $count->tenant_id,
                    branchId: $count->branch_id,
                    productId: $item->product_id,
                    batchId: $item->batch_id,
                    delta: $item->variance,
                    type: StockLedger::TYPE_COUNT,
                    referenceType: StockCount::class,
                    referenceId: $count->id,
                    reason: 'Stock count approval',
                    userId: $approvedBy,
                );
            }

            $count->status = StockCount::STATUS_APPROVED;
            $count->approved_by = $approvedBy;
            $count->save();

            return $count->fresh('items');
        });
    }
}
