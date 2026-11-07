<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Modules\Expiry\Services\ExpiryDashboardService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExpiryBucketsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Expiry buckets';

    public static function canView(): bool
    {
        return module_enabled('expiry');
    }

    protected function getStats(): array
    {
        $buckets = app(ExpiryDashboardService::class)->buckets(tenant_id());

        return [
            Stat::make('≤30 days', (string) ($buckets['days_30']['quantity'] ?? 0))
                ->description(($buckets['days_30']['batches'] ?? 0).' batches'),
            Stat::make('31–60 days', (string) ($buckets['days_60']['quantity'] ?? 0))
                ->description(($buckets['days_60']['batches'] ?? 0).' batches'),
            Stat::make('61–90 days', (string) ($buckets['days_90']['quantity'] ?? 0))
                ->description(($buckets['days_90']['batches'] ?? 0).' batches'),
            Stat::make('Expired', (string) ($buckets['expired']['quantity'] ?? 0))
                ->description(($buckets['expired']['batches'] ?? 0).' batches')
                ->color('danger'),
        ];
    }
}
