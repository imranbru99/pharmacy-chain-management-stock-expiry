<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Services;

use App\Modules\Delivery\Models\Delivery;
use App\Modules\OnlineOrders\Models\OnlineOrder;
use Illuminate\Support\Facades\DB;

final class DeliveryService
{
    public function createForOrder(OnlineOrder $order, ?int $riderId = null): Delivery
    {
        return DB::transaction(function () use ($order, $riderId): Delivery {
            return Delivery::query()->create([
                'tenant_id' => $order->tenant_id,
                'online_order_id' => $order->id,
                'rider_id' => $riderId,
                'status' => $riderId ? 'assigned' : 'pending',
                'assigned_at' => $riderId ? now() : null,
            ]);
        });
    }

    public function markPicked(Delivery $delivery): Delivery
    {
        $delivery->update([
            'status' => 'picked',
            'picked_at' => now(),
        ]);

        return $delivery->fresh();
    }

    public function markDelivered(Delivery $delivery, ?string $proofPath = null, ?int $codMinor = null): Delivery
    {
        $delivery->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'proof_path' => $proofPath,
            'cod_collected_minor' => $codMinor,
        ]);

        $delivery->onlineOrder?->update(['status' => 'delivered']);

        return $delivery->fresh();
    }
}
