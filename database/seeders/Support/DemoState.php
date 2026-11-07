<?php

declare(strict_types=1);

namespace Database\Seeders\Support;

final class DemoState
{
    public static ?int $tenantId = null;

    /** @var array<string, int> */
    public static array $branches = [];

    public static ?int $warehouseId = null;

    /** @var list<int> */
    public static array $branchIds = [];

    /** @var list<int> */
    public static array $productIds = [];

    /** @var list<int> */
    public static array $supplierIds = [];

    public static ?int $secondTenantId = null;
}
