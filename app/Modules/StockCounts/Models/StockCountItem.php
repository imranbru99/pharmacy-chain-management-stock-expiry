<?php

declare(strict_types=1);

namespace App\Modules\StockCounts\Models;

use App\Modules\Catalog\Models\Product;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockCountItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'stock_count_id',
        'product_id',
        'batch_id',
        'system_quantity',
        'counted_quantity',
        'variance',
    ];

    protected function casts(): array
    {
        return [
            'system_quantity' => 'integer',
            'counted_quantity' => 'integer',
            'variance' => 'integer',
        ];
    }

    public function stockCount(): BelongsTo
    {
        return $this->belongsTo(StockCount::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'batch_id');
    }
}
