<?php

declare(strict_types=1);

namespace App\Modules\Stock\Services;

use App\Core\Modules\ModuleManager;
use App\Models\Tenant;
use App\Modules\Stock\Models\StockBatch;
use App\Modules\Stock\Models\StockLedger;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

final class StockLedgerService
{
    public function __construct(
        private readonly ModuleManager $moduleManager,
    ) {}

    public function move(
        int $tenantId,
        int $branchId,
        int $productId,
        ?int $batchId,
        int $delta,
        string $type,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $reason = null,
        ?int $userId = null,
    ): StockLedger {
        return DB::transaction(function () use (
            $tenantId,
            $branchId,
            $productId,
            $batchId,
            $delta,
            $type,
            $referenceType,
            $referenceId,
            $reason,
            $userId,
        ): StockLedger {
            $quantityAfter = 0;

            if ($batchId !== null) {
                $batch = StockBatch::query()
                    ->whereKey($batchId)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($batch->branch_id !== $branchId || $batch->product_id !== $productId) {
                    throw new InvalidArgumentException('Batch does not match branch/product.');
                }

                $newQty = $batch->quantity + $delta;

                if ($newQty < 0 && ! $this->allowsNegativeStock($tenantId)) {
                    throw new RuntimeException('Insufficient stock: negative quantity not allowed.');
                }

                $batch->quantity = $newQty;
                $batch->save();
                $quantityAfter = $batch->quantity;
            } else {
                if ($delta < 0 && ! $this->allowsNegativeStock($tenantId)) {
                    throw new RuntimeException('Batch is required for stock decrement.');
                }
            }

            return StockLedger::query()->create([
                'tenant_id' => $tenantId,
                'branch_id' => $branchId,
                'product_id' => $productId,
                'batch_id' => $batchId,
                'type' => $type,
                'quantity_delta' => $delta,
                'quantity_after' => $quantityAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'reason' => $reason,
                'user_id' => $userId,
            ]);
        });
    }

    private function allowsNegativeStock(int $tenantId): bool
    {
        $tenant = Tenant::query()->find($tenantId);
        $fromTenant = (bool) data_get($tenant?->settings, 'allow_negative_stock', false);

        if ($fromTenant) {
            return true;
        }

        $settings = $this->moduleManager->getSettings('stock', $tenantId);

        return (bool) ($settings['allow_negative_stock'] ?? false);
    }
}
