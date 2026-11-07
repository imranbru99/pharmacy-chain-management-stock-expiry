<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class DemoController extends Controller
{
    /** @var array<string, string> */
    private const ROLE_EMAIL = [
        'superadmin' => 'superadmin@demo.test',
        'owner' => 'owner@demo.test',
        'regional' => 'regional@demo.test',
        'branchmanager' => 'branchmanager@demo.test',
        'pharmacist' => 'pharmacist@demo.test',
        'cashier' => 'cashier@demo.test',
        'storekeeper' => 'storekeeper@demo.test',
        'purchase' => 'purchase@demo.test',
        'accountant' => 'accountant@demo.test',
        'hr' => 'hr@demo.test',
        'warehouse' => 'warehouse@demo.test',
        'rider' => 'rider@demo.test',
        'supplier' => 'supplier@demo.test',
        'customer' => 'customer@demo.test',
        'doctor' => 'doctor@demo.test',
        'auditor' => 'auditor@demo.test',
    ];

    public function loginAs(Request $request, string $role): RedirectResponse
    {
        $email = self::ROLE_EMAIL[$role] ?? null;

        abort_if($email === null, 404);

        $user = User::query()->where('email', $email)->firstOrFail();

        Auth::login($user, true);
        $request->session()->regenerate();

        $panel = $user->isSuperAdmin() ? '/super-admin' : '/staff';

        return redirect()->to($panel);
    }
}
