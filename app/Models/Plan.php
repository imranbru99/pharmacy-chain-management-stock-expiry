<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\Attributes\Slug;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

#[Slug(from: 'name')]
class Plan extends Model
{
    use HasUniversalSlug;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'price_monthly_minor',
        'price_yearly_minor',
        'max_branches',
        'max_users',
        'max_skus',
        'modules_json',
        'is_active',
        'trial_days',
    ];

    protected function casts(): array
    {
        return [
            'price_monthly_minor' => 'integer',
            'price_yearly_minor' => 'integer',
            'max_branches' => 'integer',
            'max_users' => 'integer',
            'max_skus' => 'integer',
            'modules_json' => 'array',
            'is_active' => 'boolean',
            'trial_days' => 'integer',
        ];
    }

    protected $attributes = [
        'is_active' => true,
        'trial_days' => 14,
        'modules_json' => '[]',
    ];

    /**
     * @return HasMany<Tenant, $this>
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }
}
