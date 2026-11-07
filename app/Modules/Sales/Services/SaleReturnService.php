<?php

declare(strict_types=1);

namespace App\Modules\Sales\Services;

use App\Modules\Sales\Models\Sale;
use App\Modules\Sales\Models\SaleItem;
use App\Modules\Sales\Models\SaleReturn;
use App\Modules\Sales\Models\SaleReturnItem;
use App\Modules\Stock\Models\StockLedger;
use App\Modules\Stock\Services\StockLedgerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class SaleReturnService
{
    public function __construct(
        private readonly StockLedgerService $stockLedger,
    ) {}

    /**
     * @param  array<int, array{sale_item_id: int, quantity: int}>  $lines
     */
    public function process(Sale $sale, array $lines, ?string $reason = null): SaleReturn
    {
        if ($sale->status !== Sale::STATUS_COMPLETED) {
            throw new RuntimeException('Only completed sales can be returned.');
        }

        return DB::transaction(function () use ($sale, $lines, $reason): SaleReturn {
            $return = SaleReturn::query()->create([
                'sale_id' => $sale->id,
                'branch_id' => $sale->branch_id,
                'reason' => $reason,
                'processed_by' => Auth::id(),
            ]);

            $total = 0;

            foreach ($lines as $line) {
                /** @var SaleItem $saleItem */
                $saleItem = SaleItem::query()
                    ->where('sale_id', $sale->id)
                    ->whereKey($line['sale_item_id'])
                    ->firstOrFail();

                $qty = (int) $line['quantity'];
                if ($qty <= 0 || $qty > $saleItem->quantity) {
                    throw new RuntimeException('Invalid return quantity.');
                }

                $amount = (int) round($saleItem->unit_price_minor * $qty);
                $total += $amount;

                SaleReturnItem::query()->create([
                    'sale_return_id' => $return->id,
                    'sale_item_id' => $saleItem->id,
                    'product_id' => $saleItem->product_id,
                    'batch_id' => $saleItem->batch_id,
                    'quantity' => $qty,
                    'amount_minor' => $amount,
                ]);

                if ($saleItem->batch_id !== null) {
                    $this->stockLedger->move(
                        tenantId: (int) $sale->tenant_id,
                        branchId: (int) $sale->branch_id,
                        productId: (int) $saleItem->product_id,
                        batchId: (int) $saleItem->batch_id,
                        delta: $qty,
                        type: StockLedger::TYPE_SALE_RETURN,
                        referenceType: SaleReturn::class,
                        referenceId: (int) $return->id,
                        reason: $reason,
                    );
                }
            }

            $return->update(['total_minor' => $total]);
            $sale->update(['status' => Sale::STATUS_RETURNED]);

            return $return->fresh('items');
        });
    }
}
