<?php

declare(strict_types=1);

namespace App\Modules\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class CmsTestimonial extends Model
{
    protected $fillable = [
        'tenant_id',
        'author_name',
        'content',
        'rating',
        'is_published',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }
}
