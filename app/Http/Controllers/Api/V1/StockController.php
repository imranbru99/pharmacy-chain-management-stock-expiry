<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Modules\Stock\Models\StockBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class StockController extends Controller
{
    public function byBranch(Request $request, int $branch): JsonResponse
    {
        $branchModel = Branch::query()->findOrFail($branch);

        $batches = StockBatch::query()
            ->where('branch_id', $branchModel->id)
            ->where('quantity', '>', 0)
            ->with(['product:id,name,sku,barcode'])
            ->orderBy('expires_at')
            ->paginate(min(100, max(1, (int) $request->integer('per_page', 50))));

        return response()->json([
            'branch' => $branchModel->only(['id', 'name', 'code', 'city']),
            'batches' => $batches,
        ]);
    }
}
