<?php

declare(strict_types=1);

namespace App\Support\Adapters;

use ImranDev\UniversalSlug\Facades\UniversalSlug;

final class UniversalSlugAdapter
{
    public function generate(string $value, string $separator = '-', ?string $language = null): string
    {
        $language ??= current_tenant()?->locale ?? 'en';

        return UniversalSlug::generate($value, $separator, $language);
    }

    public function uniqueRule(string $modelClass, string $column = 'slug', mixed $ignoreId = null): mixed
    {
        $rule = UniversalSlug::uniqueRule($modelClass, $column);

        if ($ignoreId !== null) {
            return $rule->ignore($ignoreId);
        }

        return $rule;
    }
}
