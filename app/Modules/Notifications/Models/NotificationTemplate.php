<?php

declare(strict_types=1);

namespace App\Modules\Notifications\Models;

use App\Core\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'event_key',
        'channel',
        'subject',
        'body',
    ];
}
