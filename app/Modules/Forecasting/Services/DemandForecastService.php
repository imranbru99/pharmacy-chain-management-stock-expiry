<?php

declare(strict_types=1);

namespace App\Modules\Forecasting\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Purchasing\Models\PurchaseOrder;
use App\Modules\Purchasing\Services\PurchaseOrderService;
use App\Modules\Purchasing\Services\SupplierPriceComparisonService;
use App\Modules\Sales\Models\Sale;
use App\Modules\Sales\Models\SaleItem;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class DemandForecastService
{
    public const DEFAULT_LEAD_TIME_DAYS = 7;

    public const LOOKBACK_DAYS = 90;

    public function __construct(
        private readonly PurchaseOrderService $purchaseOrders,
        private readonly SupplierPriceComparisonService $priceComparison,
    ) {}

    /**
     * @return list<array{product_id: int, suggested_qty: int, reason: string}>
     */
    public function suggestReorder(int $tenantId, int $branchId, int $leadTimeDays = self::DEFAULT_LEAD_TIME_DAYS): array
    {
        $since = Carbon::now()->subDays(self::LOOKBACK_DAYS);

        $velocities = SaleItem::query()
            ->select('sale_items.product_id', DB::raw('SUM(sale_items.quantity) as sold_qty'))
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.tenant_id', $tenantId)
            ->where('sales.branch_id', $branchId)
            ->where('sales.status', Sale::STATUS_COMPLETED)
            ->where('sales.completed_at', '>=', $since)
            ->groupBy('sale_items.product_id')
            ->pluck('sold_qty', 'product_id');

        $stockLevels = StockBatch::query()
            ->select('product_id', DB::raw('SUM(quantity) as on_hand'))
            ->where('branch_id', $branchId)
            ->groupBy('product_id')
            ->pluck('on_hand', 'product_id');

        $productIds = $velocities->keys()
            ->merge($stockLevels->keys())
            ->unique()
            ->values();

        $suggestions = [];

        foreach ($productIds as $productId) {
            $productId = (int) $productId;
            $sold = (int) ($velocities[$productId] ?? 0);
            $dailyVelocity = $sold / self::LOOKBACK_DAYS;
            $onHand = (int) ($stockLevels[$productId] ?? 0);

            /** @var Product|null $product */
            $product = Product::query()->find($productId);
            $reorderLevel = $product?->reorder_level ?? 0;
            $safetyStock = max($reorderLevel, (int) ceil($dailyVelocity * $leadTimeDays));
            $targetStock = (int) ceil($dailyVelocity * ($leadTimeDays + self::LOOKBACK_DAYS / 30)) + $safetyStock;
            $suggested = max(0, $targetStock - $onHand);

            if ($suggested <= 0) {
                continue;
            }

            $reason = sprintf(
                '90-day sales: %d units (%.2f/day); on hand: %d; lead time %d days; safety %d',
                $sold,
                $dailyVelocity,
                $onHand,
                $leadTimeDays,
                $safetyStock,
            );

            $suggestions[] = [
                'product_id' => $productId,
                'suggested_qty' => $suggested,
                'reason' => $reason,
            ];
        }

        usort($suggestions, fn (array $a, array $b): int => $b['suggested_qty'] <=> $a['suggested_qty']);

        return $suggestions;
    }

    /**
     * @param  list<array{product_id: int, suggested_qty: int}>  $suggestions
     */
    public function createPurchaseOrderFromSuggestions(
        int $tenantId,
        int $branchId,
        array $suggestions,
        ?int $supplierId = null,
        ?int $createdBy = null,
    ): PurchaseOrder {
        $supplierId ??= $this->resolveSupplierId($tenantId, $suggestions);

        $lines = array_map(
            fn (array $row): array => [
                'product_id' => $row['product_id'],
                'quantity' => $row['suggested_qty'],
            ],
            $suggestions,
        );

        return $this->purchaseOrders->createDraft($tenantId, $supplierId, $branchId, $lines, $createdBy);
    }

    /**
     * @param  list<array{product_id: int, suggested_qty: int}>  $suggestions
     */
    private function resolveSupplierId(int $tenantId, array $suggestions): int
    {
        foreach ($suggestions as $row) {
            $supplierId = $this->priceComparison->cheapestSupplierId($tenantId, $row['product_id']);
            if ($supplierId !== null) {
                return $supplierId;
            }
        }

        throw new \RuntimeException('No supplier price list found for suggested products.');
    }
}
