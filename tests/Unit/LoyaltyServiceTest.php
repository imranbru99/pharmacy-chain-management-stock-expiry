<?php

declare(strict_types=1);

use App\Core\Tenancy\TenantContext;
use App\Models\Plan;
use App\Models\Tenant;
use App\Modules\Customers\Models\Customer;
use App\Modules\Loyalty\Services\LoyaltyService;

beforeEach(function (): void {
    $plan = Plan::query()->create([
        'name' => 'Starter',
        'slug' => 'starter-loyalty',
    ]);

    $tenant = Tenant::query()->create([
        'name' => 'Loyalty Test',
        'slug' => 'loyalty-test',
        'plan_id' => $plan->id,
    ]);

    app(TenantContext::class)->set($tenant);

    $this->customer = Customer::query()->create([
        'tenant_id' => $tenant->id,
        'name' => 'Rahim',
        'loyalty_points' => 0,
    ]);
});

it('earns points from sale totals', function (): void {
    /** @var LoyaltyService $loyalty */
    $loyalty = app(LoyaltyService::class);

    $txn = $loyalty->earn($this->customer, 15_000);

    expect($txn->points_delta)->toBe(150)
        ->and($txn->balance_after)->toBe(150)
        ->and($this->customer->fresh()->loyalty_points)->toBe(150);
});

it('redeems points when balance is sufficient', function (): void {
    $this->customer->update(['loyalty_points' => 200]);

    /** @var LoyaltyService $loyalty */
    $loyalty = app(LoyaltyService::class);

    $txn = $loyalty->redeem($this->customer->fresh(), 80);

    expect($txn->points_delta)->toBe(-80)
        ->and($txn->balance_after)->toBe(120)
        ->and($this->customer->fresh()->loyalty_points)->toBe(120);
});

it('throws when redeeming more points than available', function (): void {
    /** @var LoyaltyService $loyalty */
    $loyalty = app(LoyaltyService::class);

    $loyalty->redeem($this->customer, 10);
})->throws(RuntimeException::class);
