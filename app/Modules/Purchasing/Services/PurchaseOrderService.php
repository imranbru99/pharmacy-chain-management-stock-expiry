<?php

declare(strict_types=1);

namespace App\Modules\Purchasing\Services;

use App\Modules\Purchasing\Models\GoodsReceipt;
use App\Modules\Purchasing\Models\GoodsReceiptItem;
use App\Modules\Purchasing\Models\PurchaseOrder;
use App\Modules\Purchasing\Models\PurchaseOrderItem;
use App\Modules\Purchasing\Models\PurchaseRequisition;
use App\Modules\Purchasing\Models\SupplierPriceList;
use App\Modules\Stock\Models\StockBatch;
use App\Modules\Stock\Models\StockLedger;
use App\Modules\Stock\Services\StockLedgerService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class PurchaseOrderService
{
    public function __construct(
        private readonly StockLedgerService $ledger,
    ) {}

    public function createFromRequisition(
        PurchaseRequisition $requisition,
        int $supplierId,
        ?int $createdBy = null,
    ): PurchaseOrder {
        if ($requisition->status !== PurchaseRequisition::STATUS_APPROVED) {
            throw new RuntimeException('Only approved requisitions can be converted to purchase orders.');
        }

        return DB::transaction(function () use ($requisition, $supplierId, $createdBy): PurchaseOrder {
            $requisition->load('items');

            $po = PurchaseOrder::query()->create([
                'tenant_id' => $requisition->tenant_id,
                'supplier_id' => $supplierId,
                'branch_id' => $requisition->branch_id,
                'po_number' => $this->nextPoNumber($requisition->tenant_id),
                'status' => PurchaseOrder::STATUS_DRAFT,
                'subtotal_minor' => 0,
                'tax_minor' => 0,
                'total_minor' => 0,
                'created_by' => $createdBy,
            ]);

            $lineSubtotal = 0;
            foreach ($requisition->items as $reqItem) {
                $cost = SupplierPriceList::defaultUnitCost(
                    $requisition->tenant_id,
                    $reqItem->product_id,
                    $supplierId,
                );

                PurchaseOrderItem::query()->create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $reqItem->product_id,
                    'quantity' => $reqItem->quantity,
                    'received_quantity' => 0,
                    'unit_cost_minor' => $cost,
                ]);

                $lineSubtotal += $reqItem->quantity * $cost;
            }

            $po->subtotal_minor = $lineSubtotal;
            $po->total_minor = $lineSubtotal;
            $po->save();

            $requisition->status = PurchaseRequisition::STATUS_CONVERTED;
            $requisition->save();

            return $po->fresh('items');
        });
    }

    /**
     * @param  list<array{product_id: int, quantity: int, unit_cost_minor?: int}>  $lines
     */
    public function createDraft(
        int $tenantId,
        int $supplierId,
        int $branchId,
        array $lines,
        ?int $createdBy = null,
    ): PurchaseOrder {
        return DB::transaction(function () use ($tenantId, $supplierId, $branchId, $lines, $createdBy): PurchaseOrder {
            $subtotal = 0;
            foreach ($lines as $line) {
                $cost = $line['unit_cost_minor'] ?? SupplierPriceList::defaultUnitCost(
                    $tenantId,
                    $line['product_id'],
                    $supplierId,
                );
                $subtotal += $line['quantity'] * $cost;
            }

            $po = PurchaseOrder::query()->create([
                'tenant_id' => $tenantId,
                'supplier_id' => $supplierId,
                'branch_id' => $branchId,
                'po_number' => $this->nextPoNumber($tenantId),
                'status' => PurchaseOrder::STATUS_DRAFT,
                'subtotal_minor' => $subtotal,
                'tax_minor' => 0,
                'total_minor' => $subtotal,
                'created_by' => $createdBy,
            ]);

            foreach ($lines as $line) {
                $cost = $line['unit_cost_minor'] ?? SupplierPriceList::defaultUnitCost(
                    $tenantId,
                    $line['product_id'],
                    $supplierId,
                );

                PurchaseOrderItem::query()->create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $line['product_id'],
                    'quantity' => $line['quantity'],
                    'received_quantity' => 0,
                    'unit_cost_minor' => $cost,
                ]);
            }

            return $po->fresh('items');
        });
    }

    public function send(PurchaseOrder $order): PurchaseOrder
    {
        if ($order->status !== PurchaseOrder::STATUS_DRAFT) {
            throw new RuntimeException('Only draft purchase orders can be sent.');
        }

        $order->status = PurchaseOrder::STATUS_SENT;
        $order->ordered_at = Carbon::now();
        $order->save();

        return $order;
    }

    /**
     * @param  list<array{
     *     purchase_order_item_id: int,
     *     batch_number: string,
     *     manufactured_at?: ?string,
     *     expires_at: string,
     *     quantity: int,
     *     purchase_price_minor?: int,
     *     mrp_minor?: int
     * }>  $lines
     */
    public function receiveViaGrn(
        PurchaseOrder $order,
        array $lines,
        ?int $receivedBy = null,
        ?string $notes = null,
    ): GoodsReceipt {
        if (! in_array($order->status, [PurchaseOrder::STATUS_SENT, PurchaseOrder::STATUS_PARTIAL], true)) {
            throw new RuntimeException('Purchase order must be sent or partially received.');
        }

        return DB::transaction(function () use ($order, $lines, $receivedBy, $notes): GoodsReceipt {
            $grn = GoodsReceipt::query()->create([
                'tenant_id' => $order->tenant_id,
                'purchase_order_id' => $order->id,
                'branch_id' => $order->branch_id,
                'grn_number' => $this->nextGrnNumber($order->tenant_id),
                'received_by' => $receivedBy,
                'received_at' => Carbon::now(),
                'notes' => $notes,
            ]);

            foreach ($lines as $line) {
                /** @var PurchaseOrderItem $poItem */
                $poItem = PurchaseOrderItem::query()
                    ->whereKey($line['purchase_order_item_id'])
                    ->where('purchase_order_id', $order->id)
                    ->firstOrFail();

                $qty = $line['quantity'];
                $purchasePrice = $line['purchase_price_minor'] ?? $poItem->unit_cost_minor;
                $mrp = $line['mrp_minor'] ?? 0;

                GoodsReceiptItem::query()->create([
                    'goods_receipt_id' => $grn->id,
                    'purchase_order_item_id' => $poItem->id,
                    'product_id' => $poItem->product_id,
                    'batch_number' => $line['batch_number'],
                    'manufactured_at' => $line['manufactured_at'] ?? null,
                    'expires_at' => $line['expires_at'],
                    'quantity' => $qty,
                    'purchase_price_minor' => $purchasePrice,
                    'mrp_minor' => $mrp,
                ]);

                $batch = StockBatch::query()->firstOrCreate(
                    [
                        'branch_id' => $order->branch_id,
                        'product_id' => $poItem->product_id,
                        'batch_number' => $line['batch_number'],
                    ],
                    [
                        'tenant_id' => $order->tenant_id,
                        'manufactured_at' => $line['manufactured_at'] ?? null,
                        'expires_at' => $line['expires_at'],
                        'quantity' => 0,
                        'purchase_price_minor' => $purchasePrice,
                        'mrp_minor' => $mrp,
                        'status' => StockBatch::STATUS_AVAILABLE,
                        'supplier_id' => $order->supplier_id,
                    ],
                );

                $this->ledger->move(
                    tenantId: $order->tenant_id,
                    branchId: $order->branch_id,
                    productId: $poItem->product_id,
                    batchId: $batch->id,
                    delta: $qty,
                    type: StockLedger::TYPE_PURCHASE,
                    referenceType: GoodsReceipt::class,
                    referenceId: $grn->id,
                    reason: 'Goods receipt '.$grn->grn_number,
                    userId: $receivedBy,
                );

                $poItem->received_quantity += $qty;
                $poItem->save();
            }

            $order->load('items');
            $fullyReceived = $order->items->every(
                fn (PurchaseOrderItem $item): bool => $item->received_quantity >= $item->quantity,
            );
            $order->status = $fullyReceived
                ? PurchaseOrder::STATUS_RECEIVED
                : PurchaseOrder::STATUS_PARTIAL;
            $order->save();

            return $grn->fresh('items');
        });
    }

    private function nextPoNumber(int $tenantId): string
    {
        $count = PurchaseOrder::query()->where('tenant_id', $tenantId)->count() + 1;

        return 'PO-'.str_pad((string) $tenantId, 4, '0', STR_PAD_LEFT).'-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }

    private function nextGrnNumber(int $tenantId): string
    {
        $count = GoodsReceipt::query()->where('tenant_id', $tenantId)->count() + 1;

        return 'GRN-'.str_pad((string) $tenantId, 4, '0', STR_PAD_LEFT).'-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }
}
