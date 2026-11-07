<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\Attributes\Slug;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Slug(from: 'name')]
class Product extends Model
{
    use BelongsToTenant;
    use HasUniversalSlug;
    use LogsActivity;
    use SoftDeletes;

    public const SCHEDULE_OTC = 'otc';

    public const SCHEDULE_RX = 'rx';

    public const SCHEDULE_CONTROLLED = 'controlled';

    public const UNIT_STRIP = 'strip';

    public const UNIT_BOX = 'box';

    public const UNIT_CARTON = 'carton';

    public const UNIT_PIECE = 'piece';

    protected $fillable = [
        'tenant_id',
        'category_id',
        'manufacturer_id',
        'name',
        'name_bn',
        'generic_name',
        'strength',
        'dosage_form',
        'pack_size',
        'sku',
        'barcode',
        'slug',
        'hsn_code',
        'schedule',
        'storage_condition',
        'reorder_level',
        'max_level',
        'unit',
        'units_per_pack',
        'selling_price_minor',
        'mrp_minor',
        'tax_rate',
        'requires_prescription',
        'is_active',
        'images',
    ];

    protected function casts(): array
    {
        return [
            'reorder_level' => 'integer',
            'max_level' => 'integer',
            'units_per_pack' => 'integer',
            'selling_price_minor' => 'integer',
            'mrp_minor' => 'integer',
            'tax_rate' => 'decimal:2',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
            'images' => 'array',
        ];
    }

    protected $attributes = [
        'schedule' => self::SCHEDULE_OTC,
        'unit' => self::UNIT_STRIP,
        'units_per_pack' => 1,
        'selling_price_minor' => 0,
        'mrp_minor' => 0,
        'tax_rate' => 0,
        'reorder_level' => 0,
        'requires_prescription' => false,
        'is_active' => true,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function getRetailPriceMinorAttribute(): int
    {
        return (int) $this->selling_price_minor;
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo<Manufacturer, $this>
     */
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    /**
     * @return HasMany<StockBatch, $this>
     */
    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    /**
     * @return BelongsToMany<Product, $this>
     */
    public function substitutes(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'product_substitutes',
            'product_id',
            'substitute_product_id',
        );
    }
}
