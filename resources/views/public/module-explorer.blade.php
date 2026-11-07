@extends('public.layout')

@section('title', 'Module Explorer')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">Interactive module explorer</h1>
        <p class="mt-3 text-emerald-950/75">See how enabling modules shapes your Filament navigation groups.</p>
        <div class="mt-10">
            <livewire:module-explorer />
        </div>
    </section>
@endsection
