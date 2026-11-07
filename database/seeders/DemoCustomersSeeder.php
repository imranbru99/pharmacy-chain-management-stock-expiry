<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\Support\DemoState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class DemoCustomersSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = DemoState::$tenantId;
        if ($tenantId === null) {
            return;
        }

        $now = now();
        $rows = [];

        $firstNames = ['Rahim', 'Karim', 'Fatima', 'Ayesha', 'Hasan', 'Nusrat', 'Imran', 'Sadia'];
        $lastNames = ['Ahmed', 'Begum', 'Islam', 'Hossain', 'Chowdhury', 'Akter', 'Rahman'];

        for ($i = 1; $i <= 500; $i++) {
            $name = $firstNames[$i % count($firstNames)].' '.$lastNames[$i % count($lastNames)];
            $rows[] = [
                'tenant_id' => $tenantId,
                'name' => $name,
                'name_bn' => null,
                'phone' => '017'.str_pad((string) (10000000 + $i), 8, '0', STR_PAD_LEFT),
                'email' => 'customer'.$i.'@demo.test',
                'loyalty_points' => random_int(0, 2500),
                'loyalty_tier' => $i % 50 === 0 ? 'gold' : 'standard',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($rows) >= 100) {
                DB::table('customers')->insert($rows);
                $rows = [];
            }
        }

        if ($rows !== []) {
            DB::table('customers')->insert($rows);
        }
    }
}
