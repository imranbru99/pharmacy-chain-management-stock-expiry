<?php

declare(strict_types=1);

namespace App\Core\Tenancy;

use App\Models\Tenant;

final class TenantContext
{
    private ?Tenant $tenant = null;

    public function set(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function get(): ?Tenant
    {
        return $this->tenant;
    }

    public function clear(): void
    {
        $this->tenant = null;
    }

    public function id(): ?int
    {
        return $this->tenant?->id;
    }
}
