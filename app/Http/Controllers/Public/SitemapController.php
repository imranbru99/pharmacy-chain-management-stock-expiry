<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

final class SitemapController extends Controller
{
    public function index(): Response
    {
        $base = rtrim(config('app.url'), '/');
        $paths = [
            '/',
            '/features',
            '/modules/explorer',
            '/stock-and-expiry',
            '/pricing',
            '/demo',
            '/store',
            '/compliance-security',
            '/integrations',
            '/about',
            '/contact',
            '/blog',
            '/faq',
            '/terms',
            '/privacy',
            '/refund',
            '/branches',
            '/register-pharmacy',
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($paths as $path) {
            $xml .= '<url><loc>'.e($base.$path).'</loc><changefreq>weekly</changefreq></url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
