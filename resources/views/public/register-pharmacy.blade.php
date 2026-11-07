@extends('public.layout')
@section('title', 'Register pharmacy')
@section('content')
    <section class="mx-auto max-w-3xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">Register your pharmacy chain</h1>
        @if (session('registered'))
            <p class="mt-4 rounded-xl bg-mint-wash p-4 text-sm font-semibold text-[#0B6E4F]">Application received — our onboarding team will email you.</p>
        @endif
        <form method="post" action="{{ route('public.register.store') }}" class="card-glass mt-8 space-y-4">
            @csrf
            <input name="pharmacy_name" placeholder="Pharmacy legal name" required class="w-full rounded-xl border px-4 py-2" />
            <input name="contact_name" placeholder="Contact person" required class="w-full rounded-xl border px-4 py-2" />
            <input type="email" name="email" placeholder="Email" required class="w-full rounded-xl border px-4 py-2" />
            <input name="phone" placeholder="Phone" required class="w-full rounded-xl border px-4 py-2" />
            <input type="number" name="branch_count" min="1" value="1" placeholder="Branches" required class="w-full rounded-xl border px-4 py-2" />
            <button type="submit" class="btn-brand">Submit application</button>
        </form>
    </section>
@endsection
