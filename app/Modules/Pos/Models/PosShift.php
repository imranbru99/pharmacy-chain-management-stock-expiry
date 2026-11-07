<?php

declare(strict_types=1);

namespace App\Modules\Pos\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosShift extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'user_id',
        'opened_at',
        'closed_at',
        'opening_cash_minor',
        'closing_cash_minor',
        'expected_cash_minor',
        'variance_minor',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'opening_cash_minor' => 'integer',
            'closing_cash_minor' => 'integer',
            'expected_cash_minor' => 'integer',
            'variance_minor' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
