<?php

declare(strict_types=1);

namespace App\Modules\Sales\Services;

use App\Modules\Sales\Models\Sale;
use App\Support\Adapters\UnicodePdfAdapter;
use ImranDev\UnicodePdf\UnicodePdfDocument;

final class InvoicePdfService
{
    public function __construct(
        private readonly UnicodePdfAdapter $pdf,
    ) {}

    public function generate(Sale $sale): UnicodePdfDocument
    {
        $sale->load(['items.product', 'customer', 'branch', 'payments']);

        return $this->pdf->view('pdf.invoice', [
            'sale' => $sale,
            'shopNameBn' => 'ফার্মেসি চেইন',
            'shopNameEn' => current_tenant()?->name ?? 'Pharmacy Chain',
        ]);
    }
}
