<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Support\DemoState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make(config('demo.password', 'password'));
        $tenantId = DemoState::$tenantId;
        $mainBranch = DemoState::$branchIds[0] ?? null;

        $accounts = [
            ['superadmin@demo.test', 'Platform Super Admin', null, null, 'super_admin', 'super_admin'],
            ['owner@demo.test', 'Chain Owner', $tenantId, null, 'owner', 'owner'],
            ['regional@demo.test', 'Regional Manager', $tenantId, null, 'staff', 'regional'],
            ['branchmanager@demo.test', 'Branch Manager', $tenantId, $mainBranch, 'staff', 'branch_manager'],
            ['pharmacist@demo.test', 'Lead Pharmacist', $tenantId, $mainBranch, 'staff', 'pharmacist'],
            ['cashier@demo.test', 'POS Cashier', $tenantId, $mainBranch, 'staff', 'cashier'],
            ['storekeeper@demo.test', 'Store Keeper', $tenantId, $mainBranch, 'staff', 'storekeeper'],
            ['purchase@demo.test', 'Purchase Officer', $tenantId, null, 'staff', 'purchase'],
            ['accountant@demo.test', 'Accountant', $tenantId, null, 'staff', 'accountant'],
            ['hr@demo.test', 'HR Manager', $tenantId, null, 'staff', 'hr'],
            ['warehouse@demo.test', 'Warehouse Lead', $tenantId, DemoState::$warehouseId, 'staff', 'warehouse'],
            ['rider@demo.test', 'Delivery Rider', $tenantId, $mainBranch, 'staff', 'rider'],
            ['supplier@demo.test', 'Demo Supplier User', $tenantId, null, 'supplier', 'supplier'],
            ['customer@demo.test', 'Demo Customer', $tenantId, null, 'customer', 'customer'],
            ['doctor@demo.test', 'Dr. Rahman', $tenantId, null, 'doctor', 'doctor'],
            ['auditor@demo.test', 'Compliance Auditor', $tenantId, null, 'staff', 'auditor'],
        ];

        foreach ($accounts as [$email, $name, $tid, $bid, $type, $role]) {
            $user = User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => $password,
                    'tenant_id' => $tid,
                    'branch_id' => $bid,
                    'user_type' => $type,
                    'is_demo' => true,
                    'email_verified_at' => now(),
                ],
            );

            $user->syncRoles([$role]);
        }
    }
}
