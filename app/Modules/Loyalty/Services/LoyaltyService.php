<?php

declare(strict_types=1);

namespace App\Modules\Loyalty\Services;

use App\Modules\Customers\Models\Customer;
use App\Modules\Loyalty\Models\LoyaltyTransaction;
use App\Modules\Sales\Models\Sale;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class LoyaltyService
{
    private const POINTS_PER_100_MINOR = 1;

    public function earn(Customer $customer, int $saleTotalMinor, ?Sale $sale = null, ?string $reason = null): LoyaltyTransaction
    {
        $points = max(0, (int) floor($saleTotalMinor / 100) * self::POINTS_PER_100_MINOR);

        return $this->applyDelta(
            customer: $customer,
            delta: $points,
            reason: $reason ?? 'Purchase reward',
            sale: $sale,
        );
    }

    public function redeem(Customer $customer, int $points, ?string $reason = null): LoyaltyTransaction
    {
        if ($points <= 0) {
            throw new RuntimeException('Points must be positive.');
        }

        if ($customer->loyalty_points < $points) {
            throw new RuntimeException('Insufficient loyalty points.');
        }

        return $this->applyDelta(
            customer: $customer,
            delta: -$points,
            reason: $reason ?? 'Points redeemed',
        );
    }

    private function applyDelta(
        Customer $customer,
        int $delta,
        string $reason,
        ?Sale $sale = null,
    ): LoyaltyTransaction {
        return DB::transaction(function () use ($customer, $delta, $reason, $sale): LoyaltyTransaction {
            $locked = Customer::query()->whereKey($customer->id)->lockForUpdate()->firstOrFail();
            $balance = (int) $locked->loyalty_points + $delta;

            if ($balance < 0) {
                throw new RuntimeException('Loyalty balance cannot be negative.');
            }

            $locked->update(['loyalty_points' => $balance]);

            return LoyaltyTransaction::query()->create([
                'customer_id' => $locked->id,
                'sale_id' => $sale?->id,
                'points_delta' => $delta,
                'balance_after' => $balance,
                'reason' => $reason,
            ]);
        });
    }
}
