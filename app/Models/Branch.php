<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\Attributes\Slug;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

#[Slug(from: 'name')]
class Branch extends Model
{
    use BelongsToTenant;
    use HasUniversalSlug;
    use SoftDeletes;

    public const TYPE_WAREHOUSE = 'warehouse';

    public const TYPE_BRANCH = 'branch';

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'code',
        'type',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'license_number',
        'license_expires_at',
        'is_24h',
        'is_active',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'license_expires_at' => 'date',
            'is_24h' => 'boolean',
            'is_active' => 'boolean',
            'settings' => 'array',
        ];
    }

    protected $attributes = [
        'type' => self::TYPE_BRANCH,
        'country' => 'BD',
        'is_24h' => false,
        'is_active' => true,
    ];
}
