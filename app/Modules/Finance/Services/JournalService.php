<?php

declare(strict_types=1);

namespace App\Modules\Finance\Services;

use App\Modules\Finance\Models\JournalEntry;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class JournalService
{
    public function post(JournalEntry $entry, ?int $userId = null): JournalEntry
    {
        if ($entry->status === JournalEntry::STATUS_POSTED) {
            throw new RuntimeException('Journal entry is already posted.');
        }

        $entry->loadMissing('lines');

        $debits = $entry->lines->sum('debit_minor');
        $credits = $entry->lines->sum('credit_minor');

        if ($debits !== $credits) {
            throw new RuntimeException("Journal entry is not balanced: debits={$debits}, credits={$credits}.");
        }

        if ($debits === 0) {
            throw new RuntimeException('Journal entry has no amounts.');
        }

        return DB::transaction(function () use ($entry, $userId): JournalEntry {
            $entry->update([
                'status' => JournalEntry::STATUS_POSTED,
                'posted_by' => $userId,
                'posted_at' => now(),
            ]);

            return $entry->fresh(['lines']);
        });
    }
}
