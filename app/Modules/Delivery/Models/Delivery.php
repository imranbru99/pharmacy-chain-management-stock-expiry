<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Models\User;
use App\Modules\OnlineOrders\Models\OnlineOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'online_order_id',
        'rider_id',
        'status',
        'assigned_at',
        'picked_at',
        'delivered_at',
        'proof_path',
        'cod_collected_minor',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'picked_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function onlineOrder(): BelongsTo
    {
        return $this->belongsTo(OnlineOrder::class);
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rider_id');
    }
}
