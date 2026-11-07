<?php

declare(strict_types=1);

namespace App\Modules\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPost extends Model
{
    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'body',
        'seo_title',
        'seo_description',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
}
