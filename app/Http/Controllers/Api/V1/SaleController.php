<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\Sales\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class SaleController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price_minor' => ['required', 'integer', 'min:0'],
        ]);

        $subtotal = collect($validated['items'])->sum(
            fn (array $item): int => $item['quantity'] * $item['unit_price_minor'],
        );

        $sale = Sale::query()->create([
            'tenant_id' => $request->user()?->tenant_id,
            'branch_id' => $validated['branch_id'],
            'customer_id' => $validated['customer_id'] ?? null,
            'invoice_number' => 'API-'.now()->format('YmdHis').'-'.random_int(1000, 9999),
            'status' => Sale::STATUS_DRAFT,
            'subtotal_minor' => $subtotal,
            'total_minor' => $subtotal,
            'paid_minor' => 0,
        ]);

        foreach ($validated['items'] as $item) {
            $sale->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price_minor' => $item['unit_price_minor'],
                'line_total_minor' => $item['quantity'] * $item['unit_price_minor'],
            ]);
        }

        return response()->json(['sale' => $sale->load('items')], 201);
    }
}
