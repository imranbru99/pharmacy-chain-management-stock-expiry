<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\Support\DemoState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class DemoSalesSeeder extends Seeder
{
    private const TARGET_SALES = 2000;

    public function run(): void
    {
        $tenantId = DemoState::$tenantId;
        if ($tenantId === null) {
            return;
        }

        $batches = DB::table('stock_batches')
            ->where('tenant_id', $tenantId)
            ->where('status', 'available')
            ->select('id', 'branch_id', 'product_id', 'mrp_minor')
            ->limit(3000)
            ->get();

        if ($batches->isEmpty()) {
            return;
        }

        $customers = DB::table('customers')->where('tenant_id', $tenantId)->pluck('id')->all();
        $now = Carbon::now();
        $saleRows = [];
        $itemRows = [];
        $saleId = (int) DB::table('sales')->max('id');

        for ($i = 1; $i <= self::TARGET_SALES; $i++) {
            $saleId++;
            $completedAt = $now->copy()->subMonths(random_int(0, 11))->subDays(random_int(0, 28));
            $batch = $batches[random_int(0, $batches->count() - 1)];
            $lineCount = random_int(1, 4);
            $subtotal = 0;

            for ($line = 0; $line < $lineCount; $line++) {
                $pick = $batches[random_int(0, $batches->count() - 1)];
                $qty = random_int(1, 3);
                $unit = (int) $pick->mrp_minor;
                $lineTotal = $unit * $qty;
                $subtotal += $lineTotal;

                $itemRows[] = [
                    'sale_id' => $saleId,
                    'product_id' => $pick->product_id,
                    'batch_id' => $pick->id,
                    'quantity' => $qty,
                    'unit_price_minor' => $unit,
                    'discount_minor' => 0,
                    'tax_minor' => 0,
                    'line_total_minor' => $lineTotal,
                    'created_at' => $completedAt,
                    'updated_at' => $completedAt,
                ];
            }

            $saleRows[] = [
                'tenant_id' => $tenantId,
                'branch_id' => $batch->branch_id,
                'customer_id' => $customers !== [] ? $customers[array_rand($customers)] : null,
                'invoice_number' => sprintf('INV-DEMO-%s-%05d', $completedAt->format('Ym'), $i),
                'type' => 'retail',
                'status' => 'completed',
                'subtotal_minor' => $subtotal,
                'discount_minor' => 0,
                'tax_minor' => 0,
                'total_minor' => $subtotal,
                'paid_minor' => $subtotal,
                'payment_method' => ['cash', 'card', 'mobile'][random_int(0, 2)],
                'completed_at' => $completedAt,
                'created_at' => $completedAt,
                'updated_at' => $completedAt,
            ];

            if (count($saleRows) >= 100) {
                DB::table('sales')->insert($saleRows);
                DB::table('sale_items')->insert($itemRows);
                $saleRows = [];
                $itemRows = [];
            }
        }

        if ($saleRows !== []) {
            DB::table('sales')->insert($saleRows);
        }
        if ($itemRows !== []) {
            DB::table('sale_items')->insert($itemRows);
        }
    }
}
