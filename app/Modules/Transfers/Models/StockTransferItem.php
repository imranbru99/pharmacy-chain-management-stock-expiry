<?php

declare(strict_types=1);

namespace App\Modules\Transfers\Models;

use App\Modules\Catalog\Models\Product;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransferItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'transfer_id',
        'product_id',
        'batch_id',
        'quantity_requested',
        'quantity_sent',
        'quantity_received',
    ];

    protected function casts(): array
    {
        return [
            'quantity_requested' => 'integer',
            'quantity_sent' => 'integer',
            'quantity_received' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<StockTransfer, $this>
     */
    public function transfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class, 'transfer_id');
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<StockBatch, $this>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'batch_id');
    }
}
