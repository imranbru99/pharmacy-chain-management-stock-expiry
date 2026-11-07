<?php

declare(strict_types=1);

namespace App\Support\Adapters;

use Drutogoti\CaptureContext;
use Drutogoti\Models\DrutogotiRequest;
use Illuminate\Support\Facades\Config;

final class DrutogotiAdapter
{
    public function newCaptureContext(): CaptureContext
    {
        return new CaptureContext;
    }

    public function isEnabled(): bool
    {
        return (bool) Config::get('drutogoti.enabled', false);
    }

    public function latestRequest(?string $path = null): ?DrutogotiRequest
    {
        $query = DrutogotiRequest::query()->latest('id');

        if ($path !== null) {
            $query->where('path', $path);
        }

        return $query->first();
    }
}
