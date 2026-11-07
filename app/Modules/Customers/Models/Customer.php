<?php

declare(strict_types=1);

namespace App\Modules\Customers\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Modules\Prescriptions\Models\Prescription;
use App\Modules\Sales\Models\Sale;
use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'name_bn',
        'phone',
        'email',
        'date_of_birth',
        'gender',
        'address',
        'loyalty_points',
        'loyalty_tier',
        'allergies',
        'chronic_conditions',
        'family_of',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'loyalty_points' => 'integer',
            'allergies' => AsEncryptedArrayObject::class,
            'chronic_conditions' => AsEncryptedArrayObject::class,
        ];
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function familyHead(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'family_of');
    }

    /**
     * @return HasMany<Prescription, $this>
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * @return HasMany<Sale, $this>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
