<?php

declare(strict_types=1);

namespace App\Filament\Resources\ColdChainLogResource\Pages;

use App\Filament\Resources\ColdChainLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListColdChainLogs extends ListRecords
{
    protected static string $resource = ColdChainLogResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
