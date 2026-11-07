<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\Support\DemoState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class DemoStockSeeder extends Seeder
{
    private const TARGET_BATCHES = 5200;

    public function run(): void
    {
        $tenantId = DemoState::$tenantId;
        $productIds = DemoState::$productIds;
        $locations = array_merge([DemoState::$warehouseId], DemoState::$branchIds);
        $supplierIds = DemoState::$supplierIds;

        if ($tenantId === null || $productIds === [] || $locations === []) {
            return;
        }

        $now = now();
        $rows = [];
        $created = 0;

        while ($created < self::TARGET_BATCHES) {
            $productId = $productIds[$created % count($productIds)];
            $branchId = $locations[$created % count($locations)];
            $bucket = $created % 10;

            $expiresAt = match (true) {
                $bucket < 1 => Carbon::now()->subDays(random_int(10, 180)),
                $bucket < 4 => Carbon::now()->addDays(random_int(7, 90)),
                default => Carbon::now()->addMonths(random_int(4, 24)),
            };

            $batchNum = 'B'.str_pad((string) ($created + 1), 6, '0', STR_PAD_LEFT);
            $purchase = random_int(800, 18000);
            $mrp = (int) ($purchase * 1.35);

            $rows[] = [
                'tenant_id' => $tenantId,
                'branch_id' => $branchId,
                'product_id' => $productId,
                'batch_number' => $batchNum,
                'manufactured_at' => Carbon::now()->subMonths(random_int(1, 18)),
                'expires_at' => $expiresAt,
                'quantity' => random_int(5, 200),
                'reserved_quantity' => 0,
                'purchase_price_minor' => $purchase,
                'mrp_minor' => $mrp,
                'status' => $expiresAt->isPast() ? 'expired' : 'available',
                'supplier_id' => $supplierIds[$created % max(1, count($supplierIds))] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $created++;

            if (count($rows) >= 400) {
                DB::table('stock_batches')->insert($rows);
                $rows = [];
            }
        }

        if ($rows !== []) {
            DB::table('stock_batches')->insert($rows);
        }
    }
}
