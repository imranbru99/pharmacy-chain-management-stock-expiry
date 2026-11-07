<?php

declare(strict_types=1);

namespace App\Filament\Resources\StockCountResource\Pages;

use App\Filament\Resources\StockCountResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockCount extends EditRecord
{
    protected static string $resource = StockCountResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
