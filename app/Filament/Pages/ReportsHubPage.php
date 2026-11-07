<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Concerns\ChecksModuleAccessPage;
use App\Modules\Reports\Services\ReportRegistry;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ReportsHubPage extends Page
{
    use ChecksModuleAccessPage;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Reports Hub';

    protected static ?string $title = 'Reports';

    protected string $view = 'filament.pages.reports-hub';

    /** @var array<string, string> */
    public array $reports = [];

    protected static function moduleKey(): string
    {
        return 'reports';
    }

    public function mount(ReportRegistry $registry): void
    {
        $this->reports = $registry->all();
    }
}
