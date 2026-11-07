<?php

declare(strict_types=1);

use App\Core\Middleware\EnsureModuleEnabled;
use App\Core\Modules\ModuleManager;
use App\Models\TenantModule;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function Tests\Helpers\createTenantWithBranch;
use function Tests\Helpers\setTenant;

it('reports module disabled and blocks middleware', function (): void {
    [$tenant] = createTenantWithBranch();
    setTenant($tenant);

    TenantModule::query()->create([
        'tenant_id' => $tenant->id,
        'module_key' => 'catalog',
        'enabled' => false,
    ]);

    expect(module_enabled('catalog', $tenant->id))->toBeFalse();
    expect(app(ModuleManager::class)->isEnabled('catalog', $tenant->id))->toBeFalse();

    $request = Request::create('/catalog', 'GET');
    $middleware = app(EnsureModuleEnabled::class);

    expect(fn () => $middleware->handle($request, fn () => response('ok'), 'catalog'))
        ->toThrow(HttpException::class);
});

it('allows request when module is enabled', function (): void {
    [$tenant] = createTenantWithBranch();

    expect(module_enabled('catalog', $tenant->id))->toBeTrue();

    $request = Request::create('/catalog', 'GET');
    $response = app(EnsureModuleEnabled::class)->handle(
        $request,
        fn () => response('ok', 200),
        'catalog',
    );

    expect($response->getStatusCode())->toBe(200);
});
