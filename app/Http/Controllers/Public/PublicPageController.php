<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Core\Modules\ModuleRegistry;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class PublicPageController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'modules' => $this->modules(),
        ]);
    }

    public function features(): View
    {
        return view('public.features', [
            'modules' => $this->modules(),
        ]);
    }

    public function moduleExplorer(): View
    {
        return view('public.module-explorer', [
            'modules' => $this->modules(),
        ]);
    }

    public function solutions(string $type): View
    {
        return view('public.solutions', compact('type'));
    }

    public function stockAndExpiry(): View
    {
        return view('public.stock-and-expiry');
    }

    public function pricing(): View
    {
        $plans = DB::table('plans')->where('is_active', true)->orderBy('price_monthly_minor')->get();

        return view('public.pricing', compact('plans'));
    }

    public function demo(): View
    {
        return view('public.demo');
    }

    public function store(): View
    {
        return view('public.store');
    }

    public function complianceSecurity(): View
    {
        return view('public.compliance-security');
    }

    public function integrations(): View
    {
        return view('public.integrations');
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function blog(): View
    {
        $posts = DB::table('cms_posts')->where('is_published', true)->orderByDesc('published_at')->limit(12)->get();

        return view('public.blog', compact('posts'));
    }

    public function faq(): View
    {
        return view('public.faq');
    }

    public function legal(string $page): View
    {
        $allowed = ['terms', 'privacy', 'refund'];

        abort_unless(in_array($page, $allowed, true), 404);

        return view('public.legal', ['page' => $page]);
    }

    public function branches(): View
    {
        $branches = Branch::query()
            ->with('tenant')
            ->where('is_active', true)
            ->where('type', Branch::TYPE_BRANCH)
            ->orderBy('city')
            ->limit(50)
            ->get();

        return view('public.branches', compact('branches'));
    }

    public function registerPharmacy(): View
    {
        return view('public.register-pharmacy');
    }

    public function portal(string $type): View
    {
        return view('public.portal', compact('type'));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function modules(): array
    {
        $out = [];

        foreach (app(ModuleRegistry::class)->all() as $key => $manifest) {
            $out[] = [
                'key' => $key,
                'name' => $manifest['name'] ?? $key,
                'description' => $manifest['description'] ?? '',
                'group' => $manifest['navigation']['group'] ?? 'Modules',
                'icon' => $manifest['navigation']['icon'] ?? 'heroicon-o-squares-2x2',
                'default_enabled' => (bool) ($manifest['default_enabled'] ?? false),
            ];
        }

        return $out;
    }
}
