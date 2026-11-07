<?php

declare(strict_types=1);

namespace App\Support\Adapters;

use ImranDev\UnicodePdf\Facades\UnicodePdf;
use ImranDev\UnicodePdf\UnicodePdfDocument;

final class UnicodePdfAdapter
{
    public function html(string $html): UnicodePdfDocument
    {
        $locale = current_tenant()?->locale ?? 'en';

        return UnicodePdf::html($html)->locale($locale);
    }

    public function view(string $view, array $data = []): UnicodePdfDocument
    {
        return UnicodePdf::view($view, $data)->locale(current_tenant()?->locale ?? 'en');
    }
}
