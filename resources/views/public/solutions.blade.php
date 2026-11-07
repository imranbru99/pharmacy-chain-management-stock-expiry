@extends('public.layout')
@section('title', 'Solutions')
@section('content')
    <section class="mx-auto max-w-4xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">Solutions for {{ str($type)->headline() }}</h1>
        <p class="mt-4 text-lg text-emerald-950/75">Composable modules for {{ $type }} operators — start with stock & POS, expand into delivery, HR, and AI catalog.</p>
        <a href="{{ route('public.modules.explorer') }}" class="btn-brand mt-8 inline-flex">Explore modules</a>
    </section>
@endsection
