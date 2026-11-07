<?php

declare(strict_types=1);

namespace App\Modules\Expiry\Services;

use App\Modules\Expiry\Models\ProductRecall;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Support\Facades\DB;

final class RecallService
{
    public function activate(ProductRecall $recall): ProductRecall
    {
        return DB::transaction(function () use ($recall): ProductRecall {
            $query = StockBatch::query()
                ->where('product_id', $recall->product_id)
                ->where('tenant_id', $recall->tenant_id)
                ->lockForUpdate();

            if ($recall->batch_number !== null) {
                $query->where('batch_number', $recall->batch_number);
            }

            $query->update(['status' => StockBatch::STATUS_RECALLED]);

            $recall->status = ProductRecall::STATUS_ACTIVE;
            $recall->started_at ??= now();
            $recall->save();

            return $recall->fresh();
        });
    }

    public function close(ProductRecall $recall): ProductRecall
    {
        $recall->status = ProductRecall::STATUS_CLOSED;
        $recall->closed_at = now();
        $recall->save();

        return $recall;
    }
}
