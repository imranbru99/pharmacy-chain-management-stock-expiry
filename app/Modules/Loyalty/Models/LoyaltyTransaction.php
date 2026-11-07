<?php

declare(strict_types=1);

namespace App\Modules\Loyalty\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Modules\Customers\Models\Customer;
use App\Modules\Sales\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyTransaction extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'sale_id',
        'points_delta',
        'balance_after',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'points_delta' => 'integer',
            'balance_after' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo<Sale, $this>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
