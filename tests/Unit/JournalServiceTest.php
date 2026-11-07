<?php

declare(strict_types=1);

use App\Modules\Finance\Models\ChartOfAccount;
use App\Modules\Finance\Models\JournalEntry;
use App\Modules\Finance\Models\JournalLine;
use App\Modules\Finance\Services\JournalService;

use function Tests\Helpers\createTenantWithBranch;
use function Tests\Helpers\setTenant;

it('posts a balanced journal entry', function (): void {
    [$tenant, $branch] = createTenantWithBranch();
    setTenant($tenant);

    $debit = ChartOfAccount::query()->create([
        'tenant_id' => $tenant->id,
        'code' => '1000',
        'name' => 'Cash',
        'type' => ChartOfAccount::TYPE_ASSET,
        'is_active' => true,
    ]);

    $credit = ChartOfAccount::query()->create([
        'tenant_id' => $tenant->id,
        'code' => '4000',
        'name' => 'Sales',
        'type' => ChartOfAccount::TYPE_INCOME,
        'is_active' => true,
    ]);

    $entry = JournalEntry::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'entry_date' => now()->toDateString(),
        'status' => JournalEntry::STATUS_DRAFT,
    ]);

    JournalLine::query()->create([
        'journal_entry_id' => $entry->id,
        'account_id' => $debit->id,
        'debit_minor' => 5000,
        'credit_minor' => 0,
    ]);

    JournalLine::query()->create([
        'journal_entry_id' => $entry->id,
        'account_id' => $credit->id,
        'debit_minor' => 0,
        'credit_minor' => 5000,
    ]);

    $posted = app(JournalService::class)->post($entry);

    expect($posted->status)->toBe(JournalEntry::STATUS_POSTED);
    expect($posted->posted_at)->not->toBeNull();
});

it('rejects unbalanced journal entry', function (): void {
    [$tenant, $branch] = createTenantWithBranch();
    setTenant($tenant);

    $account = ChartOfAccount::query()->create([
        'tenant_id' => $tenant->id,
        'code' => '1001',
        'name' => 'Petty Cash',
        'type' => ChartOfAccount::TYPE_ASSET,
        'is_active' => true,
    ]);

    $entry = JournalEntry::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branch->id,
        'entry_date' => now()->toDateString(),
        'status' => JournalEntry::STATUS_DRAFT,
    ]);

    JournalLine::query()->create([
        'journal_entry_id' => $entry->id,
        'account_id' => $account->id,
        'debit_minor' => 1000,
        'credit_minor' => 0,
    ]);

    JournalLine::query()->create([
        'journal_entry_id' => $entry->id,
        'account_id' => $account->id,
        'debit_minor' => 0,
        'credit_minor' => 500,
    ]);

    app(JournalService::class)->post($entry);
})->throws(RuntimeException::class, 'not balanced');
