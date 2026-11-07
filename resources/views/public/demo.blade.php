@extends('public.layout')

@section('title', 'Demo')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">One-click demo accounts</h1>
        <p class="mt-3 text-emerald-950/75">Password for every account: <code class="rounded bg-mint-wash px-2 py-1">password</code></p>
        <div class="mt-10 grid gap-3 sm:grid-cols-2">
            @foreach ([
                'superadmin' => 'Platform Super Admin',
                'owner' => 'Chain Owner',
                'regional' => 'Regional Manager',
                'branchmanager' => 'Branch Manager',
                'pharmacist' => 'Pharmacist',
                'cashier' => 'Cashier',
                'storekeeper' => 'Store Keeper',
                'purchase' => 'Purchase',
                'accountant' => 'Accountant',
                'hr' => 'HR',
                'warehouse' => 'Warehouse',
                'rider' => 'Rider',
                'supplier' => 'Supplier portal',
                'customer' => 'Customer portal',
                'doctor' => 'Doctor',
                'auditor' => 'Auditor',
            ] as $role => $label)
                <form method="post" action="{{ route('public.demo.login', $role) }}">
                    @csrf
                    <button type="submit" class="card-glass flex w-full items-center justify-between text-left transition hover:border-[#0B6E4F]">
                        <span>
                            <span class="font-semibold">{{ $label }}</span>
                            <span class="block text-xs text-emerald-900/60">{{ $role === 'superadmin' ? 'superadmin' : $role }}@demo.test</span>
                        </span>
                        <span class="text-xs font-semibold text-[#0B6E4F]">Login →</span>
                    </button>
                </form>
            @endforeach
        </div>
    </section>
@endsection
