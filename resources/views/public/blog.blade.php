@extends('public.layout')
@section('title', 'Blog')
@section('content')
    <section class="mx-auto max-w-4xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">Blog</h1>
        <div class="mt-10 space-y-6">
            @forelse ($posts as $post)
                <article class="card-glass">
                    <h2 class="font-display text-2xl font-semibold">{{ $post->title }}</h2>
                    <p class="mt-2 text-sm text-emerald-950/70">{{ \Illuminate\Support\Str::limit(strip_tags($post->body ?? ''), 160) }}</p>
                </article>
            @empty
                <p class="text-sm text-emerald-950/70">No posts yet — seeded content appears after `db:seed`.</p>
            @endforelse
        </div>
    </section>
@endsection
