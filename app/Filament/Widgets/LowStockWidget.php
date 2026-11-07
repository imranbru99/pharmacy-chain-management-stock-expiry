<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Modules\Catalog\Models\Product;
use App\Modules\Stock\Models\StockBatch;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class LowStockWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Low stock';

    public static function canView(): bool
    {
        return module_enabled('stock') && module_enabled('catalog');
    }

    protected function getStats(): array
    {
        $tenantId = tenant_id();

        if ($tenantId === null) {
            return [Stat::make('SKUs below reorder', '—')];
        }

        $totals = StockBatch::query()
            ->where('tenant_id', $tenantId)
            ->where('status', StockBatch::STATUS_AVAILABLE)
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id');

        $lowCount = Product::query()
            ->where('products.tenant_id', $tenantId)
            ->where('reorder_level', '>', 0)
            ->leftJoinSub($totals, 'stock_totals', 'products.id', '=', 'stock_totals.product_id')
            ->whereRaw('COALESCE(stock_totals.total_qty, 0) < products.reorder_level')
            ->count();

        return [
            Stat::make('SKUs below reorder', (string) $lowCount)
                ->description('Across all branches')
                ->color($lowCount > 0 ? 'warning' : 'success'),
        ];
    }
}
