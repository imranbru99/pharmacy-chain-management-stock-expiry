<?php

declare(strict_types=1);

namespace App\Modules\Purchasing\Services;

use App\Modules\Purchasing\Models\SupplierPriceList;
use Illuminate\Support\Collection;

final class SupplierPriceComparisonService
{
    /**
     * @return Collection<int, array{supplier_id: int, supplier_name: string, unit_cost_minor: int}>
     */
    public function compare(int $tenantId, int $productId): Collection
    {
        return SupplierPriceList::query()
            ->where('tenant_id', $tenantId)
            ->where('product_id', $productId)
            ->with('supplier:id,name')
            ->orderBy('unit_cost_minor')
            ->get()
            ->map(fn (SupplierPriceList $row): array => [
                'supplier_id' => $row->supplier_id,
                'supplier_name' => (string) $row->supplier?->name,
                'unit_cost_minor' => $row->unit_cost_minor,
            ]);
    }

    public function cheapestSupplierId(int $tenantId, int $productId): ?int
    {
        $row = SupplierPriceList::query()
            ->where('tenant_id', $tenantId)
            ->where('product_id', $productId)
            ->orderBy('unit_cost_minor')
            ->first();

        return $row?->supplier_id;
    }
}
