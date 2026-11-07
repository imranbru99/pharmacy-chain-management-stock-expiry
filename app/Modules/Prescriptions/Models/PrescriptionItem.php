<?php

declare(strict_types=1);

namespace App\Modules\Prescriptions\Models;

use App\Modules\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'prescription_id',
        'product_id',
        'dosage',
        'quantity',
        'quantity_filled',
        'refills_allowed',
        'refills_used',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'quantity_filled' => 'integer',
            'refills_allowed' => 'integer',
            'refills_used' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Prescription, $this>
     */
    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
