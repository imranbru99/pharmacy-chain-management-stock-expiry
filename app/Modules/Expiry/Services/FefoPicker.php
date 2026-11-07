<?php

declare(strict_types=1);

namespace App\Modules\Expiry\Services;

use App\Modules\Expiry\Models\ProductRecall;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class FefoPicker
{
    /**
     * @return array<int, int> batch_id => quantity
     */
    public function pick(int $branchId, int $productId, int $quantityNeeded): array
    {
        if ($quantityNeeded <= 0) {
            return [];
        }

        return DB::transaction(function () use ($branchId, $productId, $quantityNeeded): array {
            $today = Carbon::today();

            $batches = StockBatch::query()
                ->where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->where('status', StockBatch::STATUS_AVAILABLE)
                ->where('expires_at', '>=', $today)
                ->where('quantity', '>', 0)
                ->orderBy('expires_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $recalledBatchNumbers = $this->recalledBatchNumbersForProduct($productId);

            $allocations = [];
            $remaining = $quantityNeeded;

            foreach ($batches as $batch) {
                if ($remaining <= 0) {
                    break;
                }

                if ($this->isBatchRecalled($batch, $recalledBatchNumbers)) {
                    continue;
                }

                $available = $batch->availableQuantity();

                if ($available <= 0) {
                    continue;
                }

                $take = min($available, $remaining);
                $allocations[$batch->id] = $take;
                $remaining -= $take;
            }

            if ($remaining > 0) {
                throw new RuntimeException("Unable to allocate {$quantityNeeded} units via FEFO; short by {$remaining}.");
            }

            return $allocations;
        });
    }

    /**
     * @return list<string>
     */
    private function recalledBatchNumbersForProduct(int $productId): array
    {
        $wildcard = ProductRecall::query()
            ->where('product_id', $productId)
            ->where('status', ProductRecall::STATUS_ACTIVE)
            ->whereNull('batch_number')
            ->exists();

        if ($wildcard) {
            return ['*'];
        }

        return ProductRecall::query()
            ->where('product_id', $productId)
            ->where('status', ProductRecall::STATUS_ACTIVE)
            ->whereNotNull('batch_number')
            ->pluck('batch_number')
            ->all();
    }

    /**
     * @param  list<string>  $recalledBatchNumbers
     */
    private function isBatchRecalled(StockBatch $batch, array $recalledBatchNumbers): bool
    {
        if ($batch->status === StockBatch::STATUS_RECALLED) {
            return true;
        }

        if (in_array('*', $recalledBatchNumbers, true)) {
            return true;
        }

        return in_array($batch->batch_number, $recalledBatchNumbers, true);
    }
}
