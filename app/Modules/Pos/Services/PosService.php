<?php

declare(strict_types=1);

namespace App\Modules\Pos\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Expiry\Services\FefoPicker;
use App\Modules\Sales\Models\Sale;
use App\Modules\Sales\Models\SaleItem;
use App\Modules\Sales\Models\SalePayment;
use App\Modules\Stock\Models\StockLedger;
use App\Modules\Stock\Services\StockLedgerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

final class PosService
{
    public function __construct(
        private readonly FefoPicker $fefoPicker,
        private readonly StockLedgerService $stockLedger,
    ) {}

    public function startSale(int $branchId, ?int $customerId = null, ?int $cashierId = null): Sale
    {
        return Sale::query()->create([
            'tenant_id' => tenant_id(),
            'branch_id' => $branchId,
            'customer_id' => $customerId,
            'invoice_number' => $this->nextInvoiceNumber($branchId),
            'type' => 'retail',
            'status' => Sale::STATUS_DRAFT,
            'cashier_id' => $cashierId ?? Auth::id(),
        ]);
    }

    public function addItem(
        Sale $sale,
        int $productId,
        int $quantity,
        ?int $unitPriceMinor = null,
    ): SaleItem {
        $this->assertEditable($sale);

        $product = Product::query()->findOrFail($productId);
        $unitPriceMinor ??= (int) $product->selling_price_minor;

        $lineTotal = ($unitPriceMinor * $quantity);

        $item = $sale->items()->create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price_minor' => $unitPriceMinor,
            'line_total_minor' => $lineTotal,
        ]);

        $this->recalculateTotals($sale);

        return $item;
    }

    public function hold(Sale $sale): Sale
    {
        $this->assertEditable($sale);

        $sale->update([
            'status' => Sale::STATUS_HELD,
            'held_at' => now(),
        ]);

        return $sale->fresh();
    }

    public function resume(Sale $sale): Sale
    {
        if ($sale->status !== Sale::STATUS_HELD) {
            throw new RuntimeException('Only held sales can be resumed.');
        }

        $sale->update([
            'status' => Sale::STATUS_DRAFT,
            'held_at' => null,
        ]);

        return $sale->fresh();
    }

    /**
     * @param  array<int, array{method: string, amount_minor: int, reference?: string|null}>  $payments
     */
    public function complete(Sale $sale, array $payments = [], ?string $paymentMethod = 'cash'): Sale
    {
        if (! in_array($sale->status, [Sale::STATUS_DRAFT, Sale::STATUS_HELD], true)) {
            throw new RuntimeException('Sale cannot be completed.');
        }

        if ($sale->items()->count() === 0) {
            throw new RuntimeException('Cannot complete an empty sale.');
        }

        return DB::transaction(function () use ($sale, $payments, $paymentMethod): Sale {
            $sale = Sale::query()->whereKey($sale->id)->lockForUpdate()->firstOrFail();

            foreach ($sale->items()->whereNull('batch_id')->get() as $item) {
                $this->allocateBatchesAndStock($sale, $item);
            }

            $sale->refresh();
            $this->recalculateTotals($sale);
            $sale->refresh();

            if ($payments === []) {
                $payments = [[
                    'method' => $paymentMethod ?? 'cash',
                    'amount_minor' => (int) $sale->total_minor,
                ]];
            }

            $paid = 0;
            foreach ($payments as $payment) {
                $amount = (int) $payment['amount_minor'];
                $paid += $amount;
                SalePayment::query()->create([
                    'sale_id' => $sale->id,
                    'method' => $payment['method'],
                    'amount_minor' => $amount,
                    'reference' => $payment['reference'] ?? null,
                ]);
            }

            $method = count($payments) > 1 ? 'split' : ($payments[0]['method'] ?? 'cash');

            $sale->update([
                'status' => Sale::STATUS_COMPLETED,
                'payment_method' => $method,
                'paid_minor' => $paid,
                'completed_at' => now(),
                'held_at' => null,
            ]);

            return $sale->fresh(['items', 'payments']);
        });
    }

    /**
     * @param  array<int, array{method: string, amount_minor: int, reference?: string|null}>  $parts
     */
    public function splitPayment(Sale $sale, array $parts): Sale
    {
        $this->assertEditable($sale);

        $sale->payments()->delete();

        foreach ($parts as $part) {
            SalePayment::query()->create([
                'sale_id' => $sale->id,
                'method' => $part['method'],
                'amount_minor' => (int) $part['amount_minor'],
                'reference' => $part['reference'] ?? null,
            ]);
        }

        $paid = array_sum(array_map(fn (array $p): int => (int) $p['amount_minor'], $parts));

        $sale->update([
            'paid_minor' => $paid,
            'payment_method' => count($parts) > 1 ? 'split' : ($parts[0]['method'] ?? 'cash'),
        ]);

        return $sale->fresh('payments');
    }

    private function allocateBatchesAndStock(Sale $sale, SaleItem $item): void
    {
        $picks = $this->fefoPicker->pick(
            (int) $sale->branch_id,
            (int) $item->product_id,
            (int) $item->quantity,
        );

        $first = true;
        foreach ($picks as $batchId => $qty) {
            if ($first) {
                $lineTotal = (int) $item->unit_price_minor * $qty;
                $item->update([
                    'batch_id' => $batchId,
                    'quantity' => $qty,
                    'line_total_minor' => $lineTotal,
                ]);
                $first = false;
            } else {
                $lineTotal = (int) $item->unit_price_minor * $qty;
                $sale->items()->create([
                    'product_id' => $item->product_id,
                    'batch_id' => $batchId,
                    'quantity' => $qty,
                    'unit_price_minor' => $item->unit_price_minor,
                    'discount_minor' => $item->discount_minor,
                    'tax_minor' => $item->tax_minor,
                    'line_total_minor' => $lineTotal,
                ]);
            }

            $tenantId = (int) ($sale->tenant_id ?? tenant_id() ?? Product::query()->withoutGlobalScopes()->findOrFail($item->product_id)->tenant_id);

            $this->stockLedger->move(
                tenantId: $tenantId,
                branchId: (int) $sale->branch_id,
                productId: (int) $item->product_id,
                batchId: $batchId,
                delta: -$qty,
                type: StockLedger::TYPE_SALE,
                referenceType: Sale::class,
                referenceId: (int) $sale->id,
                reason: 'POS sale',
                userId: $sale->cashier_id ? (int) $sale->cashier_id : null,
            );
        }
    }

    private function recalculateTotals(Sale $sale): void
    {
        $sale->load('items');

        $subtotal = (int) $sale->items->sum('line_total_minor');
        $total = $subtotal - (int) $sale->discount_minor + (int) $sale->tax_minor;

        $sale->update([
            'subtotal_minor' => $subtotal,
            'total_minor' => max(0, $total),
        ]);
    }

    private function assertEditable(Sale $sale): void
    {
        if (! in_array($sale->status, [Sale::STATUS_DRAFT, Sale::STATUS_HELD], true)) {
            throw new RuntimeException('Sale is not editable.');
        }
    }

    private function nextInvoiceNumber(int $branchId): string
    {
        return sprintf('INV-%d-%s', $branchId, Str::upper(Str::random(8)));
    }
}
