<?php

declare(strict_types=1);

namespace App\Modules\Expiry\Services;

use App\Modules\Stock\Models\StockBatch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class ExpiryDashboardService
{
    /**
     * @return array<string, array{batches: int, quantity: int}>
     */
    public function buckets(?int $branchId = null): array
    {
        $today = Carbon::today();
        $ranges = [
            'expired' => [$today->copy()->subYears(10), $today->copy()->subDay()],
            'days_30' => [$today, $today->copy()->addDays(30)],
            'days_60' => [$today->copy()->addDays(31), $today->copy()->addDays(60)],
            'days_90' => [$today->copy()->addDays(61), $today->copy()->addDays(90)],
            'days_180' => [$today->copy()->addDays(91), $today->copy()->addDays(180)],
        ];

        $result = [];

        foreach ($ranges as $key => [$from, $to]) {
            $query = StockBatch::query()
                ->whereIn('status', [StockBatch::STATUS_AVAILABLE, StockBatch::STATUS_QUARANTINE])
                ->whereBetween('expires_at', [$from->toDateString(), $to->toDateString()]);

            if ($branchId !== null) {
                $query->where('branch_id', $branchId);
            }

            $stats = $query
                ->select([
                    DB::raw('COUNT(*) as batches'),
                    DB::raw('COALESCE(SUM(quantity), 0) as quantity'),
                ])
                ->first();

            $result[$key] = [
                'batches' => (int) ($stats->batches ?? 0),
                'quantity' => (int) ($stats->quantity ?? 0),
            ];
        }

        return $result;
    }
}
