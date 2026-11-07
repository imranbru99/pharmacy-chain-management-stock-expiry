<?php

declare(strict_types=1);

namespace App\Modules\Hr\Services;

use App\Modules\Hr\Models\PayrollItem;
use App\Support\Adapters\UnicodePdfAdapter;
use ImranDev\UnicodePdf\UnicodePdfDocument;

final class PayslipPdfService
{
    public function __construct(
        private readonly UnicodePdfAdapter $pdf,
    ) {}

    public function generate(PayrollItem $item): UnicodePdfDocument
    {
        $item->loadMissing(['employee', 'payrollRun']);

        return $this->pdf->view('pdf.payslip', [
            'item' => $item,
            'employee' => $item->employee,
            'run' => $item->payrollRun,
        ]);
    }
}
