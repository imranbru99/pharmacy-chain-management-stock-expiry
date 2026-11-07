<?php

declare(strict_types=1);

namespace App\Modules\OnlineOrders\Models;

use App\Modules\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlineOrderItem extends Model
{
    protected $fillable = [
        'online_order_id',
        'product_id',
        'quantity',
        'unit_price_minor',
    ];

    public function onlineOrder(): BelongsTo
    {
        return $this->belongsTo(OnlineOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
