<?php

declare(strict_types=1);

namespace App\Modules\Transfers\Services;

use App\Modules\Stock\Models\StockBatch;
use App\Modules\Stock\Models\StockLedger;
use App\Modules\Stock\Services\StockLedgerService;
use App\Modules\Transfers\Models\StockTransfer;
use App\Modules\Transfers\Models\StockTransferItem;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

final class TransferService
{
    public function __construct(
        private readonly StockLedgerService $ledger,
    ) {}

    /**
     * @param  list<array{product_id: int, batch_id: ?int, quantity: int}>  $lines
     */
    public function request(
        int $tenantId,
        int $fromBranchId,
        int $toBranchId,
        array $lines,
        ?int $requestedBy = null,
        ?string $notes = null,
    ): StockTransfer {
        return DB::transaction(function () use ($tenantId, $fromBranchId, $toBranchId, $lines, $requestedBy, $notes): StockTransfer {
            $transfer = StockTransfer::query()->create([
                'tenant_id' => $tenantId,
                'from_branch_id' => $fromBranchId,
                'to_branch_id' => $toBranchId,
                'status' => StockTransfer::STATUS_REQUESTED,
                'requested_by' => $requestedBy,
                'notes' => $notes,
            ]);

            foreach ($lines as $line) {
                StockTransferItem::query()->create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $line['product_id'],
                    'batch_id' => $line['batch_id'] ?? null,
                    'quantity_requested' => $line['quantity'],
                    'quantity_sent' => 0,
                    'quantity_received' => 0,
                ]);
            }

            return $transfer->load('items');
        });
    }

    public function approve(StockTransfer $transfer, ?int $approvedBy = null): StockTransfer
    {
        if ($transfer->status !== StockTransfer::STATUS_REQUESTED) {
            throw new RuntimeException('Only requested transfers can be approved.');
        }

        $transfer->status = StockTransfer::STATUS_APPROVED;
        $transfer->approved_by = $approvedBy;
        $transfer->save();

        return $transfer;
    }

    /**
     * @param  array<int, int>  $sentQuantities  transfer_item_id => quantity_sent
     */
    public function dispatch(StockTransfer $transfer, array $sentQuantities, ?int $userId = null): StockTransfer
    {
        if (! in_array($transfer->status, [StockTransfer::STATUS_APPROVED, StockTransfer::STATUS_REQUESTED], true)) {
            throw new RuntimeException('Transfer cannot be dispatched in current status.');
        }

        return DB::transaction(function () use ($transfer, $sentQuantities, $userId): StockTransfer {
            foreach ($transfer->items as $item) {
                $qty = $sentQuantities[$item->id] ?? $item->quantity_requested;

                if ($qty <= 0) {
                    continue;
                }

                if ($item->batch_id === null) {
                    throw new InvalidArgumentException('Batch is required to dispatch transfer items.');
                }

                $this->ledger->move(
                    tenantId: $transfer->tenant_id,
                    branchId: $transfer->from_branch_id,
                    productId: $item->product_id,
                    batchId: $item->batch_id,
                    delta: -$qty,
                    type: StockLedger::TYPE_TRANSFER_OUT,
                    referenceType: StockTransfer::class,
                    referenceId: $transfer->id,
                    reason: 'Inter-branch transfer dispatch',
                    userId: $userId,
                );

                $item->quantity_sent = $qty;
                $item->save();
            }

            $transfer->status = StockTransfer::STATUS_DISPATCHED;
            $transfer->save();

            return $transfer->fresh('items');
        });
    }

    /**
     * @param  array<int, int>  $receivedQuantities  transfer_item_id => quantity_received
     */
    public function receive(StockTransfer $transfer, array $receivedQuantities, ?int $userId = null): StockTransfer
    {
        if (! in_array($transfer->status, [StockTransfer::STATUS_DISPATCHED, StockTransfer::STATUS_IN_TRANSIT], true)) {
            throw new RuntimeException('Transfer cannot be received in current status.');
        }

        return DB::transaction(function () use ($transfer, $receivedQuantities, $userId): StockTransfer {
            foreach ($transfer->items as $item) {
                $received = $receivedQuantities[$item->id] ?? $item->quantity_sent;
                $item->quantity_received = $received;
                $item->save();

                if ($received <= 0 || $item->batch_id === null) {
                    continue;
                }

                $sourceBatch = StockBatch::query()->findOrFail($item->batch_id);

                $destBatch = StockBatch::query()->firstOrCreate(
                    [
                        'branch_id' => $transfer->to_branch_id,
                        'product_id' => $item->product_id,
                        'batch_number' => $sourceBatch->batch_number,
                    ],
                    [
                        'tenant_id' => $transfer->tenant_id,
                        'manufactured_at' => $sourceBatch->manufactured_at,
                        'expires_at' => $sourceBatch->expires_at,
                        'quantity' => 0,
                        'purchase_price_minor' => $sourceBatch->purchase_price_minor,
                        'mrp_minor' => $sourceBatch->mrp_minor,
                        'status' => StockBatch::STATUS_AVAILABLE,
                        'supplier_id' => $sourceBatch->supplier_id,
                    ],
                );

                $this->ledger->move(
                    tenantId: $transfer->tenant_id,
                    branchId: $transfer->to_branch_id,
                    productId: $item->product_id,
                    batchId: $destBatch->id,
                    delta: $received,
                    type: StockLedger::TYPE_TRANSFER_IN,
                    referenceType: StockTransfer::class,
                    referenceId: $transfer->id,
                    reason: $received < $item->quantity_sent
                        ? 'Inter-branch transfer received (discrepancy)'
                        : 'Inter-branch transfer received',
                    userId: $userId,
                );
            }

            $transfer->status = StockTransfer::STATUS_RECEIVED;
            $transfer->save();

            return $transfer->fresh('items');
        });
    }
}
