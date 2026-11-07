<?php

declare(strict_types=1);

namespace App\Modules\Reports\Queries;

use App\Modules\Stock\Models\StockBatch;
use App\Support\Adapters\UnicodePdfAdapter;
use Illuminate\Support\Carbon;
use ImranDev\UnicodePdf\UnicodePdfDocument;

final class ExpiryReport
{
    public function __construct(
        private readonly UnicodePdfAdapter $pdf,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function rows(int $tenantId, ?int $branchId = null, int $withinDays = 90): array
    {
        $cutoff = Carbon::today()->addDays($withinDays);

        $query = StockBatch::query()
            ->where('tenant_id', $tenantId)
            ->where('quantity', '>', 0)
            ->whereDate('expires_at', '<=', $cutoff)
            ->with(['product', 'branch'])
            ->orderBy('expires_at');

        if ($branchId !== null) {
            $query->where('branch_id', $branchId);
        }

        return $query->get()->map(fn (StockBatch $batch): array => [
            'branch' => $batch->branch?->name,
            'product' => $batch->product?->name,
            'batch_number' => $batch->batch_number,
            'quantity' => $batch->quantity,
            'expires_at' => $batch->expires_at?->format('Y-m-d'),
        ])->all();
    }

    public function toPdf(int $tenantId, ?int $branchId = null): UnicodePdfDocument
    {
        return $this->pdf->view('pdf.report-expiry', [
            'rows' => $this->rows($tenantId, $branchId),
        ]);
    }
}
