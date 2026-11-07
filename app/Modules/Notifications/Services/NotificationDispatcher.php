<?php

declare(strict_types=1);

namespace App\Modules\Notifications\Services;

use App\Modules\Notifications\Models\NotificationTemplate;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification as NotificationFacade;

final class NotificationDispatcher
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function dispatch(string $eventKey, array $data, ?int $tenantId = null): bool
    {
        $tenantId ??= tenant_id();

        if ($tenantId === null) {
            return false;
        }

        $templates = NotificationTemplate::query()
            ->where('tenant_id', $tenantId)
            ->where('event_key', $eventKey)
            ->get();

        if ($templates->isEmpty()) {
            return false;
        }

        foreach ($templates as $template) {
            $body = $this->interpolate($template->body, $data);
            $subject = $this->interpolate((string) ($template->subject ?? $eventKey), $data);

            match ($template->channel) {
                'mail' => $this->sendMail($data, $subject, $body),
                'database' => $this->sendDatabase($data, $subject, $body),
                'sms' => $this->sendSms($data, $body),
                default => null,
            };
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function interpolate(string $text, array $data): string
    {
        return preg_replace_callback('/\{\{(\w+)\}\}/', function (array $m) use ($data): string {
            $key = $m[1];

            return is_scalar($data[$key] ?? null) ? (string) $data[$key] : '';
        }, $text) ?? $text;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function sendMail(array $data, string $subject, string $body): void
    {
        $to = $data['email'] ?? null;
        if (! is_string($to) || $to === '') {
            return;
        }

        Mail::raw($body, fn ($message) => $message->to($to)->subject($subject));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function sendDatabase(array $data, string $subject, string $body): void
    {
        $notifiable = $data['notifiable'] ?? null;
        if ($notifiable === null) {
            return;
        }

        NotificationFacade::send($notifiable, new DatabaseMessage([
            'subject' => $subject,
            'body' => $body,
        ]));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function sendSms(array $data, string $body): void
    {
        // SMS provider hook — no-op stub for channel wiring.
        unset($data, $body);
    }
}
