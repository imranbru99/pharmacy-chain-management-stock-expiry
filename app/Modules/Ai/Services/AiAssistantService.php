<?php

declare(strict_types=1);

namespace App\Modules\Ai\Services;

use App\Modules\Ai\Models\AiActionLog;
use App\Support\Adapters\AiHubAdapter;
use Illuminate\Support\Str;

final class AiAssistantService
{
    public function __construct(
        private readonly AiHubAdapter $aiHub,
    ) {}

    public function ask(string $prompt, string $action = 'assistant', ?int $userId = null): string
    {
        $masked = $this->maskSensitive($this->aiHub->maskedPrompt($prompt));
        $hash = hash('sha256', $masked);

        try {
            $aiResponse = $this->aiHub->forTenant()->prompt($masked)->send();
            $text = $aiResponse->content;
            $summary = Str::limit($text, 500);
            $response = $text;
        } catch (\Throwable $e) {
            $summary = 'Error: '.$e->getMessage();
            $response = $summary;
        }

        AiActionLog::query()->create([
            'tenant_id' => tenant_id(),
            'user_id' => $userId,
            'action' => $action,
            'prompt_hash' => $hash,
            'masked_prompt' => $masked,
            'response_summary' => $summary,
            'provider' => null,
            'model' => null,
        ]);

        return is_string($response) ? $response : $summary;
    }

    public function maskSensitive(string $text): string
    {
        $text = preg_replace('/\b(?:patient|mr|mrs|ms)\.?\s+[A-Z][a-z]+(?:\s+[A-Z][a-z]+)?/i', '[PATIENT]', $text) ?? $text;

        return $this->aiHub->maskedPrompt($text);
    }
}
