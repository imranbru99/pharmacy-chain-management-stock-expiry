<?php

declare(strict_types=1);

namespace App\Modules\Purchasing\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Models\Branch;
use App\Modules\Suppliers\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'supplier_id',
        'branch_id',
        'goods_receipt_id',
        'status',
        'reason',
        'total_minor',
    ];

    protected function casts(): array
    {
        return [
            'total_minor' => 'integer',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }
}
