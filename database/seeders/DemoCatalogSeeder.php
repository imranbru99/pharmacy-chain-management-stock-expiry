<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\Support\BdMedicineNames;
use Database\Seeders\Support\DemoState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class DemoCatalogSeeder extends Seeder
{
    private const TARGET = 1500;

    public function run(): void
    {
        $tenantId = DemoState::$tenantId;
        if ($tenantId === null) {
            return;
        }

        $now = now();

        $manufacturerId = DB::table('manufacturers')->insertGetId([
            'tenant_id' => $tenantId,
            'name' => 'Square Pharmaceuticals',
            'slug' => 'square-pharmaceuticals',
            'country' => 'BD',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('manufacturers')->insert([
            [
                'tenant_id' => $tenantId,
                'name' => 'Beximco Pharma',
                'slug' => 'beximco-pharma',
                'country' => 'BD',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Incepta Pharmaceuticals',
                'slug' => 'incepta-pharmaceuticals',
                'country' => 'BD',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $categoryId = DB::table('categories')->insertGetId([
            'tenant_id' => $tenantId,
            'name' => 'General Medicines',
            'slug' => 'general-medicines',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $base = BdMedicineNames::BASE;
        $rows = [];
        $ids = [];

        for ($i = 1; $i <= self::TARGET; $i++) {
            $sample = $base[($i - 1) % count($base)];
            $suffix = $i > count($base) ? ' '.(int) ($i / count($base)) : '';
            $name = $sample['en'].$suffix;
            $slug = Str::slug($name).'-'.$i;
            $mrp = random_int(1500, 25000);
            $sell = (int) ($mrp * 0.92);

            $rows[] = [
                'tenant_id' => $tenantId,
                'category_id' => $categoryId,
                'manufacturer_id' => $manufacturerId,
                'name' => $name,
                'name_bn' => $sample['bn'],
                'generic_name' => $sample['generic'],
                'strength' => $sample['strength'],
                'dosage_form' => $sample['form'],
                'pack_size' => '10 '.$sample['form'].'s',
                'sku' => 'SKU-'.str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                'barcode' => '880'.str_pad((string) (1000000000 + $i), 10, '0', STR_PAD_LEFT),
                'slug' => $slug,
                'schedule' => $i % 17 === 0 ? 'rx' : 'otc',
                'reorder_level' => 10,
                'unit' => 'strip',
                'units_per_pack' => 10,
                'selling_price_minor' => $sell,
                'mrp_minor' => $mrp,
                'requires_prescription' => $i % 17 === 0,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($rows) >= 250) {
                DB::table('products')->insert($rows);
                $rows = [];
            }
        }

        if ($rows !== []) {
            DB::table('products')->insert($rows);
        }

        DemoState::$productIds = DB::table('products')
            ->where('tenant_id', $tenantId)
            ->orderBy('id')
            ->pluck('id')
            ->all();
    }
}
