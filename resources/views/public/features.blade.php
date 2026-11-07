@extends('public.layout')

@section('title', 'Features')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">Every module in the pharmacy chain stack</h1>
        <p class="mt-4 max-w-2xl text-lg text-emerald-950/75">Mirrored from package manifests — enable per tenant, plan, or branch policy.</p>
        @php $byGroup = collect($modules)->groupBy('group'); @endphp
        @foreach ($byGroup as $group => $items)
            <div class="mt-12">
                <h2 class="font-display text-2xl font-semibold text-[#0B6E4F]">{{ $group }}</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    @foreach ($items as $module)
                        <article class="card-glass">
                            <h3 class="font-semibold">{{ $module['name'] }}</h3>
                            <p class="mt-2 text-sm text-emerald-950/70">{{ $module['description'] }}</p>
                            <p class="mt-3 text-xs font-mono text-emerald-900/50">{{ $module['key'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>
@endsection
