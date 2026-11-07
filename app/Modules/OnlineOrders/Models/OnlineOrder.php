<?php

declare(strict_types=1);

namespace App\Modules\OnlineOrders\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Models\Branch;
use App\Modules\Customers\Models\Customer;
use App\Modules\Delivery\Models\Delivery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineOrder extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    public const STATUS_PLACED = 'placed';

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'customer_id',
        'order_number',
        'status',
        'subtotal_minor',
        'delivery_fee_minor',
        'total_minor',
        'payment_method',
        'payment_status',
        'prescription_file',
        'delivery_address',
    ];

    /**
     * @return BelongsTo<Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return HasMany<OnlineOrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OnlineOrderItem::class);
    }

    /**
     * @return HasOne<Delivery, $this>
     */
    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }
}
