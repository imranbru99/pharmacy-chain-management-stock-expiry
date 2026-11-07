<?php

declare(strict_types=1);

namespace App\Modules\Billing\Models;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformInvoice extends Model
{
    protected $table = 'platform_invoices';

    protected $fillable = [
        'tenant_id',
        'number',
        'amount_minor',
        'status',
        'due_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
