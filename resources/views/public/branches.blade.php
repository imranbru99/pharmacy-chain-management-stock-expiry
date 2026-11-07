@extends('public.layout')
@section('title', 'Branches')
@section('content')
    <section class="mx-auto max-w-5xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">Branch locator</h1>
        <p class="mt-3 text-emerald-950/75">Demo chain branches — ready for map embeds and distance search.</p>
        <ul class="mt-10 space-y-4">
            @foreach ($branches as $branch)
                <li class="card-glass">
                    <p class="font-semibold">{{ $branch->name }}</p>
                    <p class="text-sm text-emerald-950/70">{{ $branch->address_line_1 }}, {{ $branch->city }} — {{ $branch->tenant->name ?? '' }}</p>
                    <p class="text-xs text-emerald-900/60">Code {{ $branch->code }} @if($branch->is_24h) · 24h @endif</p>
                </li>
            @endforeach
        </ul>
    </section>
@endsection
