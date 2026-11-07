<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Modules\Sales\Models\Sale;

class SalePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->tenant_id !== null;
    }

    public function view(User $user, Sale $sale): bool
    {
        return (int) $user->tenant_id === (int) $sale->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->tenant_id !== null;
    }

    public function update(User $user, Sale $sale): bool
    {
        return (int) $user->tenant_id === (int) $sale->tenant_id;
    }

    public function delete(User $user, Sale $sale): bool
    {
        return (int) $user->tenant_id === (int) $sale->tenant_id;
    }
}
