<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $dir = $locale === 'ar' ? 'rtl' : 'ltr';
    $title = trim($__env->yieldContent('title', 'AushadhiChain'));
    $description = trim($__env->yieldContent('meta_description', 'Multi-branch pharmacy chain management with stock, expiry, and POS.'));
@endphp
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — AushadhiChain</title>
    <meta name="description" content="{{ $description }}">
    <meta property="og:title" content="{{ $title }} — AushadhiChain">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="public-site antialiased">
    <header class="sticky top-0 z-40 border-b border-emerald-900/10 bg-mint-soft/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 lg:px-6">
            <a href="{{ route('public.home') }}" class="font-display text-xl font-bold text-[#0B6E4F]">AushadhiChain</a>
            <nav class="hidden items-center gap-6 text-sm font-semibold text-emerald-950/80 lg:flex">
                <a href="{{ route('public.features') }}">Features</a>
                <a href="{{ route('public.stock-expiry') }}">Stock & Expiry</a>
                <a href="{{ route('public.pricing') }}">Pricing</a>
                <a href="{{ route('public.demo') }}">Demo</a>
                <a href="{{ route('public.contact') }}">Contact</a>
            </nav>
            <div class="flex items-center gap-2">
                <div class="flex rounded-full border border-emerald-800/15 bg-white p-1 text-xs font-semibold">
                    <a class="rounded-full px-2 py-1 {{ $locale === 'en' ? 'bg-[#0B6E4F] text-white' : '' }}" href="{{ route('public.locale', 'en') }}">EN</a>
                    <a class="rounded-full px-2 py-1 {{ $locale === 'bn' ? 'bg-[#0B6E4F] text-white' : '' }}" href="{{ route('public.locale', 'bn') }}">বাংলা</a>
                </div>
                <a href="{{ route('login') }}" class="btn-outline-brand hidden sm:inline-flex">Sign in</a>
                <a href="{{ route('public.register') }}" class="btn-brand">Start trial</a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-20 border-t border-emerald-900/10 bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 md:grid-cols-4 lg:px-6">
            <div>
                <p class="font-display text-lg font-bold text-[#0B6E4F]">AushadhiChain</p>
                <p class="mt-2 text-sm text-emerald-950/70">Pharmacy chain OS for stock discipline, branch visibility, and compliant sales.</p>
            </div>
            <div>
                <p class="font-semibold">Product</p>
                <ul class="mt-3 space-y-2 text-sm text-emerald-950/75">
                    <li><a href="{{ route('public.modules.explorer') }}">Module explorer</a></li>
                    <li><a href="{{ route('public.integrations') }}">Integrations</a></li>
                    <li><a href="{{ route('public.compliance') }}">Compliance</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold">Company</p>
                <ul class="mt-3 space-y-2 text-sm text-emerald-950/75">
                    <li><a href="{{ route('public.about') }}">About</a></li>
                    <li><a href="{{ route('public.blog') }}">Blog</a></li>
                    <li><a href="{{ route('public.branches') }}">Branch locator</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold">Legal</p>
                <ul class="mt-3 space-y-2 text-sm text-emerald-950/75">
                    <li><a href="{{ route('public.terms') }}">Terms</a></li>
                    <li><a href="{{ route('public.privacy') }}">Privacy</a></li>
                    <li><a href="{{ route('public.refund') }}">Refund</a></li>
                </ul>
            </div>
        </div>
    </footer>
    @livewireScripts
</body>
</html>
