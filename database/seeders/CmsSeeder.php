<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $pages = [
            ['About AushadhiChain', 'about', '<p>Multi-branch pharmacy operations built for Bangladesh and beyond.</p>'],
            ['FAQ', 'faq', '<p>Common questions about modules, pricing, and onboarding.</p>'],
            ['Terms of Service', 'terms', '<p>Standard terms for SaaS pharmacy chain software.</p>'],
            ['Privacy Policy', 'privacy', '<p>We respect patient and pharmacy data privacy.</p>'],
            ['Refund Policy', 'refund', '<p>Subscription refunds follow plan-specific rules.</p>'],
        ];

        foreach ($pages as [$title, $slug, $body]) {
            DB::table('cms_pages')->updateOrInsert(
                ['tenant_id' => null, 'slug' => $slug],
                [
                    'title' => $title,
                    'body' => $body,
                    'seo_title' => $title,
                    'seo_description' => strip_tags($body),
                    'is_published' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }

        DB::table('cms_posts')->updateOrInsert(
            ['tenant_id' => null, 'slug' => 'fefo-stock-best-practices'],
            [
                'title' => 'FEFO stock discipline across branches',
                'body' => '<p>How Pharmacy Chain helps you rotate batches before expiry.</p>',
                'is_published' => true,
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );
    }
}
