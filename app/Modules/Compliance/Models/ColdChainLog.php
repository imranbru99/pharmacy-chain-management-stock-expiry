<?php

declare(strict_types=1);

namespace App\Modules\Compliance\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColdChainLog extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'recorded_at',
        'temperature_c',
        'humidity',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'temperature_c' => 'decimal:2',
            'humidity' => 'decimal:2',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
