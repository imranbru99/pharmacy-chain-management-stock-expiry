<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureModuleEnabled
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (! module_enabled($module)) {
            abort($request->expectsJson() ? 403 : 404);
        }

        return $next($request);
    }
}
