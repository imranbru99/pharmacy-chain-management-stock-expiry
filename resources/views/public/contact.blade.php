@extends('public.layout')

@section('title', 'Contact')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">Contact sales</h1>
        @if (session('contact_sent'))
            <p class="mt-4 rounded-xl bg-mint-wash p-4 text-sm font-semibold text-[#0B6E4F]">Thanks — we received your message.</p>
        @endif
        <form method="post" action="{{ route('public.contact.store') }}" class="card-glass mt-8 space-y-4">
            @csrf
            <div>
                <label class="text-sm font-semibold">Name</label>
                <input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border border-emerald-900/15 px-4 py-2" />
                @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-sm font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border border-emerald-900/15 px-4 py-2" />
            </div>
            <div>
                <label class="text-sm font-semibold">Phone</label>
                <input name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-xl border border-emerald-900/15 px-4 py-2" />
            </div>
            <div>
                <label class="text-sm font-semibold">Pharmacy name</label>
                <input name="pharmacy_name" value="{{ old('pharmacy_name') }}" class="mt-1 w-full rounded-xl border border-emerald-900/15 px-4 py-2" />
            </div>
            <div>
                <label class="text-sm font-semibold">Message</label>
                <textarea name="message" rows="5" required class="mt-1 w-full rounded-xl border border-emerald-900/15 px-4 py-2">{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="btn-brand">Send message</button>
        </form>
    </section>
@endsection
