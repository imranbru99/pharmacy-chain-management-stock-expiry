<?php

declare(strict_types=1);

namespace App\Modules\Prescriptions\Services;

use App\Modules\Prescriptions\Models\Prescription;
use App\Modules\Prescriptions\Models\PrescriptionItem;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

final class PrescriptionService
{
    public function verify(Prescription $prescription, ?int $userId = null): Prescription
    {
        if ($prescription->status !== Prescription::STATUS_PENDING) {
            throw new RuntimeException('Only pending prescriptions can be verified.');
        }

        $prescription->update([
            'status' => Prescription::STATUS_VERIFIED,
            'verified_by' => $userId ?? Auth::id(),
            'verified_at' => now(),
        ]);

        return $prescription->fresh();
    }

    public function partialFill(PrescriptionItem $item, int $quantity): PrescriptionItem
    {
        $remaining = $item->quantity - $item->quantity_filled;
        if ($quantity <= 0 || $quantity > $remaining) {
            throw new RuntimeException('Invalid fill quantity.');
        }

        $item->increment('quantity_filled', $quantity);
        $item = $item->fresh();

        $prescription = $item->prescription;
        $allFilled = $prescription->items()->get()->every(
            fn (PrescriptionItem $row): bool => $row->quantity_filled >= $row->quantity
        );

        $prescription->update([
            'status' => $allFilled ? Prescription::STATUS_FILLED : Prescription::STATUS_PARTIAL,
        ]);

        return $item;
    }

    public function refill(PrescriptionItem $item, int $quantity): PrescriptionItem
    {
        if ($item->refills_used >= $item->refills_allowed) {
            throw new RuntimeException('No refills remaining.');
        }

        $item->increment('refills_used');
        $item->increment('quantity_filled', $quantity);

        return $item->fresh();
    }
}
