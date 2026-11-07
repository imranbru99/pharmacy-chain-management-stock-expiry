<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Modules\Customers\Models\Customer;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->tenant_id !== null;
    }

    public function view(User $user, Customer $customer): bool
    {
        return (int) $user->tenant_id === (int) $customer->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->tenant_id !== null;
    }

    public function update(User $user, Customer $customer): bool
    {
        return (int) $user->tenant_id === (int) $customer->tenant_id;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return (int) $user->tenant_id === (int) $customer->tenant_id;
    }
}
