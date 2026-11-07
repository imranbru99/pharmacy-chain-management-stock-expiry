<?php

declare(strict_types=1);

namespace App\Modules\Ai\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiActionLog extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'action',
        'prompt_hash',
        'masked_prompt',
        'response_summary',
        'cost_usd',
        'provider',
        'model',
    ];

    protected function casts(): array
    {
        return ['cost_usd' => 'decimal:6'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
