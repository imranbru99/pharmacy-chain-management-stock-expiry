<?php

declare(strict_types=1);

use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\DemoController;
use App\Http\Controllers\Public\LocaleController;
use App\Http\Controllers\Public\PublicPageController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\TenantRegistrationController;
use App\Http\Middleware\SetPublicLocale;
use Illuminate\Support\Facades\Route;

Route::middleware([SetPublicLocale::class])->group(function (): void {
    Route::get('/', [PublicPageController::class, 'home'])->name('public.home');
    Route::get('/features', [PublicPageController::class, 'features'])->name('public.features');
    Route::get('/modules/explorer', [PublicPageController::class, 'moduleExplorer'])->name('public.modules.explorer');
    Route::get('/solutions/{type}', [PublicPageController::class, 'solutions'])->name('public.solutions');
    Route::get('/stock-and-expiry', [PublicPageController::class, 'stockAndExpiry'])->name('public.stock-expiry');
    Route::get('/pricing', [PublicPageController::class, 'pricing'])->name('public.pricing');
    Route::get('/demo', [PublicPageController::class, 'demo'])->name('public.demo');
    Route::post('/demo/login/{role}', [DemoController::class, 'loginAs'])->name('public.demo.login');
    Route::get('/store', [PublicPageController::class, 'store'])->name('public.store');
    Route::get('/compliance-security', [PublicPageController::class, 'complianceSecurity'])->name('public.compliance');
    Route::get('/integrations', [PublicPageController::class, 'integrations'])->name('public.integrations');
    Route::get('/about', [PublicPageController::class, 'about'])->name('public.about');
    Route::get('/contact', [PublicPageController::class, 'contact'])->name('public.contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('public.contact.store');
    Route::get('/blog', [PublicPageController::class, 'blog'])->name('public.blog');
    Route::get('/faq', [PublicPageController::class, 'faq'])->name('public.faq');
    Route::get('/terms', fn () => app(PublicPageController::class)->legal('terms'))->name('public.terms');
    Route::get('/privacy', fn () => app(PublicPageController::class)->legal('privacy'))->name('public.privacy');
    Route::get('/refund', fn () => app(PublicPageController::class)->legal('refund'))->name('public.refund');
    Route::get('/branches', [PublicPageController::class, 'branches'])->name('public.branches');
    Route::get('/register-pharmacy', [PublicPageController::class, 'registerPharmacy'])->name('public.register');
    Route::post('/register-pharmacy', [TenantRegistrationController::class, 'store'])->name('public.register.store');
    Route::get('/portal/customer', fn () => app(PublicPageController::class)->portal('customer'))->name('public.portal.customer');
    Route::get('/portal/supplier', fn () => app(PublicPageController::class)->portal('supplier'))->name('public.portal.supplier');
    Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('public.locale');
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('public.sitemap');
});

Route::redirect('/login', '/admin/login')->name('login');
