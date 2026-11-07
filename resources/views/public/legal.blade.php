@extends('public.layout')
@section('title', ucfirst($page))
@section('content')
    <section class="mx-auto max-w-3xl px-4 py-16 lg:px-6">
        <h1 class="font-display text-4xl font-bold text-emerald-950">{{ ucfirst($page) }}</h1>
        @php $row = \Illuminate\Support\Facades\DB::table('cms_pages')->whereNull('tenant_id')->where('slug', $page)->first(); @endphp
        <div class="prose mt-8">{!! $row->body ?? '<p>Policy content is managed in CMS.</p>' !!}</div>
    </section>
@endsection
