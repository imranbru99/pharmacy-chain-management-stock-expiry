<?php

declare(strict_types=1);

namespace App\Modules\Reports\Queries;

use App\Models\Branch;
use App\Modules\Sales\Models\Sale;
use App\Support\Adapters\UnicodePdfAdapter;
use Illuminate\Support\Facades\DB;
use ImranDev\UnicodePdf\UnicodePdfDocument;

final class SalesByBranchReport
{
    public function __construct(
        private readonly UnicodePdfAdapter $pdf,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function rows(int $tenantId): array
    {
        $aggregates = DB::table('sales')
            ->where('tenant_id', $tenantId)
            ->where('status', Sale::STATUS_COMPLETED)
            ->whereNull('deleted_at')
            ->select([
                'branch_id',
                DB::raw('COUNT(*) as sale_count'),
                DB::raw('SUM(total_minor) as total_minor'),
            ])
            ->groupBy('branch_id')
            ->get();

        $branchNames = Branch::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('id', $aggregates->pluck('branch_id'))
            ->pluck('name', 'id');

        return $aggregates->map(fn ($row): array => [
            'branch' => $branchNames[(int) $row->branch_id] ?? 'Branch #'.$row->branch_id,
            'sale_count' => (int) $row->sale_count,
            'total_minor' => (int) $row->total_minor,
        ])->all();
    }

    public function toPdf(int $tenantId): UnicodePdfDocument
    {
        return $this->pdf->view('pdf.report-sales-by-branch', [
            'rows' => $this->rows($tenantId),
        ]);
    }
}
