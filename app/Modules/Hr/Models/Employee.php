<?php

declare(strict_types=1);

namespace App\Modules\Hr\Models;

use App\Core\Tenancy\BelongsToTenant;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'branch_id',
        'employee_code',
        'name',
        'designation',
        'join_date',
        'salary_minor',
        'status',
        'attendance_device_user_id',
    ];

    protected function casts(): array
    {
        return ['join_date' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
