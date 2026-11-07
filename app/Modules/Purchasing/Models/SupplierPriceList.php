<?php

declare(strict_types=1);

namespace App\Modules\Purchasing\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Modules\Catalog\Models\Product;
use App\Modules\Suppliers\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPriceList extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'supplier_id',
        'unit_cost_minor',
    ];

    protected function casts(): array
    {
        return [
            'unit_cost_minor' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public static function defaultUnitCost(int $tenantId, int $productId, int $supplierId): int
    {
        $cost = self::query()
            ->where('tenant_id', $tenantId)
            ->where('product_id', $productId)
            ->where('supplier_id', $supplierId)
            ->value('unit_cost_minor');

        return $cost !== null ? (int) $cost : 0;
    }
}
