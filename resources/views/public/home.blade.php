@extends('public.layout')

@section('title', 'Pharmacy Chain')

@section('content')
    <section class="hero-bleed">
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-20 lg:grid-cols-2 lg:px-6">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-[#0B6E4F]">AushadhiChain</p>
                <h1 class="font-display mt-4 text-4xl font-bold leading-tight text-emerald-950 md:text-5xl">
                    Every branch stocked, every batch accountable.
                </h1>
                <p class="mt-5 max-w-xl text-lg text-emerald-950/75">
                    Multi-branch pharmacy management with FEFO expiry discipline, POS, purchasing, and role-based control — built for modern chains in Bangladesh.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('public.demo') }}" class="btn-brand">Explore live demo</a>
                    <a href="{{ route('public.stock-expiry') }}" class="btn-outline-brand">Stock & expiry</a>
                    <a href="{{ route('public.register') }}" class="btn-outline-brand">Register pharmacy</a>
                </div>
            </div>
            <div class="card-glass relative">
                <p class="text-sm font-semibold text-[#0B6E4F]">Branch pulse</p>
                <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
                    <div class="rounded-xl bg-mint-wash p-4"><dt class="text-emerald-900/60">SKUs</dt><dd class="font-display text-2xl font-bold">1,500+</dd></div>
                    <div class="rounded-xl bg-mint-wash p-4"><dt class="text-emerald-900/60">Batches tracked</dt><dd class="font-display text-2xl font-bold">5,000+</dd></div>
                    <div class="rounded-xl bg-mint-wash p-4"><dt class="text-emerald-900/60">Branches</dt><dd class="font-display text-2xl font-bold">6 + WH</dd></div>
                    <div class="rounded-xl bg-mint-wash p-4"><dt class="text-emerald-900/60">Near expiry alerts</dt><dd class="font-display text-2xl font-bold">FEFO</dd></div>
                </dl>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 lg:px-6">
        <h2 class="font-display text-3xl font-bold text-emerald-950">Modular from dispensary to HQ</h2>
        <p class="mt-3 max-w-2xl text-emerald-950/75">Toggle modules per plan or branch — catalog, POS, transfers, prescriptions, delivery, and more.</p>
        <div class="mt-8 grid gap-4 md:grid-cols-3">
            @foreach (array_slice($modules ?? [], 0, 6) as $module)
                <article class="card-glass">
                    <p class="text-xs font-semibold uppercase tracking-wide text-[#0B6E4F]">{{ $module['group'] }}</p>
                    <h3 class="mt-2 font-display text-xl font-semibold">{{ $module['name'] }}</h3>
                    <p class="mt-2 text-sm text-emerald-950/70">{{ $module['description'] }}</p>
                </article>
            @endforeach
        </div>
        <a href="{{ route('public.features') }}" class="btn-outline-brand mt-8 inline-flex">View all modules</a>
    </section>
@endsection
