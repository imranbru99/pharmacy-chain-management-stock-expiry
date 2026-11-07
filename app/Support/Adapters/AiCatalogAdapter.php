<?php

declare(strict_types=1);

namespace App\Support\Adapters;

use ImranDevBd\AiCatalog\Facades\AiCatalog;
use ImranDevBd\AiCatalog\PendingGeneration;

final class AiCatalogAdapter
{
    public function forTenant(): PendingGeneration
    {
        return AiCatalog::pending();
    }

    public function fromKeyword(string $keyword): PendingGeneration
    {
        return AiCatalog::fromKeyword($keyword);
    }

    public function audit(?int $tenantId = null, int $staleDays = 90): array
    {
        $tenantId ??= tenant_id();

        return AiCatalog::audit($tenantId !== null ? (string) $tenantId : null, $staleDays);
    }
}
