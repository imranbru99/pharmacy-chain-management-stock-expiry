<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\Support\DemoState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class DemoSupplierSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = DemoState::$tenantId;
        if ($tenantId === null) {
            return;
        }

        $now = now();
        $rows = [];

        $names = [
            'MediTrade BD', 'PharmaLink Distributors', 'GreenLeaf Wholesale', 'Dhaka Med Supply',
            'CarePoint Logistics', 'HealthBridge Trading', 'Bengal Pharma Hub', 'National Drug Mart',
        ];

        for ($i = 1; $i <= 40; $i++) {
            $name = ($names[$i % count($names)] ?? 'Supplier').' '.$i;
            $rows[] = [
                'tenant_id' => $tenantId,
                'name' => $name,
                'slug' => Str::slug($name).'-'.$i,
                'email' => 'supplier'.$i.'@demo-supply.test',
                'phone' => '+8801'.random_int(300000000, 999999999),
                'payment_terms' => 'Net 30',
                'address' => 'Industrial zone, Bangladesh',
                'performance_score' => random_int(60, 99),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 20) as $chunk) {
            DB::table('suppliers')->insert($chunk);
        }

        DemoState::$supplierIds = DB::table('suppliers')
            ->where('tenant_id', $tenantId)
            ->pluck('id')
            ->all();
    }
}
