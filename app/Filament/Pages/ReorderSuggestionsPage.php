<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Core\Tenancy\TenantContext;
use App\Filament\Concerns\ChecksModuleAccessPage;
use App\Models\Branch;
use App\Modules\Forecasting\Services\DemandForecastService;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ReorderSuggestionsPage extends Page implements HasForms
{
    use ChecksModuleAccessPage;
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Purchasing';

    protected static ?string $navigationLabel = 'Reorder suggestions';

    protected static ?string $title = 'Reorder suggestions';

    protected static ?int $navigationSort = 74;

    protected string $view = 'filament.pages.reorder-suggestions';

    public ?int $branchId = null;

    /** @var list<array{product_id: int, suggested_qty: int, reason: string}> */
    public array $suggestions = [];

    protected static function moduleKey(): string
    {
        return 'forecasting';
    }

    public function mount(): void
    {
        $tenantId = app(TenantContext::class)->id();
        $branch = Branch::query()
            ->when($tenantId !== null, fn ($q) => $q->where('tenant_id', $tenantId))
            ->first();

        $this->branchId = $branch?->id;
        $this->form->fill(['branch_id' => $this->branchId]);
        $this->loadSuggestions();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->label('Branch')
                    ->options(fn (): array => Branch::query()
                        ->pluck('name', 'id')
                        ->all())
                    ->live()
                    ->afterStateUpdated(function (?int $state): void {
                        $this->branchId = $state;
                        $this->loadSuggestions();
                    }),
            ]);
    }

    public function loadSuggestions(): void
    {
        $tenantId = app(TenantContext::class)->id();
        if ($tenantId === null || $this->branchId === null) {
            $this->suggestions = [];

            return;
        }

        $this->suggestions = app(DemandForecastService::class)->suggestReorder($tenantId, $this->branchId);
    }
}
