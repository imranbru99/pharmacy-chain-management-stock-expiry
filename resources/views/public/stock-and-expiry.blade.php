@extends('public.layout')

@section('title', 'Stock & Expiry')

@section('content')
    <section class="hero-bleed">
        <div class="relative mx-auto max-w-5xl px-4 py-20 text-center lg:px-6">
            <h1 class="font-display text-4xl font-bold text-emerald-950 md:text-5xl">FEFO-first stock across every branch</h1>
            <p class="mx-auto mt-5 max-w-2xl text-lg text-emerald-950/75">Batch-level inventory, expiry buckets, recalls, and inter-branch transfers — the flagship capability of AushadhiChain.</p>
        </div>
    </section>
    <section class="mx-auto grid max-w-6xl gap-6 px-4 py-16 md:grid-cols-3 lg:px-6">
        @foreach ([
            ['Near-expiry radar', 'Surface 30/60/90 day buckets per branch with pharmacist-friendly queues.'],
            ['Batch integrity', 'Purchase price, MRP, supplier, and ledger reason codes on every movement.'],
            ['Recall readiness', 'Quarantine batches and trace affected sales with compliance workflows.'],
        ] as [$title, $body])
            <article class="card-glass">
                <h2 class="font-display text-xl font-semibold">{{ $title }}</h2>
                <p class="mt-3 text-sm text-emerald-950/70">{{ $body }}</p>
            </article>
        @endforeach
    </section>
@endsection
