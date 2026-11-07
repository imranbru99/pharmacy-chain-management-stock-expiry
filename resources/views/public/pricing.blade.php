@extends('public.layout')
@section('title', 'Pricing')
@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">Plans that scale with branches</h1>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @forelse ($plans as $plan)
                <article class="card-glass flex flex-col">
                    <h2 class="font-display text-2xl font-bold">{{ $plan->name }}</h2>
                    <p class="mt-4 text-3xl font-bold text-[#0B6E4F]">৳{{ number_format($plan->price_monthly_minor / 100, 2) }}<span class="text-sm font-normal text-emerald-900/60">/mo</span></p>
                    <ul class="mt-6 space-y-2 text-sm text-emerald-950/75">
                        <li>{{ $plan->max_branches }} branches</li>
                        <li>{{ $plan->max_users }} users</li>
                        <li>{{ number_format($plan->max_skus) }} SKUs</li>
                    </ul>
                    <a href="{{ route('public.register') }}" class="btn-brand mt-auto pt-8">Start {{ $plan->trial_days }}-day trial</a>
                </article>
            @empty
                <p class="text-sm">Run migrations & seeders to load plans.</p>
            @endforelse
        </div>
    </section>
@endsection
