<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Tenancy\TenantContext;
use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetTenantFromUser
{
    public function __construct(
        private readonly TenantContext $tenantContext,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->tenantContext->clear();

        $user = $request->user();

        if ($user !== null && $user->tenant_id !== null) {
            $tenant = Tenant::query()->find($user->tenant_id);

            if ($tenant !== null && $tenant->is_active) {
                $this->tenantContext->set($tenant);
            }
        }

        return $next($request);
    }
}
