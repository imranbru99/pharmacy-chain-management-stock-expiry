<?php

declare(strict_types=1);

namespace App\Filament\Resources\ControlledDrugLogResource\Pages;

use App\Filament\Resources\ControlledDrugLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListControlledDrugLogs extends ListRecords
{
    protected static string $resource = ControlledDrugLogResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
