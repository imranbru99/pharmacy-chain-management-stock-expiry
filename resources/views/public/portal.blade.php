@extends('public.layout')
@section('title', ucfirst($type).' portal')
@section('content')
    <section class="mx-auto max-w-3xl px-4 py-16 text-center lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">{{ ucfirst($type) }} portal</h1>
        <p class="mt-4 text-emerald-950/75">Sign in with a demo {{ $type }} account from the <a class="font-semibold text-[#0B6E4F]" href="{{ route('public.demo') }}">demo page</a>.</p>
    </section>
@endsection
