<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $sale->invoice_number ?? ($invoice_number ?? 'DEMO') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f5f5f5; }
        .meta { margin-top: 12px; }
    </style>
</head>
<body>
    @php
        $shopBn = $shopNameBn ?? 'ডেমো ফার্মা চেইন';
        $shopEn = $shopNameEn ?? 'Demo Pharma Chain';
        $invoiceNo = $sale->invoice_number ?? ($invoice_number ?? 'INV-DEMO');
        $branchName = $sale->branch?->name ?? ($branch ?? '—');
        $customerLabel = $sale->customer?->name_bn
            ?? $sale->customer?->name
            ?? ($customer ?? 'Walk-in / ওয়াক-ইন');
        $lineItems = isset($sale) && $sale?->items
            ? $sale->items
            : collect($items ?? []);
        $totalLabel = isset($sale)
            ? number_format(($sale->total_minor ?? 0) / 100, 2).' BDT'
            : ($total ?? ($total_en ?? '—'));
        $paidLabel = isset($sale)
            ? number_format(($sale->paid_minor ?? 0) / 100, 2).' BDT'
            : ($total ?? '—');
    @endphp

    <h1>{{ $shopBn }} / {{ $shopEn }}</h1>
    <p class="meta">
        <strong>ইনভয়েস / Invoice:</strong> {{ $invoiceNo }}<br>
        <strong>শাখা / Branch:</strong> {{ $branchName }}<br>
        <strong>গ্রাহক / Customer:</strong> {{ $customerLabel }}
        @if(!empty($customer_en))
            ({{ $customer_en }})
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>পণ্য / Product</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lineItems as $item)
                @if (is_array($item))
                    <tr>
                        <td>{{ $item['name_bn'] ?? '' }} {{ $item['name'] ?? 'Item' }}</td>
                        <td>{{ $item['qty'] ?? 1 }}</td>
                        <td>—</td>
                        <td>{{ $item['total'] ?? '—' }}</td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $item->product?->name ?? 'Item' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price_minor / 100, 2) }}</td>
                        <td>{{ number_format($item->line_total_minor / 100, 2) }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <p class="meta">
        <strong>মোট / Total:</strong> {{ $totalLabel }}<br>
        <strong>পরিশোধ / Paid:</strong> {{ $paidLabel }}
    </p>
</body>
</html>
