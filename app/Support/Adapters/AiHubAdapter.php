<?php

declare(strict_types=1);

namespace App\Support\Adapters;

use App\Models\Tenant;
use ImranDevBd\AiHub\Facades\AIHub;
use ImranDevBd\AiHub\PendingRequest;

final class AiHubAdapter
{
    public function forTenant(?Tenant $tenant = null): PendingRequest
    {
        $tenant ??= current_tenant();
        $settings = $tenant?->settings ?? [];
        $ai = is_array($settings['ai'] ?? null) ? $settings['ai'] : [];

        $request = AIHub::provider($ai['provider'] ?? null);

        if (! empty($ai['model'])) {
            $request = $request->model((string) $ai['model']);
        }

        if (! empty($ai['api_key'])) {
            $request = $request->apiKey((string) $ai['api_key']);
        }

        return $request;
    }

    public function maskedPrompt(string $prompt): string
    {
        $masked = preg_replace('/\b\d{10,}\b/', '[PHONE]', $prompt) ?? $prompt;
        $masked = preg_replace('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b/i', '[EMAIL]', $masked) ?? $masked;

        return $masked;
    }

    public function chat(?string $provider = null, ?string $model = null): mixed
    {
        return AIHub::chat($provider, $model);
    }
}
