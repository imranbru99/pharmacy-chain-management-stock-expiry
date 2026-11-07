@extends('public.layout')
@section('title', $heading)
@section('content')
    <section class="mx-auto max-w-4xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">{{ $heading }}</h1>
        @if (! empty($intro))
            <p class="mt-4 text-lg text-emerald-950/75">{{ $intro }}</p>
        @endif
        <div class="prose prose-emerald mt-8 max-w-none">
            {!! $body ?? '' !!}
        </div>
    </section>
@endsection
