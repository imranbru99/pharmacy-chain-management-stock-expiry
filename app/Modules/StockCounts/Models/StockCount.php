<?php

declare(strict_types=1);

namespace App\Modules\StockCounts\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockCount extends Model
{
    use BelongsToTenant;

    public const TYPE_CYCLE = 'cycle';

    public const TYPE_FULL = 'full';

    public const TYPE_BLIND = 'blind';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'type',
        'status',
        'started_by',
        'approved_by',
        'notes',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function startedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'started_by');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * @return HasMany<StockCountItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(StockCountItem::class);
    }
}
