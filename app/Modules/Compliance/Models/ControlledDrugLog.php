<?php

declare(strict_types=1);

namespace App\Modules\Compliance\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Models\Branch;
use App\Models\User;
use App\Modules\Catalog\Models\Product;
use App\Modules\Prescriptions\Models\Prescription;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ControlledDrugLog extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'product_id',
        'batch_id',
        'quantity',
        'direction',
        'patient_name',
        'prescription_id',
        'user_id',
        'logged_at',
    ];

    protected function casts(): array
    {
        return ['logged_at' => 'datetime'];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'batch_id');
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
