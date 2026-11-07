<?php

declare(strict_types=1);

namespace App\Modules\Stock\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Models\Branch;
use App\Modules\Catalog\Models\Product;
use App\Modules\Suppliers\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class StockBatch extends Model
{
    use BelongsToTenant;
    use LogsActivity;
    use SoftDeletes;

    public const STATUS_AVAILABLE = 'available';

    public const STATUS_QUARANTINE = 'quarantine';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_RECALLED = 'recalled';

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'product_id',
        'batch_number',
        'manufactured_at',
        'expires_at',
        'quantity',
        'reserved_quantity',
        'purchase_price_minor',
        'mrp_minor',
        'status',
        'supplier_id',
    ];

    protected function casts(): array
    {
        return [
            'manufactured_at' => 'date',
            'expires_at' => 'date',
            'quantity' => 'integer',
            'reserved_quantity' => 'integer',
            'purchase_price_minor' => 'integer',
            'mrp_minor' => 'integer',
        ];
    }

    protected $attributes = [
        'quantity' => 0,
        'reserved_quantity' => 0,
        'purchase_price_minor' => 0,
        'mrp_minor' => 0,
        'status' => self::STATUS_AVAILABLE,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function availableQuantity(): int
    {
        return max(0, $this->quantity - $this->reserved_quantity);
    }

    /**
     * @return BelongsTo<Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<Supplier, $this>
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * @return HasMany<StockLedger, $this>
     */
    public function ledgers(): HasMany
    {
        return $this->hasMany(StockLedger::class, 'batch_id');
    }
}
