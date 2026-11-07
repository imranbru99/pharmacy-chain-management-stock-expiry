<?php

declare(strict_types=1);

namespace App\Modules\Suppliers\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\Attributes\Slug;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Slug(from: 'name')]
class Supplier extends Model
{
    use BelongsToTenant;
    use HasUniversalSlug;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'email',
        'phone',
        'payment_terms',
        'address',
        'performance_score',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'performance_score' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected $attributes = [
        'is_active' => true,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * @return HasMany<StockBatch, $this>
     */
    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }
}
